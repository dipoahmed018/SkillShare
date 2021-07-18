<?php

namespace App\Http\Controllers\Forum;

use App\Models\Post;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Models\User;

class CommentAnswerController extends Controller
{
    public function parentCreate(CreateCommentRequest $request, Post $post)
    {
        if ($request->user()->cannot('access', $post)) {
            return abort(401, 'unauthorized');
        }
        $comment = Comment::create([
            'content' => $request->content,
            'owner' => $request->user()->id,
            'vote' => 0,
            'commentable_type' => $request->commentable_type == 'parent' ?? 'answer',
        ]);
        if ($comment->commentable_id !== 'reply' && $request->images) {
            $images = json_decode($request->images, true);
            if (count($images) > 3) {
                return abort(422, 'You can not upload more than 4 image');
            }
            Storage::makeDirectory('/private/comment/' . $comment->id);
            foreach ($images as $key => $url) {
                $name = preg_replace('#.*image/#', '', $url, 1);
                $image = Image::make('/private/comment/temp/' . $name);
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save('/private/comment/' . $comment->id . '/' . $name, 80);
                Storage::delete('/private/comment/temp/' . $name);
            };
        }
    }
    public function replyCreate(CreateCommentRequest $request, Comment $commentable, User $refer)
    {
        $post = $commentable->getPost();
        if ($request->user()->cannot('access', $post)) {
            return abort(401, 'unauthorized');
        }
        $request->content = '<a href="https://skillshare.com/user/' . $refer->id . '/profile">@' . $refer->name . '</a> <div class="reply-content"> ' . $request->content . '</div>';
        $comment = Comment::create([
            'content' => $request->content,
            'owner' => $request->user()->id,
            'vote' => 0,
            'commentable_type' => 'reply',
        ]);
        //send notification to refference

        if ($commentable->commentable_type == 'parent') {
            $images = json_decode($request->images, true);
            if (count($images) > 3) {
                return abort(422, 'You can not upload more than 4 image');
            }
            Storage::makeDirectory('/private/comment/' . $comment->id);
            foreach ($images as $key => $url) {
                $name = preg_replace('#.*image/#', '', $url, 1);
                $image = Image::make('/private/comment/temp/' . $name);
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save('/private/comment/' . $comment->id . '/' . $name, 80);
                Storage::delete('/private/comment/temp/' . $name);
            };
        }
    }

    public function updateVote(Request $request, Comment $comment)
    {
        if ($request->user()->cannot('update', $comment)) {
            return abort(401, 'unautorized');
        }
        $request->validate(['method' => 'required|in:increment,decrement']);
        if ($comment->commentable_type == 'parent' || 'reply') {
            if ($vote = $comment->voted($request->user()->id)) {
                return $request->method == 'decrement' ? $vote->delete() : $vote;
            } else {
                return $request->method == 'decrement' ? abort(404, 'vote not found') : $comment->allvote()->save(new Vote([
                    'vote_type' => 'increment',
                    'voter_id' => $request->user()->id
                ]));
            };
        }
        if ($comment->commentable_type == 'answer') {
            if ($vote = $comment->voted($request->user()->id)) {
                return $request->method == 'decrement' ? $vote->save(['vote_type' => 'decrement']) : $vote->save(['vote_type' => 'increment']);
            } else {
                if ($request->method == 'decrement') {
                    $comment->allvote()->save(new Vote([
                        'vote_type' => 'decrement',
                        'voter_id' => $request->user()->id
                    ]));
                } else {
                    $comment->allvote()->save(new Vote([
                        'vote_type' => 'increment',
                        'voter_id' => $request->user()->id
                    ]));
                }
            };
        }
        return response('something went wrong', 500);
    }
    public function updateComment(CreateCommentRequest $request, Comment $comment)
    {
        $post = $comment->getPost();
        $comment->content = $request->content;
        $comment->save();
        if ($post->post_type == 'question' && $comment->commentable_type == 'reply') {
            return $comment;
        }
        if ($request->images) {
            $images = json_decode($request->images, true);
            if (count($images) > 3) {
                return abort(422, 'You can not upload more than 4 image');
            }
            Storage::makeDirectory('/private/comment/' . $comment->id);
            foreach ($images as $key => $url) {
                $name = preg_replace('#.*image/#', '', $url, 1);
                $image = Image::make('/private/comment/temp/' . $name);
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save('/private/comment/' . $comment->id . '/' . $name, 80);
                Storage::delete('/private/comment/temp/' . $name);
            };
        }
    }
    public function deleteComment(Request $request, Comment $comment)
    {
        if ($request->user()->cannot('update', $comment)) {
            abort(401, 'unautorized');
        }
        return $comment->delete();
    }
}
