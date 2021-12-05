<?php

namespace Tests\Unit;

use App\Models\Comment;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_comment()
    {
        Comment::create([
            'content' => 'hello worlasldflsadfjlsdafkj',
            'owner' => 7,
            'comment_type' => 'parent',
            'commentable_id' => 10,
            'commentable_type' => 'question',
            'reference_ids' => [2, 4, 5, 6, 7, 8],
        ]);
    }
}
