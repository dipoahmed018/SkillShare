<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Forum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->paragraph(1),
        ];
    }
    public function reply()
    {
        return $this->state(function (array $attributes) {
            $user = User::has('courseForum')->get()->random();
            $forums = collect($user->courseForum()->with('posts.comments')->get());
            $comment = $forums->pluck('posts')->collapse()->pluck('comments')->collapse()->random()->id;
            return [
                'owner' => $user->id,
                'commentable_id' => $comment,
                'comment_type' => 'reply',
            ];
        });
    }
    public function parent()
    {
        return $this->state(function (array $attributes) {
            $user = User::has('courseForum')->get()->random();
            $post = $user->courseForum()->with('posts')->get()->pluck('posts')->collapse()->random();
            return [
                'owner' => $user->id,
                'commentable_id' => $post->id,
                'comment_type' => 'parent',
            ];
        });
    }
}
