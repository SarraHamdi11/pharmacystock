<?php

namespace Database\Factories;

use App\Models\Maladie;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaladieFactory extends Factory
{
    protected $model = Maladie::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(2),
        ];
    }
}