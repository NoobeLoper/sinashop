<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:edit-comment')->only(['edit', 'update']);
        $this->middleware('can:delete-comment')->only(['destroy']);
        $this->middleware('can:approving-comment')->only(['approving']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::query();
        if ($keyword = request('search')) {
            $comments->where('comment', 'LIKE', "%{$keyword}%")->orwhereHas('user', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            })
            ->orWhere('id', $keyword);
        }

        if (request('approved')) {
            // $this->authorize('show-approved-comments');
            $comments->where('approved', 0);
        }

        $comments = $comments->latest()->paginate(8);

        return view('admin.comments.all', compact('comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'comment' => 'required'
        ]);

        $comment->update($data);
        alert()->success('Comment updated successfully');
        return redirect(route('admin.comments.index'))->with('success', 'ویرایش انجام شد.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        alert()->success('دیدگاه مورد نظر حذف شد', 'انجام شد');
        return back()->with('success', 'دیدگاه پاک شد');
    }

    public function approving(Comment $comment)
    {
        if($comment->approved == 0) {
            $comment->approved = 1;
            alert()->success("نظر تایید شد");
        } else {
            $comment->approved = 0;
            alert()->info("نظر از حالت تایید شده در آمد");
        }

        $comment->update();

        return redirect(route('admin.comments.index'))->with('success', 'Done');
    }
}
