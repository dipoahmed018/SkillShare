<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Http\Request;

class CommentAnswerController extends Controller
{
    public function updateVote(Request $request, Comment $comment)
    {
        if ($request->user()->cannot('access', $comment)) {
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
}
