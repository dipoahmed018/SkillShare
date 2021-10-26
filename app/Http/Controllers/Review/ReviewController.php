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

    public function getReplies(Review $review)
    {
       $replies = $review->reviewReplies()->with(['ownerDetails.profilePicture'])->simplePaginate(10);
       return response()->json(['data' => $replies, 'error' => false, 'success' => true]);
    }
    public function createReview(CreateReview $request)
    {
        $reviewable_type = $request->reviewable_type;
        $reviewable_id = $request->reviewable_id;
        $newReview = null;
        if ($reviewable_type == 'course') {
            $course = Course::findOrFail($reviewable_id);
            if ($request->user()->cannot('review', $course)) {
                return abort(403, 'you are not autorized to review this course');
            }
            $newReview = $course->review()->create([
                'content' => $request->content,
                'stars' => $request->stars ?: 0,
                'owner' => $request->user()->id,
            ]);
        }
        if ($reviewable_type == 'review_reply') {
            $review = Review::findOrFail($reviewable_id);
            if ($request->user()->cannot('reply', $review)) {
                return abort(403, 'you are not autorized to make reply to this review');
            }
            if ($review->reviewable_type == 'review_reply') {
                $parent = $review->reviewParent;
                $newReview = $parent->reviewReplies()->create([
                    'content' => $request->content,
                    'stars' => 0,
                    'owner' => $request->user()->id,
                ]);
            } else {
                $newReview = $review->reviewReplies()->create([
                    'content' => $request->content,
                    'stars' => 0,
                    'owner' => $request->user()->id,
                ]);
            }
        }
        $newReview->load('ownerDetails.profilePicture');
        if ($request->header('Accept') == 'application/json') {
            return response()->json(['data' => $newReview, 'error' => false, 'success' => true],200);
        }
        return redirect()->back();
    }
    public function editReview(EditReview $request, Review $review)
    {
        $parent = $review->base_parent();
        $table = $parent->getTable();
        $review->content = $request->content ? $request->content : $review->content;
        $review->stars = $request->stars ? $request->stars : $review->stars;
        $review =  $review->save();
        if ($request->acceptsJson()) {
            return response()->json(['data' => $review, 'error' => false, 'success' => true], 200);
        }
        return redirect("/show/$table/$parent->id");
    }
    public function deleteReview(Request $request, Review $review)
    {
        if ($request->user()->cannot('delete', $review)) {
            return abort(403, 'you are not autorized to delete this review');
        }
        $parent = $review->base_parent();
        $review->reviewReplies()->delete();
        $review->delete();
        if ($request->acceptsJson()) {
            return response()->json(['data' => $review, 'error' => false, 'success' => true], 200);
        }
        return redirect("/show/course/$parent->id");
    }
}
