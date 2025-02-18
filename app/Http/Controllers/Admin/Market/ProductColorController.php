<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Models\Market\Product;
use App\Models\Market\ProductColor;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return view('admin.market.product.color.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Product $product
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Product $product)
    {
        return view('admin.market.product.color.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'color_name' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'price_increase' => 'required|numeric',
        ]);
        $inputs = $request->all();
        $inputs['product_id'] = $product->id;
        $color = ProductColor::create($inputs);
        return redirect()->route('admin.market.color.index', $product->id)->with('swal-success', 'رنگ شما با موفقیت ثبت شد');
    }
}
