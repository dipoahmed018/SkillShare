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
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        $user = User::find(11);
        $this->assertTrue(true);
    }
}
