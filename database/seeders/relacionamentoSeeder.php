<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class relacionamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = Marca::factory()->count(2)->create();


        $marcas->each(function ($marca) {
            Produto::factory()->count(1)->create(['marca_id' => $marca->id]);
        });
    }
}
