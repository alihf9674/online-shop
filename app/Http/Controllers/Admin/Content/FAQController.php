<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\FaqRequest;
use App\Models\Content\Faq;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $faqs = Faq::orderBy('created_at')->simplePaginate(15);
        return view('admin.content.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.content.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FAQRequest $request)
    {
        $inputs = $request->all();
        Faq::create($inputs);
        return redirect()->route('admin.content.faq.index')->with('swal-success','پرسش جدید با موفقیت ثبت شد');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Faq $faq
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Faq $faq)
    {
        return view('admin.content.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FAQRequest $request, FAQ $faq)
    {
        $inputs = $request->all();
        $faq->update($inputs);
        return redirect()->route('admin.content.faq.index')->with('swal-success','پرسش جدید با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.content.faq.index')->with('swal-success', 'پرسش شما با موفقیت حذف شد');
    }

    /**
     * change FAQ status.
     * @param Faq $faq
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Faq $faq)
    {
        $faq->status = $faq->status === 0 ? 1 : 0;
        $result = $faq->save();
        if ($result) {
            if ($faq->status === 0) return response()->json(['status' => true, 'checked' => false]);
            else return response()->json(['status' => true, 'checked' => true]);
        }
        return response()->json(['status' => false]);
    }
}
