<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'generic_name' => $this->faker->word(),
            'code_bar' => $this->faker->unique()->ean13(),
            'category_id' => Category::factory(),
            'supplier_id' => Supplier::factory(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}