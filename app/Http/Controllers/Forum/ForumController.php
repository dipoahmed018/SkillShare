<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumUpdate;
use App\Models\Catagory;
use App\Models\Forum;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $user = $request->user();
        if (!$user?->canany(['access', 'update'], $forum)) {
            return abort(403, 'You are not authorized to access this forum');
        };

        //datas
        $forum->students = $forum->students()
            ->with('profilePicture')
            ->paginate(4, ['*'], 'students');
        
        $forum->questions = $forum->questions()
            ->with('allVotes', 'ownerDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(1, ['*'], 'questions');

        //permissions
        $forum->editable = $user?->id == $forum->owner;

        if ($request->header('accept') == 'application/json') {
            return response()->json($forum, 200);
        };

        return view('pages.forum.Show', ['forum' => $forum]);
    }
    public function updateForumDetails(ForumUpdate $request, Forum $forum)
    {
        $forum->name = $request->name ?? $forum->name;
        $forum->description = $request->description ?? $forum->description;
        $forum->save();

        
        if ($request->hasFile('cover')) {
            $name = uniqid() .  $request->cover->extension();
            Log::channel('event')->info('forum',[$name]);
            $request->file('cover')?->storeAs('forum/cover', $name, 'public');
            return $forum;
        }
    }
}
