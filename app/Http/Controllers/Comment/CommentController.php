<?php

namespace App\Http\Controllers\Comment;

use App\Models\Post;
use App\Models\Vote;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Comment\CreateCommentRequest;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function commentCreate(CreateCommentRequest $request)
    {
        $type = $request->type;
        $content = $request->content;
        $commentable = $type == 'parent' ? Post::findOrFail($request->commentable) : Comment::findOrFail($request->commentable);
        if ($request->user()->cannot('access', $commentable)) {
            return abort(401, 'unauthorized');
        }
        // if comment type is reply makes sure that parent is not a reply so that there can no mulitilevel nested reply
        if ($type == 'reply') {
            $commentable = $commentable->commentable_type == 'reply' ? $commentable->parent : $commentable;
        }

        $comment = Comment::create([
            'content' => $content,
            'owner' => $request->user()->id,
            'vote' => 0,
            'commentable_id' => $commentable->id,
            'commentable_type' => $type,
        ]);

        //handel references 
        if ($request->filled('references')) {

            $references = [];
            $request->references->each(fn ($user) => $reference[] = ['user_id' => $user]);
            Log::channel('event')->info('references', [$references]);
            $comment->references()->saveMany($references);
        }

        return response()->json($comment, 200);
    }

    public function updateComment(Request $request, Comment $comment)
    {
        if ($request->user()->cannot('update', $comment)) {
            return response('you are not the owner of this comment', 403);
        }
        $request->validate([
            'content' => 'required|string|min:5',
        ]);
        if ($comment->comment_type == 'reply') {
            $commentable = $comment->parent;
            $comment->content = "<a href=" . env('APP_URL') . "/user/$commentable->owner/profile>@$commentable->ownerDetails->name</a> <div class='reply-content'>$request->content</div>";
            $comment->save();
            return response()->json($comment, 200);
        }
        $comment->content = $request->content;
        $comment->save();
        return response()->json($comment, 200);
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
        $user = $request->user();
        if ($user->cannot('update', $comment)) {
            return abort(401, 'unautorized');
        }
        $request->validate(['method' => 'required|in:increment,decrement']);
        $voted = $comment->votes()->where('voter_id', $user->id)->first();
        if ($voted) {
            $voted->delete();
            return response()->json(['vote' => null]);
        } else {
            $vote = $comment->votes()->create([
                'vote_type' => 'increment',
                'voter_id' => $user->id
            ]);
            return response()->json($vote);
        };
    }
}
