<?php

namespace Tests\Unit;

use App\Models\Catagory;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Group;
use App\Models\Message;
use App\Models\Post;
use App\Models\Price;
use App\Models\Referrel;
use App\Models\Review;
use App\Models\Tuition;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ForumTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {  
        $forum =collect( DB::table('forum')
        ->join('tuition_students','tuition_id', '=','forum.forumable_id')
        ->join('course_students','course_id','=','forum.forumable_id')
        ->whereRaw('tuition_students.student_id = ? OR course_students.student_id = ?',[1,1])
        ->get() )->random();
         $comments = DB::table('comment')
        ->joinSub('SELECT `post`.id FROM post WHERE `post`.postable_id = ' . $forum->id,'post_id',function($join){
            $join->on('comment.commentable_id','=','post_id.id');
        })
        ->whereIn('comment.commentable_type',['parent','answer'])->get();
        dump($comments);
       $this->assertTrue(true);
    }
}
