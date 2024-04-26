<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Customer\LoginRegisterRequest;
use App\Http\Services\Message\Email\EmailService;
use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\Sms\SmsService;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class LoginRegisterController extends Controller
{
    protected SmsService $smsService;
    protected EmailService $emailService;

    public function __construct()
    {
        $this->smsService = new SmsService;
        $this->emailService = new EmailService;
    }

    public function loginRegisterForm()
    {
        return view('customer.auth.login-register');
    }

    public function loginRegister(LoginRegisterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $inputs = $request->all();
        //check id is email or not
        if (filter_var($inputs['id'], FILTER_VALIDATE_EMAIL)) {
            $type = 1; // 1 => email
            $user = User::where('email', $inputs['id'])->first();
            if (empty($user)) $newUser['email'] = $inputs['id'];

        } //check id is mobile or not
        elseif (preg_match('/^(\+98|98|0)9\d{9}$/', $inputs['id'])) {
            $type = 0; // 0 => mobile;
            // all mobile numbers are in on format 9** *** ***
            $inputs['id'] = $this->filterUserPhoneNumber($inputs['id']);
            $user = User::where('mobile', $inputs['id'])->first();
            if (empty($user)) $newUser['mobile'] = $inputs['id'];
        } else {
            return redirect()->route('auth.customer.login-register-form')->withErrors(
                [
                    'id' => 'شناسه ورودی شما نه شماره موبایل است نه ایمیل'
                ]
            );
        }
        if (empty($user)) {
            $newUser['password'] = '98355154';
            $newUser['activation'] = 1;
            $user = User::create($newUser);
        }
        //create otp code
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);
        Otp::create([
            'token' => $token,
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'login_id' => $inputs['id'],
            'type' => $type
        ]);
        //send sms or email
        if ($type === 0) {
            //sms
            $this->smsService->setFrom(Config::get('sms.otp_from'));
            $this->smsService->setTo(['0' . $user->mobile]);
            $this->smsService->setText("مجموعه آمازون \n  کد تایید : $otpCode");
            $this->smsService->setIsFlash(true);
        } else {
            // email
            $details = [
                'title' => 'ایمیل فعال سازی',
                'body' => "کد فعال سازی شما : $otpCode"
            ];
            $this->emailService->setDetails($details);
            $this->emailService->setFrom('noreply@example.com', 'example');
            $this->emailService->setSubject('کد احراز هویت');
            $this->emailService->setTo($inputs['id']);
        }
        $messagesService = new MessageService($type === 0 ? $this->smsService : $this->emailService);
        $messagesService->send();
        return redirect()->route('auth.customer.login-confirm-form', $token);
    }

    public function loginConfirmForm(string $token)
    {
        $otp = Otp::where('token', $token)->first();
        if (empty($otp)) {
            return redirect()->route('auth.customer.login-register-form')->withErrors(
                [
                    'id' => 'آدرس وارد شده نامعتبر میباشد'
                ]
            );
        }
        return view('customer.auth.login-confirm', compact('token', 'otp'));
    }

    public function loginConfirm($token, LoginRegisterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $inputs = $request->all();
        $otp = Otp::where('token', $token)->where('used', 0)->where('created_at', '>=', Carbon::now()->subMinute(5)->toDateTimeString())->first();
        if (empty($otp)) {
            return redirect()->route('auth.customer.login-register-form', $token)->withErrors(['id' => 'آدرس وارد شده نامعتبر میباشد']);
        }
        //if otp not match
        if ($otp->otp_code !== $inputs['otp']) {
            return redirect()->route('auth.customer.login-confirm-form', $token)->withErrors(
                [
                    'otp' => 'کد وارد شده صحیح نمیباشد'
                ]
            );
        }
        // if everything is ok :
        $otp->update(['used' => 1]);
        $user = $otp->user()->first();

        if ($otp->type == 0 && empty($user->mobile_verified_at)) {
            $user->update([
                'mobile_verified_at' => Carbon::now()
            ]);
        } elseif ($otp->type == 1 && empty($user->email_verified_at)) {
            $user->update([
                'email_verified_at' => Carbon::now()
            ]);
        }
        Auth::login($user);
        return redirect()->route('customer.home');
    }

    public function loginResendOtp($token): \Illuminate\Http\RedirectResponse
    {
        $otp = Otp::where('token', $token)->where('created_at', '<=', Carbon::now()->subMinutes(5)->toDateTimeString())->first();

        if (empty($otp)) {
            return redirect()->route('auth.customer.login-register-form', $token)->withErrors(
                [
                    'id' => 'ادرس وارد شده نامعتبر است'
                ]
            );
        }
        $user = $otp->user()->first();
        //create otp code
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);

        Otp::create([
            'token' => $token,
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'login_id' => $otp->login_id,
            'type' => $otp->type
        ]);

        if ($otp->type === 0) {
            //sms
            $this->smsService->setFrom(Config::get('sms.otp_from'));
            $this->smsService->setTo(['0' . $user->mobile]);
            $this->smsService->setText("مجموعه آمازون \n  کد تایید : $otpCode");
            $this->smsService->setIsFlash(true);
        } else {
            // email
            $details = [
                'title' => 'ایمیل فعال سازی',
                'body' => "کد فعال سازی شما : $otpCode"
            ];
            $this->emailService->setDetails($details);
            $this->emailService->setFrom('noreply@example.com', 'example');
            $this->emailService->setSubject('کد احراز هویت');
            $this->emailService->setTo($otp->login_id);
        }
        $messagesService = new MessageService($otp->type === 0 ? $this->smsService : $this->emailService);
        $messagesService->send();

        return redirect()->route('auth.customer.login-confirm-form', $token);
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        return redirect()->route('customer.home');
    }

    private function filterUserPhoneNumber(string $phone): string
    {
        $phone = ltrim($phone, '0');
        $phone = substr($phone, 0, 2) === '98' ? substr($phone, 2) : $phone;
        return str_replace('+98', '', $phone);
    }
}
