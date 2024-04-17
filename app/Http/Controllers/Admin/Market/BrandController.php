<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\BrandRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Market\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $brands = Brand::orderBy('created_at', 'desc')->simplePaginate(15);
        return view('admin.market.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.market.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BrandRequest $request
     * @param ImageService $imageService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BrandRequest $request, ImageService $imageService)
    {
        $inputs = $request->all();
        if ($request->hasFile('logo')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'brand');
            $result = $imageService->createIndexAndSave($request->file('logo'));
        }
        if ($result === false) {
            return redirect()->route('admin.market.brand.index')->with('swal-error', 'آپلود تصویر با خطا مواجه شد');
        }
        $inputs['logo'] = $result;
        Brand::create($inputs);
        return redirect()->route('admin.market.brand.index')->with('swal-success', 'برند جدید شما با موفقیت ثبت شد');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Brand $brand
     * @return  \Illuminate\Contracts\View\View
     */
    public function edit(Brand $brand)
    {
        return view('admin.market.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BrandRequest $request
     * @param Brand $brand
     * @param ImageService $imageService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BrandRequest $request, Brand $brand, ImageService $imageService)
    {
        $inputs = $request->all();

        if ($request->hasFile('logo')) {
            if (!empty($brand->logo)) {
                $imageService->deleteDirectoryAndFiles($brand->logo['directory']);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'brand');
            $result = $imageService->createIndexAndSave($request->file('logo'));
            if ($result === false) {
                return redirect()->route('admin.market.brand.index')->with('swal-error', 'آپلود تصویر با خطا مواجه شد');
            }
            $inputs['logo'] = $result;
        } elseif (isset($inputs['currentImage']) && !empty($brand->logo)) {
            $image = $brand->logo;
            $image['currentImage'] = $inputs['currentImage'];
            $inputs['logo'] = $image;
        }
        $brand->update($inputs);
        return redirect()->route('admin.market.brand.index')->with('swal-success', 'برند شما با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.market.brand.index')->with('swal-success', 'برند شما با موفقیت حذف شد');
    }
}
