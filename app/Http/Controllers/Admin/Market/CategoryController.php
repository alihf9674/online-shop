<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductCategoryRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Market\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $productCategories = ProductCategory::orderBy('created_at', 'desc')->simplePaginate(15);
        return view('admin.market.category.index', compact('productCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = ProductCategory::where('parent_id', null)->get();
        return view('admin.market.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductCategoryRequest $request
     * @param ImageService $imageService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductCategoryRequest $request, ImageService $imageService)
    {
        $inputs = $request->all();
        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'product-category');
            $result = $imageService->createIndexAndSave($request->file('image'));
        }
        if ($result === false) {
            return redirect()->route('admin.market.category.index')->with('swal-error', 'آپلود تصویر با خطا مواجه شد');
        }
        $inputs['image'] = $result;
         ProductCategory::create($inputs);
        return redirect()->route('admin.market.category.index')->with('swal-success', 'دسته بندی جدید شما با موفقیت ثبت شد');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductCategory $productCategory
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(ProductCategory $productCategory)
    {
        $parent_categories = ProductCategory::where('parent_id', null)->get()->except($productCategory->id);
        return view('admin.market.category.edit', compact('productCategory', 'parent_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductCategoryRequest $request
     * @param ProductCategory $productCategory
     * @param ImageService $imageService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductCategoryRequest $request, ProductCategory $productCategory, ImageService $imageService)
    {
        $inputs = $request->all();

        if ($request->hasFile('image')) {
            if (!empty($productCategory->image)) {
                $imageService->deleteDirectoryAndFiles($productCategory->image['directory']);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'product-category');
            $result = $imageService->createIndexAndSave($request->file('image'));
            if ($result === false) {
                return redirect()->route('admin.market.category.index')->with('swal-error', 'آپلود تصویر با خطا مواجه شد');
            }
            $inputs['image'] = $result;
        } elseif(isset($inputs['currentImage']) && !empty($productCategory->image)) {
                $image = $productCategory->image;
                $image['currentImage'] = $inputs['currentImage'];
                $inputs['image'] = $image;
        }
        $productCategory->update($inputs);
        return redirect()->route('admin.market.category.index')->with('swal-success', 'دسته بندی شما با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategory $productCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->route('admin.market.category.index')->with('swal-success', 'دسته بندی شما با موفقیت حذف شد');
    }
}
