<?php

use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class SimpleTest extends TestCase 
{
    function test_simple(){
        $result = Comment::factory()->count(8)->make();
        // $result = Forum::all()->random();
        // // dump($result->members->random()->id);
        dump($result);
        // dump($result->members->random()->id);
    }
}