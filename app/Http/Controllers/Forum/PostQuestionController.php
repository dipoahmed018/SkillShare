<?php

namespace App\Http\Controllers\Forum;

use App\Models\Forum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostQuestionController extends Controller
{
    public function questionCreate(Request $request, Forum $forum)
    {
        $request->validate(['content' => 'string|required|max:2000|min:10']);
        if ($request->images) {
            return json_decode($request->images,true);
        }
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
}
