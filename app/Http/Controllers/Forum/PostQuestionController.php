<?php

namespace App\Http\Controllers\Forum;

use App\Models\Post;
use App\Models\Vote;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FileLink;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PostQuestionController extends Controller
{
    public function postCreate(Request $request, $postable, $type)
    {
        $request->validate([
            'title' => 'string|max:250|min:5',
            'content' => 'string|max:2500|min:5'
        ]);
        $postable = $type == 'answer' ? Post::findOrFail($postable) : Forum::findOrFail($postable);
        if ($request->user()->cannot('access', $postable)) {
            return abort(401, 'you are Unautorized');
        }
        $types = ['question', 'answer', 'post'];
        if (!in_array($type, $types)) {
            return  abort(404);
        }
        if (!$request->title && !$request->content) {
            return abort(422, ['error' => 'You must provide title or content']);
        }
        $post = Post::create([
            'title' => $request->title ? $request->title : null,
            'content' => $request->content ?: ($request->images && $type == 'post' ? $request->images : null),
            'owner' => $request->user()->id,
            'postable_id' => $postable->id,
            'post_type' => $type,
            'answer' => 0,
        ]);
        if ($images = json_decode($request->images, true)) {
            if (count($images) > 3) {
                return abort(422, 'You can not upload more than 4 image');
            }
            foreach ($images as $key => $url) {
                //move the image to new file and convert it
                $name = preg_replace('#.*name=#', '', $url, 1);
                $path = storage_path("app/private/post/$name");
                $image = Image::make(storage_path("app/temp/$name"));
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 80);
                Storage::delete('temp/' . $name);

                // make a FileLink
                FileLink::create([
                    'file_name' => $name,
                    'file_type' => 'post',
                    'file_link' => "private/post/$name",
                    'fileable_id' => $post->id,
                    'fileable_type' => 'post',
                    'secutity' => 'private',
                ]);
            };
            $post->save();
        }
        return redirect()->back();
    }
    public function postUpdate(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return abort(401, 'you are Unautorized');
        }
        $request->validate(['title' => 'string|max:250|min:5']);
        $post->title = $request->title ?? $post->title;
        $post->content =  $request->content ?: ($request->images && $post->post_type == 'post' ? $request->images : $post->content);
        if ($request->images) {
            $images = json_decode($request->images, true);
            if (count($images) > 3) {
                return abort(422, 'You can not upload more than 4 image');
            }
            $new_image_names = collect($images)->flatten()->map(fn ($el) => preg_replace('/.*name=/', '', $el));
            $old_image_names = $post->images->pluck('file_link')->map(fn ($el) => preg_replace('/.*post\//', '', $el));
            update_files($new_image_names, $old_image_names, 'private/post/');
        }
        $post->save();
        return redirect()->back();
    }

    public function getQuestion(Request $request, Post $question)
    {
        if ($request->user()->cannot('access', $question->parent)) {
            return abort(401, 'You are unauthorized');
        }
        if ($question->post_type == 'post') {
            return abort(422, 'question not available');
        }
        $question->ownerDetails;
        $question->voted = $question->voted($request->user()->id);
        return view('pages/forum/Question', ['question' => $question, 'answers' => $question->answers]);
    }
    public function getPost(Request $request, Post $post)
    {
    }
    public function updateVote(Request $request, Post $post)
    {
        if ($request->user()->cannot('access', $post)) {
            return abort(401, 'unautorized');
        }

        $request->validate(['type' => 'required|in:increment,decrement']);
        if ($post->post_type == 'post') {
            if ($vote = $post->voted($request->user()->id)) {
                $request->type == 'decrement' ? $vote->delete() : $vote;
                return response()->json(['votes' => $post->voteCount(), 'type' => 'remove']);
            } else {
                $request->type == 'decrement' ? abort(422, 'vote not found') : $post->allVotes()->save(new Vote([
                    'vote_type' => 'increment',
                    'voter_id' => $request->user()->id
                ]));
            };
        }
        if ($post->post_type !== 'post') {
            if ($vote = $post->voted($request->user()->id)) {
                //need workd
                if ($vote->vote_type == $request->type) {
                    $vote->delete();
                    return response()->json(['votes' => $post->voteCount(), 'type' => 'remove']);
                }
                $request->type == 'decrement' ? $vote->vote_type = 'decrement' : $vote->vote_type = 'increment';
                $vote->save();
            } else {
                // return 'not found';
                if ($request->type == 'decrement') {
                    $post->allVotes()->save(new Vote([
                        'vote_type' => 'decrement',
                        'voter_id' => $request->user()->id
                    ]));
                } else {
                    $post->allVotes()->save(new Vote([
                        'vote_type' => 'increment',
                        'voter_id' => $request->user()->id
                    ]));
                }
            };
        }
        return response()->json(['votes' => $post->voteCount(), 'type' => $request->type]);
    }

    public function acceptAnswer(Request $request, Post $post)
    {
        return $request->ip();
        $user = $request->user();
        if ($post->post_type !== 'answer') {
            return abort(404, 'answer not found');
        }
        $postable = $post->parent;
        if ($user->cannot('update', $postable)) {
            return abort(401, 'You are not allowed to update this post');
        }

        //toggle post answer 
        if ($postable->answer == $post->id) {
            $postable->answer = null;
            $post->save();
        
        //change post answer
        } else {
            $postable->answer = $post->id;
            $postable->save();
        }
        return true;
    }

    public function deletePost(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return abort(401, 'unauthorized');
        }
        $post->delete();
        if ($request->header('accept')== 'application/json') {
            return response()->json($post, 200);
        }
        return redirect()->back();
    }
}
