<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
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
    public function getForumDetials(Request $request, Forum $forum)
    {
        $request->user()->cannot('access', $forum);
    }
    public function updateForum()
    {
        
    }
}
