<?php

namespace App\Http\Controllers\Admin\Notify;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notify\EmailRequest;
use App\Models\Notify\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $emails = Email::orderBy('created_at', 'desc')->simplePaginate();
        return view('admin.notify.email.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notify.email.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EmailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EmailRequest $request)
    {
        $inputs = $request->all();
        $realTimeStamp = substr($request->published_at, 0, 10);
        $inputs['published_at'] = date('Y-m-d H:i:s', (int)$realTimeStamp);
        Email::create($inputs);
        return redirect()->route('admin.notify.email.index')->with('swal-success', 'ایمیل جدید با موفقیت ثبت شد');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Email $email
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Email $email)
    {
        return view('admin.notify.email.edit',compact('email'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EmailRequest $request
     * @param  Email $email
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EmailRequest $request, Email $email)
    {
        $inputs = $request->all();
        $realTimeStamp = substr($request->published_at, 0, 10);
        $inputs['published_at'] = date('Y-m-d H:i:s', (int)$realTimeStamp);
        $email->update($inputs);
        return redirect()->route('admin.notify.email.index')->with('swal-success', 'ایمیل با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Email $email
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Email $email)
    {
        $email->delete();
        return redirect()->route('admin.notify.email.index')->with('swal-success', 'ایمیل با موفقیت حذف شد');
    }

    /**
     * change email status.
     * @param Email $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Email $email): \Illuminate\Http\JsonResponse
    {
        $email->status = $email->status === 0 ? 1 : 0;
        $result = $email->save();
        if ($result) {
            if ($email->status === 0) return response()->json(['status' => true, 'checked' => false]);
            else return response()->json(['status' => true, 'checked' => true]);
        }
        return response()->json(['status' => false]);
    }
}
