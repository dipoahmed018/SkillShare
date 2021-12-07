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
use App\Models\CommentReferences;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'offset' => 'integer',
            'limit' => 'integer',
        ]);
        
        if ($request->post) {
            $post = Post::findOrFail($request->post);
            $data = [];

            $comments = $post->comments()->with([
                'ownerDetails.profilePicture',
                'referenceUsers',
            ])
                ->oldest()->offset($request->offset ?? 0)->limit($request->limit ?? 5)->get();

            //check if more comments available after this request
            $nextoffset = ($request->offset ?? 0) + ($request->limit ?? 5);
            $data['has_more'] = $post->comments()->offset($nextoffset)->limit($request->limit ?? 5)->get()->count() > 0 ? true : false;

            $data['comments'] = $comments;
            return response()->json($data, 200);
        }
        $comments = Comment::with(['ownerDetails.profilePicture', 'referenceUsers'])->paginate(10);
        return response()->json($comments);
    }
    public function commentCreate(CreateCommentRequest $request)
    {
        $type = $request->type;
        $content = $request->content;
        $user = $request->user();
        $commentable = $type == 'parent' ? Post::findOrFail($request->commentable) : Comment::findOrFail($request->commentable);
        if ($user->cannot('access', $commentable)) {
            return abort(401, 'unauthorized');
        }
        Log::channel('event')->info('iam erer', []);
        // if comment type is reply makes sure that parent is not a reply so that there can no mulitilevel nested reply
        if ($type == 'reply') {
            $commentable = $commentable->commentable_type == 'reply' ? $commentable->parent : $commentable;
        }
        //handel references 
        $comment = Comment::create([
            'content' => $content,
            'owner' => $user->id,
            'commentable_id' => $commentable->id,
            'comment_type' => $type,
        ]);

        $references = null;
        if ($request->filled('references')) {
            $references = collect($request->references);
            $references = $references->map(fn ($user) => new CommentReferences(['user_id' => $user]));
            $comment->references()->saveMany($references);
        }
        $comment->load([
            'ownerDetails.profilePicture',
            'referenceUsers',
        ]);
        return response()->json($comment, 201);
    }

    public function updateComment(Request $request, Comment $comment)
    {
        if ($request->user()->cannot('update', $comment)) {
            return response('you are not the owner of this comment', 403);
        }
        $request->validate([
            'content' => 'required|string|min:5',
        ]);
        $comment->content = $request->content;
        $comment->save();
        return response()->json($comment, 204);
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
