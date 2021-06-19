<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumUpdate;
use App\Models\Forum;
use App\Models\Message;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller
{
    public function index()
    {
        $review = Review::factory()->reply()->make();
        return response($review, 200);
    }
    public function getForumDetails(Request $request, Forum $forum)
    {
        if(strstr($request->header('accept'), 'application/json')){
            return response($forum, 200);
        };
        return view('pages.forum.Show',['forum'=> $forum]);
    }
    public function updateForum(ForumUpdate $request)
    {
    }
}
