<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReview;
use App\Http\Requests\Review\EditReview;
use App\Models\Course;
use App\Models\Review;
use App\Models\Tuition;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function createReview(CreateReview $request)
    {
        $reviewable_type = $request->route('name');
        $reviewable_id = $request->route('id');
        if ($reviewable_type == 'course') {
            $course = Course::findOrFail($reviewable_id);
            if ($request->user()->cannot('review', $course)) {
                return abort(403, 'you are not autorized to review this course');
            }
            $course->review()->create([
                'content' => $request->content,
                'stars' => $request->stars,
                'owner' => $request->user()->id,
            ]);
        }
        if ($reviewable_type == 'tuition') {
            $tuition = Tuition::findOrFail($reviewable_id);
            if ($request->user()->cannot('review', $tuition)) {
                return abort(403, 'you are not autorized to review this tuition');
            }
            $tuition->review()->create([
                'content' => $request->content,
                'stars' => $request->stars,
                'owner' => $request->user()->id,
            ]);
        }
        if ($reviewable_type == 'review_reply') {
            $review = Review::findOrFail($reviewable_id);
            $baseparent = $review->base_parent();
            $parent = null;
            if ($request->user()->cannot('reply', $review)) {
                return abort(403, 'you are not autorized to make reply to this review');
            }
            if ($review->reviewable_type == 'review_reply') {
                $parent = $review->review_parent;
                $parent->review_replys()->create([
                    'content' => $request->content,
                    'stars' => 0,
                    'owner' => $request->user()->id,
                ]);
            } else {
                $review->review_replys()->create([
                    'content' => $request->content,
                    'stars' => 0,
                    'owner' => $request->user()->id,
                ]);
            }
            return redirect("/show/" . $baseparent->getTable() . "/$baseparent->id");
        }
        return redirect("/show/$reviewable_type/$reviewable_id");
    }
    public function editReview(EditReview $request, Review $review)
    {
        $parent = $review->base_parent();
        $table = $parent->getTable();
        $review->content = $request->content ? $request->content : $review->content;
        $review->stars = $request->stars ? $request->stars : $review->stars;
        $review =  $review->save();
        // if ($request->acceptsJson()) {
        //     return response()->json($review, 200);
        // }
        return redirect("/show/$table/$parent->id");
    }
    public function deleteReview(Request $request, Review $review)
    {
        if ($request->user()->cannot('delete', $review)) {
            return abort(403, 'you are not autorized to delete this review');
        }
        $parent = $review->base_parent();
        $table = $parent->getTable();
        $review->review_replys()->delete();
        $review = $review->delete();
        // if ($request->acceptsJson()) {
        //     return response($review, 200);
        // }
        return redirect("/show/$table/$parent->id");
    }
}
