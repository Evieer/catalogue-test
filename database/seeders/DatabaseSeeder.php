<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Price;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Главные категории
        Group::factory()->count(5)->create();

        // Дочерние категории
        Group::factory()->count(10)->withParent()->create();
        Group::factory()->count(15)->withParent()->create();

        // Товары
        Product::factory()->count(500)->create()->each(function ($product) {
            Price::factory()->create(['id_product' => $product->id]);
        });
    }
}
