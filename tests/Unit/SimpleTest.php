<?php

use App\Models\Catagory;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Referrel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class SimpleTest extends TestCase
{
    function test_simple()
    {
        $post = Post::find(28);
        $votes  = $post->allVote;
        $count = $votes->where('vote_type','increment')->count() - $votes->where('vote_type', 'decrement')->count();
        dump($count);
        assertTrue(true);
    }
}
