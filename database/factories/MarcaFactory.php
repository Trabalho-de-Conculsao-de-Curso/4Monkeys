<?php

namespace Database\Factories;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marca>
 */
class MarcaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Marca::class;
    public function definition(): array
    {
        $produto = Produto::factory()->create();
        return [
            'nome' => $produto->nome,
            'produto_id' => $produto->id,
        ];
    }
}
