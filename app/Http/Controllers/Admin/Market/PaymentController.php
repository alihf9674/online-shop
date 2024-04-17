<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Models\Market\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('admin.market.payment.index', compact('payments'));
    }

    public function offline()
    {
        $payments = Payment::where('paymentable_type', 'App\Models\Market\OfflinePayment')->get();
        return view('admin.market.payment.index', compact('payments'));
    }

    public function online()
    {
        $payments = Payment::where('paymentable_type', 'App\Models\Market\OnlinePayment')->get();
        return view('admin.market.payment.index', compact('payments'));
    }

    public function cash()
    {
        $payments = Payment::where('paymentable_type', 'App\Models\Market\CashPayment')->get();
        return view('admin.market.payment.index', compact('payments'));
    }

    public function canceled(Payment $payment): \Illuminate\Http\RedirectResponse
    {
        $payment->status = 2;
        $payment->save();
        return redirect()->route('admin.market.payment.index')->with('swal-success', 'تغییر شما با موفقیت انجام شد');
    }

    public function returned(Payment $payment): \Illuminate\Http\RedirectResponse
    {
        $payment->status = 3;
        $payment->save();
        return redirect()->route('admin.market.payment.index')->with('swal-success', 'تغییر شما با موفقیت انجام شد');
    }

    public function show(Payment $payment): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('admin.market.payment.show', compact('payment'));
    }
}
