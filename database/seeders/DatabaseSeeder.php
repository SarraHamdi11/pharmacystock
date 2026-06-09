<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::factory()->count(10)->create();

        Category::factory()->count(15)->create();

        Product::factory()->count(30)->create();

        Store::factory()->count(5)->create();

        Stock::factory()->count(100)->create();

        Customer::factory()->count(20)->create();
        Order::factory()->count(40)->create();

        Order::all()->each(function ($order) {
            Product::inRandomOrder()->limit(rand(1, 5))->get()->each(function ($product) use ($order) {
                \App\Models\OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'price' => $product->price ?? fake()->randomFloat(2, 5, 50), 
                ]);
            });
        });
    }
}