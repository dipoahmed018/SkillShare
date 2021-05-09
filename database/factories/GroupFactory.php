<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function configure()
    {
        return $this->afterCreating(function (Group $group) {
            $users = User::all()->except($group->owner)->pluck('id')->random(8);
            $userinstace = [];
            foreach ($users as $key => $value) {
                $userinstace[(int)$value] = ['member_type' => $this->faker->randomElement(['member','modaretor'])];
            }
            $group->members()->syncWithoutDetaching($userinstace);
        });
    }
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'owner' => User::all()->random()->id,
        ];
    }
}
