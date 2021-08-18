<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $owner = User::all()->random();
        return [
            'content' => $this->faker->paragraph(1),
            'stars' => rand(1,10),
            'owner' => $owner->id,
        ];
    }
    public function course()
    {
        return $this->state(function(array $attributes){
            $owner = User::find($attributes['owner']);
            $course = collect($owner->courses)->random();
            return  [
                'reviewable_id' => $course->id,
                'reviewable_type' => 'course',
            ];
        });
    }
    public function reply()
    {
        return $this->state(function(array $attributes){
           $owner = User::find($attributes['owner']);
           $type =   collect($owner->courses)->random();
           $review = collect($type->review)->random();
           return [
               'reviewable_id' => $review->id,
               'reviewable_type' => 'review_reply',
           ];
        });
    }
}
