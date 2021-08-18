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
            $course = Course::all()->random(5);
            $catagoryable = [];
            foreach ($course as $key => $course) {
                $catagoryable[(int)$course->id] = ['catagoryable_type' => 'course'];
            }
            $catagory->posts()->syncWithoutDetaching($catagoryable);
        });
    }
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
