<?php

use App\Models\Post;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class PostTest extends TestCase
{
    function test_simple()
    {
        $post= Post::find(81);
        $vote = $post->votes()->create([
            'vote_type' => 'increment',
            'voter_id' => 7,
        ]);
        assertTrue($vote ? true : false, 'vote created');
    }
}
