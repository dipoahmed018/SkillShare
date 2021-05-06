<?php

namespace Database\Factories;

use App\Models\Forum;
use App\Models\Model;
use App\Models\Price;
use App\Models\Tuition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TuitionFactory extends Factory
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
    public function configure()
    {
        return $this->afterCreating(function (Tuition $tuition) {
            $forum = Forum::factory()->create([
                'forumable_id' => $tuition->id,
                'forumable_type' => 'tuition',
                'owner' => $tuition->owner,
            ]);
            $users = User::all()->random(8);
            $sync_user = [];
            foreach ($users as $key => $value) {
                $expired = rand(0,1);
                $expired_at = $expired ? now() : now()->addMonth();
                $sync_user[(int) $value->id] = ['expired' => $expired, 'expires_at' => $expired_at];
            }
            $tuition->students()->syncWithoutDetaching($sync_user);
            $tuition->forum_id = $forum->id;
            $tuition->save();
        });
    }
    public function definition()
    {
        return [
            'stripe_id' => Price::factory()->create()->stripe_product,
            'title' => $this->faker->paragraph(1),
            'description' => $this->faker->paragraph(2),
            'owner' => User::all()->random(),
        ];
    }
}
