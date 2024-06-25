<?php

namespace Database\Seeders;

use App\Models\Marca;
use App\Models\Produto;
use App\Models\User;
use App\Models\Produto;
use App\Models\LojasOnlines;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        Produto::factory()->create();
=======

        $this->call([
            MarcaSeeder::class,
            EspecificacoesSeeder::class,
            PrecoSeeder::class,
            LojaOnlineSeeder::class,
            ProdutoSeeder::class,
            SoftwareSeeder::class,
        ]);

>>>>>>> d170fa60fde362a52b7237dafded019a6462d741
    }
}
