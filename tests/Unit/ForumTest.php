<?php

namespace Tests\Unit;

use App\Models\Forum;
use PHPUnit\Framework\TestCase;
class ForumTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $forum = Forum::find(1);
        // $forum->questions;
        $this->assertTrue(true);
    }
}
