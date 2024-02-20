<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\CommentRequest;
use App\Models\Content\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $unseenComments = Comment::where('seen', 0)->get();
        foreach ($unseenComments as $unseenComment) {
            $unseenComment->seen = 1;
            $unseenComment->save();
        }
        $comments = Comment::orderBy('created_at', 'desc')->simplePaginate(15);
        return view('admin.content.comment.index', compact('comments'));
    }


    /**
     * Display the specified resource.
     *
     * @param Comment $comment
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Comment $comment)
    {
        return view('admin.content.comment.show', compact('comment'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * change comment status.
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Comment $comment)
    {
        $comment->status = $comment->status === 0 ? 1 : 0;
        $result = $comment->save();
        if ($result) {
            if ($comment->status === 0) return response()->json(['status' => true, 'checked' => false]);
            else return response()->json(['status' => true, 'checked' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function approved(Comment $comment)
    {
        $comment->approved = $comment->approved == 0 ? 1 : 0;
        $result = $comment->save();
        if ($result) {
            return redirect()->route('admin.content.comment.index')->with('swal-success', 'وضعیت نظر با موفقیت تغییر کرد');
        }
        return redirect()->route('admin.content.comment.index')->with('swal-error', 'تغییر وضعیت با خطا مواجه شد');
    }

    public function answer(CommentRequest $request, Comment $comment)
    {
        if ($comment->parent == null) {
            $inputs = $request->all();
            $inputs['author_id'] = 1;
            $inputs['parent_id'] = $comment->id;
            $inputs['commentable_id'] = $comment->commentable_id;
            $inputs['commentable_type'] = $comment->commentable_type;
            $inputs['approved'] = 1;
            $inputs['status'] = 1;
            Comment::create($inputs);
            return redirect()->route('admin.content.comment.index')->with('swal-success', '  پاسخ شما با موفقیت ثبت شد');
        }
        return redirect()->route('admin.content.comment.index')->with('swal-error', 'خطا');

    }
}
