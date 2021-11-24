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
use App\Models\Vote;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\assertTrue;

class ForumTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_reations()
    {
        // $response = Course::query()->Price(300, 5000)->Catagory(5);
        // $response->selectRaw('select stars from reviews')
        // dump($response->get());
        $forum = Forum::find(4);
        $response = $forum->coverPhoto()->delete();
        dump($response);
    }
}
