<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Tuition;
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
        // $response = $this->getJson('/api/forum/1');
        $response = Tuition::find(1)->forum()->with(['posts','questions'])->get();
        // $response->forum_id = 2;
        // $response->save();
        $response->dump();
        $this->assertTrue(true);
    }
}
