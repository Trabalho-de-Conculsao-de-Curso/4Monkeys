<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produto;
use App\Models\Marca;

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
    protected $model = Produto::class;
    public function definition(): array
    {
        $marca = Marca::factory()->create();
        return [
            'nome' => $this->faker->word(),
            'marca_id' => $marca->id,
            'especificacoes' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'lojasOnline'=> json_encode(['Loja A', 'Loja B', 'Loja C']),
        ];
    }

}
