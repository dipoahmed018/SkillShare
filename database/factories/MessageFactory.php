<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => 'hello world',
            'owner' => User::all()->random()->id,
        ];
    }
    public function toFriends()
    {
        return $this->state(function(array $attributes){
            
        });
    }
    public function toGroup()
    {
        return $this->state(function(array $attributes){
            $groups = User::find(1)->groups;
            $group = collect($groups)->random();
            return [
                'receiver_id' => $group->id,
                'receiver_type' => 'group',
            ]
        });
    }
}
