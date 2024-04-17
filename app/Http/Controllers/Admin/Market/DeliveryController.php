<?php

namespace App\Http\Controllers\Admin\Market;

use Illuminate\Http\Request;
use App\Models\Market\Delivery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\DeliveryRequest;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $delivery_methods = Delivery::all();
        return view('admin.market.delivery.index', compact('delivery_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.market.delivery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DeliveryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DeliveryRequest $request)
    {
        $inputs = $request->all();
        Delivery::create($inputs);
        return redirect()->route('admin.market.delivery.index')->with('swal-success', 'روش ارسال جدید شما با موفقیت ثبت شد');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Delivery $delivery)
    {
        return view('admin.market.delivery.edit', compact('delivery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DeliveryRequest $request
     * @param Delivery $delivery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        $inputs = $request->all();
        $delivery->update($inputs);
        return redirect()->route('admin.market.delivery.index')->with('swal-success', 'روش ارسال شما با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Delivery $delivery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('admin.market.delivery.index')->with('swal-success', 'روش ارسال شما با موفقیت حذف شد');
    }


    public function status(Delivery $delivery)
    {
        $delivery->status = $delivery->status == 0 ? 1 : 0;

        $result = $delivery->save();
        if ($result) {
            if ($delivery->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            }
            return response()->json(['status' => true, 'checked' => true]);
        }
        return response()->json(['status' => false]);
    }
}
