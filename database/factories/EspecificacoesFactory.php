<?php

namespace Database\Factories;

use App\Models\Especificacoes;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marca>
 */
class EspecificacoesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Especificacoes::class;
    public function definition(): array
    {

        return [
            'detalhes' => $this->faker->randomElement(['XXXX', 'YYYY', 'ZZZZ']),

        ];
    }
}
