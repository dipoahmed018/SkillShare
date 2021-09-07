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
use App\Services\Filters\Sort;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class SimpleTest extends TestCase
{
    function test_simple()
    {
        $query = Course::search('v')->where('price', '<', 4000)->get();
        dump($query);
        assertTrue(true);
    }
}
