<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'store_id' => Store::factory(),
            'quantity_stock' => $this->faker->numberBetween(10, 500),
        ];
    }
}