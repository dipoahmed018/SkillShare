<?php

namespace App\Http\Controllers\Forum;

use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\File;
use App\Models\Vote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostQuestionController extends Controller
{
    public function postCreate(Request $request, Forum $forum, $type)
    {
        $request->validate(['title' => 'required|string|max:250|min:5']);
        if ($request->user()->cannot('access', $forum) && $request->user()->cannot('update', $forum)) {
            return abort(401, 'you are Unautorized');
        }
        // return $type;
        if ($type !== "post" && $type !== "question") {
            return abort(422, 'post type not supported must be post or question');
        }
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content ? $request->content : ($request->images ?? ''),
            'owner' => $request->user()->id,
            'vote' => 0,
            'postable_id' => $forum->id,
            'post_type' => $type,
            'answer' => 0,
        ]);
        if ($request->images) {
            $images = (json_decode($request->images, true));
            if (count($images) > 3) {
                return abort(422, 'You can not upload more than 4 image');
            }
            Storage::makeDirectory('/private/post/' . $post->id);
            foreach ($images as $key => $url) {
                $name = preg_replace('#.*image/#', '', $url, 1);
                Storage::move('temp/' . $name, '/private/post/' . $post->id . '/' . $name);
            };
        }
        return redirect('/show/forum/' . $forum->id);
    }
    public function saveImage(Request $request, Forum $forum)
    {
        if ($request->user()->cannot('access', $forum) && $request->user()->cannot('update', $forum)) {
            return abort(401, 'you are Unautorized');
        }
        if ($request->file('upload')->getSize() / 1000 > 2000) {
            return response()->json(['error' => ['message' => 'The image uploaded was too big. Image Must be under 2000kb']], 422);
        }
        if (!preg_match('#image/(png|jpg|jpeg)$#', $request->file('upload')->getMimeType())) {
            return response()->json(['error' => ['message' => 'The provided file must be an image of jpg, jpeg or png type']], 422);
        }
        $extension = $request->file('upload')->getClientOriginalExtension();
        $random_name = uniqid() . '.' . $extension;
        $request->file('upload')->storeAs('temp/', $random_name);
        return response()->json(['url' => 'https://skillshare.com/' . $forum->id . '/image/' . $random_name]);
    }
    public function getImage(Request $request, Forum $forum, $name)
    {
        if ($request->user()->cannot('access', $forum) && $request->user()->cannot('update', $forum)) {
            return abort(401, 'you are Unautorized');
        }
        if (File::exists(storage_path('app/private/post/' . $name))) {
            return response()->file(storage_path('app/private/post/' . $name), ['content-type' => 'image/*']);
        } else if (File::exists(storage_path('app/temp/' . $name))) {
            return response()->file(storage_path('app/temp/' . $name), ['content-type' => 'image/*']);
        } else {
            return response()->file(storage_path('app/private/post/default.JPG'), ['content-type' => 'image/*']);
        }
    }

    public function getQuestion(Request $request, Post $question)
    {
        if (!$request->user()->canany(['access', 'update'], $question->forum)) {
            return abort(401, 'You are unauthorized');
        }
        if ($question->post_type == 'post') {
            return abort(422, 'question not available');
        }
        $question->owner_details;
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
        $request->validate(['method' => 'required|in:increment,decrement']);
        if ($post->post_type == 'post') {
            if ($vote = $post->voted($request->user()->id)) {
                return $request->method == 'decrement' ? $vote->delete() : $vote;
            } else {
                return $request->method == 'decrement' ? abort(404, 'vote not found') : $post->allvote()->save(new Vote([
                    'vote_type' => 'increment',
                    'voter_id' => $request->user()->id
                ]));
            };
        }
        if ($post->post_type == 'question') {
            if ($vote = $post->voted($request->user()->id)) {
                return $request->method == 'decrement' ? $vote->save(['vote_type' => 'decrement']) : $vote->save(['vote_type' => 'increment']);
            } else {
                if ($request->method == 'decrement') {
                    $post->allvote()->save(new Vote([
                        'vote_type' => 'decrement',
                        'voter_id' => $request->user()->id
                    ]));
                } else {
                    $post->allvote()->save(new Vote([
                        'vote_type' => 'increment',
                        'voter_id' => $request->user()->id
                    ]));
                }
            };
        }
        return response('something went wrong', 500);
    }

    public function deletePost(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return abort(401, 'unauthorized');
        }
        return $post->delete();
    }
}
