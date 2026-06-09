<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing columns to orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->default(0)->after('order_date');
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('completed')->after('total_amount');
            }
        });

        // Fill existing orders with a number if they don't have one
        $orders = DB::table('orders')->whereNull('order_number')->get();
        foreach ($orders as $order) {
            DB::table('orders')
                ->where('id', $order->id)
                ->update(['order_number' => 'ORD-' . strtoupper(uniqid())]);
        }

        // Add indexes for performance
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
            $table->index('generic_name');
            $table->index('code_bar');
            $table->index('expiry_date');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index(['first_name', 'last_name']);
            $table->index('phone');
        });

        // Rename product_orders to order_items for clarity and consistency
        if (Schema::hasTable('product_orders') && !Schema::hasTable('order_items')) {
            Schema::rename('product_orders', 'order_items');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_items') && !Schema::hasTable('product_orders')) {
            Schema::rename('order_items', 'product_orders');
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['generic_name']);
            $table->dropIndex(['code_bar']);
            $table->dropIndex(['expiry_date']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['first_name', 'last_name']);
            $table->dropIndex(['phone']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_number', 'total_amount', 'status']);
        });
    }
};
