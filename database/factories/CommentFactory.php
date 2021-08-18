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
    public function configure()
    {
        return $this->afterCreating(function (Comment $comment) {
            if ($comment->commentable_type !== 'reply') {
                $post =  $comment->parent;
                if ($post->answer == null) {
                    $post->answer = $comment->id;
                    $post->save();
                }
            }
        });
    }
    public function definition()
    {
        $post = Post::all()->random();
        $owner_type = null;
        $owner_type_id = null;
        $owner = collect(Forum::find($post->postable_id)->members)->random()->id;
        $owner_type = $this->faker->randomElement(['parent', 'answer']);
        $owner_type_id = $post->id;
        return [
            'content' => $this->faker->paragraph(1),
            'owner' => $owner,
            'commentable_id' => $owner_type_id,
            'commentable_type' => $owner_type,
        ];
    }
    public function reply()
    {
        return $this->state(function (array $attributes) {
            $owner = $attributes['owner'];
            $user = User::find($owner);
            $forums = collect($user->courseForum()->with('posts.comments','questions.answers')->get());
            $comments = $forums->pluck('posts')->collapse()->pluck('comments')->collapse();
            $answers = $forums->pluck('questions')->collapse()->pluck('answers')->collapse();
            $comment = $comments->concat($answers)->random();
            return [
                'commentable_id' => $comment->id,
                'commentable_type' => 'reply',
            ];
        });
    }
}
