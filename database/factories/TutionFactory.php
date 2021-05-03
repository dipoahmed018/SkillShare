<?php

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Model;
use App\Models\Tuition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TutionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tuition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'stripe_id' => ,
            'title' => $this->faker->paragraph(1),
            'description' => $this->faker->paragraph(2),
            'owner' => User::all()->random(),
            'forum_id' => ,
        ];
    }
}
