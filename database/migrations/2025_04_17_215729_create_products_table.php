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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
           
            $table->string('picture')->nullable();
            $table->timestamps();
            $table->string('generic_name')->nullable();
            $table->string('code_bar')->unique()->nullable();
            $table->decimal('price', 8, 2); // Exemple avec 8 chiffres au total et 2 décimales
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
