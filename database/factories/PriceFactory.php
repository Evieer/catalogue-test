<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Price;

class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'id_product' => \App\Models\Product::factory(),
        ];
    }
}
