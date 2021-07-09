<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Forum;
use App\Models\Group;
use App\Models\Price;
use App\Models\Course;
use App\Models\Review;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Tuition;
use App\Models\Catagory;
use App\Models\Referrel;
use App\Models\TutorialDetails;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\Authenticatable;

use function PHPUnit\Framework\assertTrue;

class ForumTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $post = Post::find(80);
        $students = $post->forum()->with(['members' => function ($query) {
            $query->where('student_id', 3);
        }, 'owner_details' => function ($query) {
            $query->where('id', 2);
        }])->get();
        dump($students->pluck('members')->first());
        assertTrue(true);
    }
}
