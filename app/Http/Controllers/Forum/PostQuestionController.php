<?php

namespace App\Http\Controllers\Forum;

use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostQuestionController extends Controller
{
    public function questionCreate(Request $request, Forum $forum)
    {
        $images = null;
        $request->validate(['content' => 'string|required|max:2000|min:10', 'title' => 'required|string|max:250|min:5']);
        if ($request->user()->cannot('access', $forum) && $request->user()->cannot('update', $forum)) {
            return abort(401, 'you are Unautorized');
        }
        if ($request->images) {
            $images = (json_decode($request->images, true));
        }
        if (count($images) > 3) {
            return abort(422, 'You can not upload more than 4 image');
        }
        foreach ($images as $key => $url) {
            $name = preg_replace('#.*image/#', '', $url, 1);
            Storage::move('/private/post/temp/' . $name, '/private/post/' . $name);
        };
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'owner' => $request->user()->id,
            'vote' => 0,
            'postable_id' => $forum->id,
            'post_type' => 'question',
            'answer' => 0,
        ]);
        return $post;
    }
    public function postCreate(Forum $forum)
    {
        return $forum;
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
        $request->file('upload')->storeAs('private/post/temp/', $random_name);
        return response()->json(['url' => 'https://skillshare.com/' . $forum->id . '/image/' . $random_name]);
    }
    public function getImage(Request $request, Forum $forum, $name)
    {
        if ($request->user()->cannot('access', $forum) && $request->user()->cannot('update', $forum)) {
            return abort(401, 'you are Unautorized');
        }
        if (File::exists(storage_path('app/private/post/' . $name))) {
            return response()->file(storage_path('app/private/post/' . $name), ['content-type' => 'image/*']);
        } else if (File::exists(storage_path('app/private/post/temp/' . $name))) {
            return response()->file(storage_path('app/private/post/temp/' . $name), ['content-type' => 'image/*']);
        } else {
            return response()->file(storage_path('app/private/post/default.JPG'), ['content-type' => 'image/*']);
        }
    }

    public function getQuestion(Request $request, Post $question)
    {
        if (!$request->user()->canany(['access','update'], $question->forum)) {
            return abort(401,'You are unauthorized');
        }
        if ($question->post_type == 'post') {
            return abort(422,'question not available');
        }
        return view('pages/forum/Question',['question' => $question, 'answers' => $question->answers]);
    }
    public function getPost(Request $request, Post $post)
    {
        # code...
    }
}
