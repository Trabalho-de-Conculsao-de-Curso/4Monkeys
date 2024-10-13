<?php

use function Pest\Laravel\artisan;

it('executa o scraper Python com sucesso', function () {
    // Executa o comando Artisan
    artisan('scraper:run')
        ->expectsOutput('Scraper executado com sucesso')  // Verifica se a saída esperada ocorreu
        ->assertExitCode(0);  // Verifica se o comando terminou com sucesso
});



