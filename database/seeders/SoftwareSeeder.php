<?php

namespace Database\Seeders;

use App\Models\Software;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Software::create([
            'nome' => 'Valorant',
            'descricao' => 'Valorant é um FPS tático 5x5 que tem como objetivo plantar ou desarmar a Spike',
        ]);

        Software::create([
            'nome' => 'PUBG: BATTLEGROUNDS',
            'descricao' => 'PUBG: BATTLEGROUNDS é um battle royale de tiro que coloca 100 jogadores uns contra os outros até que o último sobreviva.',
        ]);

        Software::create([
            'nome' => 'ELDEN RING',
            'descricao' => 'Elden Ring é um RPG épico de ação ambientado em um mundo de fantasia sombria.',
        ]);

        Software::create([
            'nome' => 'Grand Theft Auto V',
            'descricao' => 'GTA V, é um jogo eletrônico de ação e aventura lançado em 2013 para o PS3.',
        ]);

        Software::create([
            'nome' => 'Pacote Office',
            'descricao' => 'O pacote office facilita processos diários, de modo a possibilitar maior produtividade.',
        ]);

        Software::create([
            'nome' => 'OBS',
            'descricao' => 'Open Broadcaster Software é um programa de streaming e gravação gratuito e de código aberto mantido pelo OBS Project.',
        ]);

        Software::create([
            'nome' => 'Adobe Photoshop',
            'descricao' => 'O Photoshop é um software da multinacional americana Adobe Inc. usado para edição de imagens, criação de arte digital, design gráfico e animações.',
        ]);

        Software::create([
            'nome' => 'Minecraft',
            'descricao' => 'Minecraft é um jogo eletrônico lançado em 2009 que consiste em sobreviver em um mundo formado (majoritariamente) por blocos cúbicos.',
        ]);

        Software::create([
            'nome' => 'PhpStorm',
            'descricao' => 'O PhpStorm fornece uma experiência simplificada para o ciclo de desenvolvimento completo com novos idiomas, como TypeScript, CoffeeScript e Dart.',
        ]);

        Software::create([
            'nome' => 'Visual Studio Code',
            'descricao' => 'O Visual Studio Code é um editor de código-fonte desenvolvido pela Microsoft, lançado em abril de 2015.',
        ]);
    }
}
