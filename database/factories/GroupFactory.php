<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'id_parent' => 0,
        ];
    }

    public function withParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'id_parent' => Group::where('id_parent', 0)->inRandomOrder()->first()->id,
            ];
        });
    }
}
