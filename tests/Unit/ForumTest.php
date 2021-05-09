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
        $owner = User::all()->random();
        $type = rand(0, 1) ? $owner->cousers : $owner->tuitions;

        // $review = collect($type->review)->random();
        dump($owner);
        $this->assertTrue(true);
    }
}
