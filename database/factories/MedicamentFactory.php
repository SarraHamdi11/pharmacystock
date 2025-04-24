<?php

namespace Database\Factories;

use App\Models\Medicament;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicamentFactory extends Factory
{
    protected $model = Medicament::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'dosage' => $this->faker->randomElement(['50mg', '100mg', '200mg', '500mg']),
            'forme' => $this->faker->randomElement(['Comprimé', 'Gélule', 'Sirop', 'Pommade']),
            'fabricant' => $this->faker->company(),
            'date_expiration' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
            'seuil_alerte' => $this->faker->numberBetween(2, 15),
        ];
    }
}