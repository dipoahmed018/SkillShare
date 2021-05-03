<?php

namespace Tests\Unit;

use App\Models\Catagory;
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
        // $response = $this->get('/api/forum');
        $response = Price::find(1)->tuition();
        $response->dump();
        $this->assertTrue(true);
    }
}
