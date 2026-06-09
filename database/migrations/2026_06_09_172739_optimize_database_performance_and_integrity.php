<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Optimisation de la table 'products'
        Schema::table('products', function (Blueprint $table) {
            // Index composite pour les recherches fréquentes
            $table->index(['category_id', 'supplier_id'], 'idx_product_cat_sup');
            $table->index(['active', 'track_expiry'], 'idx_product_active_expiry');
        });

        // 2. Optimisation de la table 'orders'
        Schema::table('orders', function (Blueprint $table) {
            $table->index('order_date');
            $table->index('status');
            $table->index('customer_id');
        });

        // 3. Optimisation de la table 'order_items' (anciennement product_orders)
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('order_id');
        });

        // 4. Optimisation de la table 'stocks'
        Schema::table('stocks', function (Blueprint $table) {
            // Index composite pour accélérer les lookups de stock par magasin
            $table->index(['product_id', 'store_id'], 'idx_stock_product_store');
        });

        // 5. Optimisation de la table 'activities'
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->index(['subject_id', 'subject_type']);
                $table->index('causer_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex(['subject_id', 'subject_type']);
            $table->dropIndex(['causer_id']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex('idx_stock_product_store');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['order_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['customer_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_product_cat_sup');
            $table->dropIndex('idx_product_active_expiry');
        });
    }
};
