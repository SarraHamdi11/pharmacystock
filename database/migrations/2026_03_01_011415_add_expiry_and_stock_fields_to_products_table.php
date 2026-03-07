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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('min_stock')->default(10)->after('price');
            $table->date('expiry_date')->nullable()->after('min_stock');
            $table->boolean('track_expiry')->default(true)->after('expiry_date');
            $table->text('storage_conditions')->nullable()->after('track_expiry');
            $table->string('batch_number')->nullable()->after('storage_conditions');
            $table->string('manufacturer')->nullable()->after('batch_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'min_stock',
                'expiry_date',
                'track_expiry',
                'storage_conditions',
                'batch_number',
                'manufacturer'
            ]);
        });
    }
};
