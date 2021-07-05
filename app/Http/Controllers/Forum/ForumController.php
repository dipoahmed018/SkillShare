<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumUpdate;
use App\Models\Forum;
use App\Models\Review;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class ForumController extends Controller
{

    public function index()
    {
        $review = Review::factory()->reply()->make();
        return response($review, 200);
    }
    public function getForumDetails(Request $request, Forum $forum)
    {
        if (!$request->user()->canany(['access', 'update'], $forum)) {
            return abort(403, 'You are not authorized to access this forum');
        }
        if (strstr($request->header('accept'), 'application/json')) {
            return response($forum, 200);
        };
        return view('pages.forum.Show', ['forum' => $forum]);
    }
    public function updateForumDetails(ForumUpdate $request, Forum $forum)
    {
        if ($request->name) {
            $forum->name = $request->name;
        }
        if ($request->description) {
            $forum->description = $request->description;
        } else {
            $forum->description = null;
        }
        $forum->save();
        return $forum;
    }
}
