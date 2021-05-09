<?php

namespace Database\Factories;

use App\Models\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Price::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'stripe_price' => 'price_'. $this->faker->regexify('[A-Za-z0-1]{10}'),
            'stripe_product' => 'prod_'. $this->faker->regexify('[A-Za-z0-1]{10}'),
            'price' => $this->faker->randomNumber(1),
        ];
    }
}
