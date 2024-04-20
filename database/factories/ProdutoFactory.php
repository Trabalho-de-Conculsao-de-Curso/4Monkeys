<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome'=> $this->faker->word(),
            'marca'=> $this->faker->company(),
            'especificacoes'=> $this->faker->sentence(),
            'preco'=> $this->faker->randomFloat(2, 10, 1000),
            'lojasOnline'=> json_encode(['Loja A', 'Loja B', 'Loja C'])
        ];
    }
}
