<?php

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $forum = Forum::all()->random();
        $student = $forum->members->random();
        return [
            'title' => $this->faker->paragraph(1),
            'content' => $this->faker->paragraph(2),
            'postable_id' => $forum->id,
            // 'post_type' => $this->faker->randomElement(['post','question']),
            'post_type' => 'post',
            'owner' => $student->id,
        ];
    }
}
