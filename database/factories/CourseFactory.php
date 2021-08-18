<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Forum;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function configure()
    {
        return $this->afterCreating(function (Course $course) {
            $users = User::all('id')->random(8);
            $course->students()->syncWithoutDetaching($users);
            $forum = Forum::factory()->create([
                'forumable_id' => $course->id,
                'forumable_type' => 'course',
                'owner' => $course->owner,
            ]);
           Review::factory()->create([
                'reviewable_id' => $course->id,
                'reviewable_type' => 'course',
            ]);
            $course->forum_id = $forum->id;
            $course->save();
        });
    }
    public function definition()
    {
        return [
            'title' => $this->faker->paragraph(1),
            'description' => $this->faker->paragraph(2),
            'price' => $this->faker->randomNumber(4),
            'owner' => User::all()->random(),
        ];
    }
}
