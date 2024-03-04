<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\SettingRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Setting\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $setting = Setting::first();

        if (is_null($setting)) {
            $defaultSetting = new SettingSeeder();
            $defaultSetting->run();
            $setting = Setting::first();
        }

        return view('admin.setting.index', compact('setting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Setting $setting
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Setting $setting)
    {
        return view('admin.setting.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SettingRequest $request ,
     * @param Setting $setting
     * @param ImageService $imageService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingRequest $request, Setting $setting, ImageService $imageService)
    {
        $inputs = $request->all();

        if ($request->hasFile('logo')) {
            if (!empty($setting->logo)) {
                $imageService->deleteImage($setting->logo);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'setting');
            $imageService->setImageName('logo');
            $result = $imageService->save($request->file('logo'));
            if ($result === false) {
                return redirect()->route('admin.content.category.index')->with('swal-error', 'آپلود تصویر با خطا مواجه شد');
            }
            $inputs['logo'] = $result;
        }
        if ($request->hasFile('icon')) {
            if (!empty($setting->icon)) {
                $imageService->deleteImage($setting->icon);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'setting');
            $imageService->setImageName('icon');
            $result = $imageService->save($request->file('icon'));
            if ($result === false) {
                return redirect()->route('admin.content.category.index')->with('swal-error', 'آپلود تصویر با خطا مواجه شد');
            }
            $inputs['icon'] = $result;
        }
        $setting->update($inputs);
        return redirect()->route('admin.setting.index')->with('swal-success', 'تنظیمات سایت  شما با موفقیت ویرایش شد');
    }
}
