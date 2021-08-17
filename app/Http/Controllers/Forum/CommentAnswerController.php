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
use Illuminate\Support\Facades\Log;

class CommentAnswerController extends Controller
{
    public function commentCreate(CreateCommentRequest $request)
    {
        $type = $request->type;
        $content = $request->content;
        $commentable = ($type == 'parent' || $type == 'answer') ? Post::findOrFail($request->commentable) : Comment::findOrFail($request->commentable);
        if ($request->user()->cannot('access', $commentable)) {
            return abort(401, 'unauthorized');
        }
        if ($type == 'reply') {
            $content = '<a href="https://skillshare.com/user/' . $commentable->owner . '/profile">@' . $commentable->owner_details->name . '</a> <div class="reply-content"> ' . $request->content . '</div>';
            $commentable = $commentable->commentable_type == 'reply' ? $commentable->parent : $commentable;
        }
        $comment = Comment::create([
            'content' => $content,
            'owner' => $request->user()->id,
            'vote' => 0,
            'commentable_id' => $commentable->id,
            'commentable_type' => $type,
        ]);
        if ($request->images) {
            if ($type == 'reply') {
                if ($commentable->commentable_type == 'answer') {
                    return $comment;
                }
            }
            $images = json_decode($request->images, true);
            if (count($images) > 3) {
                $comment->delete();
                return abort(422, 'You can not upload more than 4 image');
            }
            Storage::makeDirectory('/private/comment/' . $comment->id);
            foreach ($images as $key => $url) {
                $name = preg_replace('#.*image/#', '', $url, 1);
                // $comment->content = str_replace($name, $name . '/' . $comment->id, $comment->content);
                $image = Image::make(storage_path('app/temp/' . $name));
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path('app/private/comment/' . $comment->id . '/' . $name), 80);
                Storage::delete('temp/' . $name);
            };
            // $comment->save();
        }
        return $comment;
    }

    public function updateComment(Request $request, Comment $comment)
    {
        $post = $comment->getPost();
        $request->validate([
            'content' => 'required|string|min:5',
        ]);
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
            update_files($images, '/private/comment/'. $comment->id);
            // foreach ($images as $key => $url) {
            //     $name = preg_replace('#.*image/#', '', $url, 1);
            //     $comment->content = str_replace($name, $name . '/' . $comment->id, $comment->content);
            //     $names->push($name);
            //     if (!Storage::exists('/private/comment/' . $name)) {
            //         $image = Image::make(storage_path('app/temp/' . $name));
            //         $image->resize(800, null, function ($constraint) {
            //             $constraint->aspectRatio();
            //         })->save(storage_path('app/private/comment/' . $name), 80);
            //         Storage::delete('temp/' . $name);
            //     }
            // };
        }
        return $comment;
    }
    public function deleteComment(Request $request, Comment $comment)
    {
        if ($request->user()->cannot('update', $comment)) {
            abort(401, 'unautorized');
        }
        return $comment->delete();
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

    public function saveCommentImage(Request $request)
    {
        return saveImage($request->file('upload'), 'https://skillshare.com/get/comment/image/');
    }
    public function getCommentImage($name, $comment = null)
    {
        if ($comment) {
            return response()->file(storage_path('app/private/comment/' . $comment . '/' . $name), ['content-type' => 'image/*']);
        } else {
            return response()->file(storage_path('app/temp/' . $name), ['content-type' => 'image/*']);
        }
        return response()->file(public_path('default/Default.jpeg'));
    }
}
