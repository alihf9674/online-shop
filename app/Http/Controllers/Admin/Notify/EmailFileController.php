<?php

namespace App\Http\Controllers\Admin\Notify;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notify\EmailFileRequest;
use App\Http\Services\File\FileService;
use App\Models\Notify\Email;
use App\Models\Notify\EmailFile;
use Illuminate\Http\Request;

class EmailFileController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Email $email
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Email $email)
    {
        return view('admin.notify.email-file.index', $email);
    }

    /**
     * Show the form for creating a new resource.
     * @param Email $email
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Email $email)
    {
        return view('admin.notify.email-file.create', compact('email'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmailFileRequest $request
     * @param Email $email
     * @param FileService $fileServiceEmail $email
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EmailFileRequest $request, Email $email, FileService $fileService)
    {
        $inputs = $request->all();

        if ($request->hasFile('file')) {
            $fileService->setExclusiveDirectory('files' . DIRECTORY_SEPARATOR . 'email-files');
            $fileService->setFileSize($request->file('file'));
            $fileSize = $fileService->getFileSize();
            $result = $fileService->moveToPublic($request->file('file'));

            if ($result === false) {
                return redirect()->route('admin.notify.email-file.index', $email->id)
                    ->with('swal-error', 'آپلود فایل با خطا مواجه شد');
            }
            $fileFormat = $fileService->getFileFormat();

            $inputs['file_size'] = $fileSize;
            $inputs['file_type'] = $fileFormat;
            $inputs['file_path'] = $result;
        }
        $inputs['public_mail_id'] = $email->id;

        EmailFile::create($inputs);
        return redirect()->route('admin.notify.email-file.index', $email->id)->with('swal-success', 'فایل جدید شما با موفقیت ثبت شد');
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
     * @param EmailFile $file
     * @param Email $email
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(EmailFile $file, Email $email)
    {
        return view('admin.notify.email-file.edit', compact('file', 'email'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EmailFileRequest $request
     * @param EmailFile $file
     * @param FileService $fileService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EmailFileRequest $request, EmailFile $file, FileService $fileService)
    {
        $inputs = $request->all();
        if ($request->hasFile('file')) {
            if (!empty($file->file_path)) {
                $fileService->deleteFile($file->file_path);
            }

            $fileService->setExclusiveDirectory('files' . DIRECTORY_SEPARATOR . 'email-files');
            $fileService->setFileSize($request->file('file'));
            $fileSize = $fileService->getFileSize();
            $result = $fileService->moveToPublic($request->file('file'));

            if ($result === false) {
                return redirect()->route('admin.notify.email-file.index', $file->email->id)
                    ->with('swal-error', 'آپلود فایل با خطا مواجه شد');
            }
            $fileFormat = $fileService->getFileFormat();

            $inputs['file_size'] = $fileSize;
            $inputs['file_type'] = $fileFormat;
            $inputs['file_path'] = $result;
        }
        $file->update($inputs);
        return redirect()->route('admin.notify.email-file.index', $file->email->id)->with('swal-success', 'فایل شما با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(EmailFile $file)
    {
        $file->delete();
        return redirect()->route('admin.notify.email-file.index', $file->email->id)->with('swal-success', 'فایل شما با موفقیت حذف شد');
    }

    /**
     * change file status.
     * @param EmailFile $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(EmailFile $file): \Illuminate\Http\JsonResponse
    {
        $file->status = $file->status === 0 ? 1 : 0;
        $result = $file->save();
        if ($result) {
            if ($file->status === 0) return response()->json(['status' => true, 'checked' => false]);
            else return response()->json(['status' => true, 'checked' => true]);
        }
        return response()->json(['status' => false]);
    }
}
