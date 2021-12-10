<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_comment_method()
    {
        $user = User::findOrFail(3);
        $response = $this->actingAs($user)
            ->postJson('/comment/create', [
                'content' => 'hello world',
                'commentable' => 4,
                'type' => 'reply',
                'references' => [1, 2, 3, 4],
            ]);
        $response->assertStatus(200);
    }

    public function test_reference_sync()
    {
        $comment = Comment::findOrFail(90);
        $comment->referenceUsers()->sync([1]);
        $this->assertTrue(true);
    }
}
