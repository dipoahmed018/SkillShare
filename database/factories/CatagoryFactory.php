<?php

namespace Database\Factories;

use App\Models\Catagory;
use App\Models\Course;
use App\Models\Post;
use App\Models\Tuition;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatagoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Catagory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function configure()
    {
        return $this->afterCreating(function (Catagory $catagory) {
            $lotery = rand(0, 2);
            if ($lotery == 0) {
                $tuition = Tuition::all()->random(5);
                $catagoryable = [];
                foreach ($tuition as $key => $tuition) {
                    $catagoryable[(int)$tuition->id] = ['catagoryable_type' => 'tuition'];
                }
                $catagory->posts()->syncWithoutDetaching($catagoryable);
            } elseif ($lotery == 1) {
                $course = Course::all()->random(5);

                $catagoryable = [];
                foreach ($course as $key => $course) {
                    $catagoryable[(int)$course->id] = ['catagoryable_type' => 'course'];
                }
                $catagory->posts()->syncWithoutDetaching($catagoryable);
            } elseif ($lotery == 2) {
                $posts = Post::all()->random(10);

                $catagoryable = [];
                foreach ($posts as $key => $post) {
                    $catagoryable[(int)$post->id] = ['catagoryable_type' => 'post'];
                }
                $catagory->posts()->syncWithoutDetaching($catagoryable);
            }
        });
    }
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
