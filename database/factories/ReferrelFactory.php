<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Referrel;
use App\Models\Tuition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReferrelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Referrel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'referrel_token' => 'Rtoken'. $this->faker->regexify('[A-Za-z0-9]{15}'),
            'cut_of' => rand(1,50),
            'quantity' => rand(1,200),
            'exipres_at' => now()->addMonth(),
        ];
    }
    public function course()
    {
        return $this->state(function (array $attributes){
           $course = Course::all()->random();
           return [
               'item_id' => $course->id,
               'item_type' => 'course'
           ];
        });
    }
}
