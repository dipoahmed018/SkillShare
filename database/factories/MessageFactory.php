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
            $friend = collect( User::find($attributes['owner'])->getAllFriends() )->random();
            return [
                'receiver_id' => $friend->id,
                'receiver_type' => 'user',
            ];
        });
    }
    public function toGroups()
    {
        return $this->state(function(array $attributes){
            $group = collect(User::find($attributes['owner'])->groups)->random();
            return [
                'receiver_id' => $group->id,
                'receiver_type' => 'group',
            ];
        });
    }
}
