<?php

namespace App\Http\Controllers\Admin\Notify;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notify\SMSRequest;
use App\Models\Notify\SMS;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $sms = SMS::orderBy('created_at', 'desc')->simplePaginate();
        return view('admin.notify.sms.index', compact('sms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.notify.sms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SMSRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SMSRequest $request)
    {
        $inputs = $request->all();
        $realTimeStamp = substr($request->published_at, 0, 10);
        $inputs['published_at'] = date("Y-m-d H:i:s", (int)$realTimeStamp);
        SMS::create($inputs);
        return redirect()->route('admin.notify.sms.index')->with('swal-success', 'پیامک جدید با موفقیت ثبت شد');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param SMS $sms
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(SMS $sms)
    {
        return view('admin.notify.sms.edit', compact('sms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SMSRequest $request
     * @param SMS $sms
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SMSRequest $request, SMS $sms)
    {
        $inputs = $request->all();
        $realTimeStamp = substr($request->published_at, 0, 10);
        $inputs['published_at'] = date('Y-m-d H:i:s', (int)$realTimeStamp);
        $sms->update($inputs);
        return redirect()->route('admin.notify.sms.index')->with('swal-success', 'پیامک با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SMS $sms
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SMS $sms)
    {
        $sms->delete();
        return redirect()->route('admin.notify.sms.index')->with('swal-success', 'پیامک با موفقیت حذف شد');
    }

    /**
     * change sms status.
     *
     * @param Sms $sms
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Sms $sms)
    {
        $sms->status = $sms->status === 0 ? 1 : 0;
        $result = $sms->save();
        if ($result) {
            if ($sms->status === 0) return response()->json(['status' => true, 'checked' => false]);
            else return response()->json(['status' => true, 'checked' => true]);
        }
        return response()->json(['status' => false]);
    }
}
