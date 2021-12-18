<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use App\Models\Catagory;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Referrel;
use App\Models\Review;
use App\Models\User;
use Tests\TestCase;

class SeederTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_drop_pivot_tables()
    {
        DB::disableForeignKeyCheck();
        DB::table('course_students')->truncate();
        DB::table('comment_references')->truncate();
        DB::table('catagoryable')->truncate();

        $this->assertTrue(true);
    }

    public function test_drop_resourse_tables()
    {
        Review::truncate();
        Referrel::truncate();
        Comment::truncate();
        Catagory::truncate();
        Post::truncate();
        Notification::truncate();
        Forum::truncate();
        Course::truncate();
        User::truncate();
        $this->assertTrue(true);
    }
}
