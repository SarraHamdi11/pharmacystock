<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityService;

class OrderService
{
    /**
     * Create a new order with its items.
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'order_date' => $data['order_date'] ?? now(),
                'status' => $data['status'] ?? 'completed',
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                $total += $item['quantity'] * $item['price'];
                
                // Deduct stock (simplified)
                $product = \App\Models\Product::find($item['product_id']);
                $stock = $product->stocks()->first();
                if ($stock) {
                    $stock->decrement('quantity_stock', $item['quantity']);
                }
            }

            $order->update(['total_amount' => $total]);

            ActivityService::log('created', "New order placed: {$order->order_number}", $order);

            return $order;
        });
    }
}
