<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produto;
use App\Models\LojasOnlines;
=======

use App\Models\Produto;
use App\Models\Especificacoes;
use App\Models\Marca;
use App\Models\Preco;
use App\Models\LojaOnline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
>>>>>>> d170fa60fde362a52b7237dafded019a6462d741


class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
       Produto::factory()->create();

=======
        Produto::create([
            'nome' => 'CPU',
            'marca_id' => 1,
            'especificacoes_id' => 1,
            'preco_id' => 1,
            'loja_online_id' => 1,
        ]);

        Produto::create([
            'nome' => 'COOLER',
            'marca_id' => 2,
            'especificacoes_id' => 2,
            'preco_id' => 2,
            'loja_online_id' => 2,
        ]);

        Produto::create([
            'nome' => 'GPU',
            'marca_id' => 3,
            'especificacoes_id' => 3,
            'preco_id' => 3,
            'loja_online_id' => 3,
        ]);

        Produto::create([
            'nome' => 'MOTHERBOARD',
            'marca_id' => 4,
            'especificacoes_id' => 4,
            'preco_id' => 4,
            'loja_online_id' => 4,
        ]);

        Produto::create([
            'nome' => 'RAM',
            'marca_id' => 5,
            'especificacoes_id' => 5,
            'preco_id' => 5,
            'loja_online_id' => 5,
        ]);

        Produto::create([
            'nome' => 'HDD',
            'marca_id' => 6,
            'especificacoes_id' => 6,
            'preco_id' => 6,
            'loja_online_id' => 6,
        ]);

        Produto::create([
            'nome' => 'CABINET',
            'marca_id' => 7,
            'especificacoes_id' => 7,
            'preco_id' => 7,
            'loja_online_id' => 7,
        ]);

        Produto::create([
            'nome' => 'SOURCE',
            'marca_id' => 8,
            'especificacoes_id' => 8,
            'preco_id' => 8,
            'loja_online_id' => 8,
        ]);

        Produto::create([
            'nome' => 'CPU',
            'marca_id' => 9,
            'especificacoes_id' => 9,
            'preco_id' => 9,
            'loja_online_id' => 9,
        ]);

        Produto::create([
            'nome' => 'COOLER',
            'marca_id' => 10,
            'especificacoes_id' => 10,
            'preco_id' => 10,
            'loja_online_id' => 10,
        ]);

        Produto::create([
            'nome' => 'GPU',
            'marca_id' => 11,
            'especificacoes_id' => 11,
            'preco_id' => 11,
            'loja_online_id' => 11,
        ]);

        Produto::create([
            'nome' => 'MOTHERBOARD',
            'marca_id' => 12,
            'especificacoes_id' => 12,
            'preco_id' => 12,
            'loja_online_id' => 12,
        ]);

        Produto::create([
            'nome' => 'RAM',
            'marca_id' => 13,
            'especificacoes_id' => 13,
            'preco_id' => 13,
            'loja_online_id' => 13,
        ]);

        Produto::create([
            'nome' => 'HDD',
            'marca_id' => 14,
            'especificacoes_id' => 14,
            'preco_id' => 14,
            'loja_online_id' => 14,
        ]);

        Produto::create([
            'nome' => 'CABINET',
            'marca_id' => 15,
            'especificacoes_id' => 15,
            'preco_id' => 15,
            'loja_online_id' => 15,
        ]);

        Produto::create([
            'nome' => 'SOURCE',
            'marca_id' => 16,
            'especificacoes_id' => 16,
            'preco_id' => 16,
            'loja_online_id' => 16,
        ]);

        Produto::create([
            'nome' => 'CPU',
            'marca_id' => 17,
            'especificacoes_id' => 17,
            'preco_id' => 17,
            'loja_online_id' => 17,
        ]);

        Produto::create([
            'nome' => 'COOLER',
            'marca_id' => 18,
            'especificacoes_id' => 18,
            'preco_id' => 18,
            'loja_online_id' => 18,
        ]);

        Produto::create([
            'nome' => 'GPU',
            'marca_id' => 19,
            'especificacoes_id' => 19,
            'preco_id' => 19,
            'loja_online_id' => 19,
        ]);

        Produto::create([
            'nome' => 'MOTHERBOARD',
            'marca_id' => 20,
            'especificacoes_id' => 20,
            'preco_id' => 20,
            'loja_online_id' => 20,
        ]);

        Produto::create([
            'nome' => 'RAM',
            'marca_id' => 21,
            'especificacoes_id' => 21,
            'preco_id' => 21,
            'loja_online_id' => 21,
        ]);

        Produto::create([
            'nome' => 'HDD',
            'marca_id' => 22,
            'especificacoes_id' => 22,
            'preco_id' => 22,
            'loja_online_id' => 22,
        ]);

        Produto::create([
            'nome' => 'CABINET',
            'marca_id' => 23,
            'especificacoes_id' => 23,
            'preco_id' => 23,
            'loja_online_id' => 23,
        ]);

        Produto::create([
            'nome' => 'SOURCE',
            'marca_id' => 24,
            'especificacoes_id' => 24,
            'preco_id' => 24,
            'loja_online_id' => 24,
        ]);

        Produto::create([
            'nome' => 'CPU',
            'marca_id' => 25,
            'especificacoes_id' => 25,
            'preco_id' => 25,
            'loja_online_id' => 25,
        ]);

        Produto::create([
            'nome' => 'COOLER',
            'marca_id' => 26,
            'especificacoes_id' => 26,
            'preco_id' => 26,
            'loja_online_id' => 26,
        ]);

        Produto::create([
            'nome' => 'GPU',
            'marca_id' => 27,
            'especificacoes_id' => 27,
            'preco_id' => 27,
            'loja_online_id' => 27,
        ]);

        Produto::create([
            'nome' => 'MOTHERBOARD',
            'marca_id' => 28,
            'especificacoes_id' => 28,
            'preco_id' => 28,
            'loja_online_id' => 28,
        ]);

        Produto::create([
            'nome' => 'RAM',
            'marca_id' => 29,
            'especificacoes_id' => 29,
            'preco_id' => 29,
            'loja_online_id' => 29,
        ]);

        Produto::create([
            'nome' => 'HDD',
            'marca_id' => 30,
            'especificacoes_id' => 30,
            'preco_id' => 30,
            'loja_online_id' => 30,
        ]);

        Produto::create([
            'nome' => 'CABINET',
            'marca_id' => 31,
            'especificacoes_id' => 31,
            'preco_id' => 31,
            'loja_online_id' => 31,
        ]);

        Produto::create([
            'nome' => 'SOURCE',
            'marca_id' => 32,
            'especificacoes_id' => 32,
            'preco_id' => 32,
            'loja_online_id' => 32,
        ]);

        Produto::create([
            'nome' => 'CPU',
            'marca_id' => 33,
            'especificacoes_id' => 33,
            'preco_id' => 33,
            'loja_online_id' => 33,
        ]);

        Produto::create([
            'nome' => 'COOLER',
            'marca_id' => 34,
            'especificacoes_id' => 34,
            'preco_id' => 34,
            'loja_online_id' => 34,
        ]);

        Produto::create([
            'nome' => 'GPU',
            'marca_id' => 35,
            'especificacoes_id' => 35,
            'preco_id' => 35,
            'loja_online_id' => 35,
        ]);

        Produto::create([
            'nome' => 'MOTHERBOARD',
            'marca_id' => 36,
            'especificacoes_id' => 36,
            'preco_id' => 36,
            'loja_online_id' => 36,
        ]);

        Produto::create([
            'nome' => 'RAM',
            'marca_id' => 37,
            'especificacoes_id' => 37,
            'preco_id' => 37,
            'loja_online_id' => 37,
        ]);

        Produto::create([
            'nome' => 'HDD',
            'marca_id' => 38,
            'especificacoes_id' => 38,
            'preco_id' => 38,
            'loja_online_id' => 38,
        ]);

        Produto::create([
            'nome' => 'CABINET',
            'marca_id' => 39,
            'especificacoes_id' => 39,
            'preco_id' => 39,
            'loja_online_id' => 39,
        ]);

        Produto::create([
            'nome' => 'SOURCE',
            'marca_id' => 40,
            'especificacoes_id' => 40,
            'preco_id' => 40,
            'loja_online_id' => 40,
        ]);
>>>>>>> d170fa60fde362a52b7237dafded019a6462d741
    }
}
