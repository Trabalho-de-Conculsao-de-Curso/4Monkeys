<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Produto;
use App\Models\LojasOnlines;


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

        $lojasOnlines = LojasOnline::factory()->create();
        return [
            'nome' => $this->faker->word(),
            'marca' => $this->faker->company(),
            'especificacoes' => $this->faker->sentence(),
            'preco' => $this->faker->randomFloat(2, 10, 1000),
            'lojasOnline_id'=>$lojasOnlines->id,
        ];
    }
}
