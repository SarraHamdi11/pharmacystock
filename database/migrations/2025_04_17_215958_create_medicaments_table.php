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
        Schema::create('medicaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('dosage')->nullable();
            $table->string('forme')->nullable();
            $table->string('fabricant')->nullable();
            $table->date('date_expiration')->nullable();
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
            $table->softDeletes();

            // Ajoutez ici d'autres colonnes spécifiques aux médicaments si nécessaire
            $table->index('date_expiration'); // Index pour faciliter la recherche par date d'expiration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicaments');
    }
};