<?php

namespace Database\Factories;

use App\Models\ProdutoFinal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProdutoFinal>
 */
class ProdutoFinalFactory extends Factory
{
    protected $model = ProdutoFinal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word,
            'categoria' => $this->faker->randomElement(['Categoria1', 'Categoria2', 'Categoria3']),
        ];
    }
}
