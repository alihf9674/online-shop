<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\CategoryAttributeRequest;
use App\Models\Market\CategoryAttribute;
use App\Models\Market\ProductCategory;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $category_attributes = CategoryAttribute::all();
        return view('admin.market.property.index', compact('category_attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.market.property.create', compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryAttributeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryAttributeRequest $request)
    {
        $inputs = $request->all();
        CategoryAttribute::create($inputs);
        return redirect()->route('admin.market.property.index')->with('swal-success', 'فرم جدید شما با موفقیت ثبت شد');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CategoryAttribute $categoryAttribute
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(CategoryAttribute $categoryAttribute)
    {
        $productCategories = ProductCategory::all();
        return view('admin.market.property.edit', compact('categoryAttribute', 'productCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryAttributeRequest $request
     * @param CategoryAttribute $categoryAttribute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryAttributeRequest $request, CategoryAttribute $categoryAttribute)
    {
        $inputs = $request->all();
        $categoryAttribute->update($inputs);
        return redirect()->route('admin.market.property.index')->with('swal-success', 'فرم شما با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CategoryAttribute $categoryAttribute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CategoryAttribute $categoryAttribute)
    {
        $categoryAttribute->delete();
        return redirect()->route('admin.market.property.index')->with('swal-success', 'فرم شما با موفقیت حذف شد');
    }
}
