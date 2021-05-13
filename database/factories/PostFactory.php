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
        $pivot_table = $forum->forumable_type === 'tuition' ? 'tuition_students' : 'course_students';
        $column_name = $forum->forumable_type === 'tuition' ? 'tuition_id' : 'course_id';
        $response = DB::table('users')->selectRaw('users.*')->join($pivot_table,'users.id','=',$pivot_table.'.student_id')->where($column_name,'=',$forum->forumable_id)->get();
        $student = $response->random();
        return [
            'title' => $this->faker->paragraph(1),
            'content' => $this->faker->paragraph(2),
            'vote' => random_int(1,1000),
            'postable_id' => $forum->id,
            'post_type' => $this->faker->randomElement(['post','question']),
            'owner' => $student->id,
        ];
    }
}
