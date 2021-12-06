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
        $user = User::find(3);
        $response = $this->actingAs($user)
            ->postJson('/comment/create', [
                'content' => 'hello world',
                'commentable' => 4,
                'type' => 'reply',
                'references' => [1, 2, 3, 4],
            ]);
        $response->assertStatus(200);
    }
}
