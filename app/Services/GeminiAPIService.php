<?php
namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Http;


class GeminiAPIService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->apiUrl = config('services.gemini.api_url');

    }

    public function getRecommendations(array $softwares, array $produtos)
    {
        $prompt = $this->generatePrompt($softwares, $produtos);


        $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'Content-Type' => 'application/json'
        ])->post($this->apiUrl, [
        'prompt' => $prompt
        ]);

        if ( $response = Gemini::geminiPro()->generateContent([$prompt])) {
            return $response;
        }

        throw new \Exception('Erro ao se comunicar com a API do Gemini');

        // $prompt = $this->generatePrompt($softwares, $produtos);
        //
        // return $response;
    }

    protected function generatePrompt(array $softwares, array $produtos)
    {
        // Gerar o prompt que será enviado para a API do Gemini
        $prompt = "Baseado nos seguintes produtos e suas especificações, e nos softwares selecionados, monte 3 desktops categorizados como bronze, silver e gold:\n\n";

        $prompt .= "Softwares Selecionados:\n";
        foreach ($softwares as $software) {
        $prompt .= "- {$software['nome']}\n";
        }

        $prompt .= "\nProdutos Disponíveis:\n";
        foreach ($produtos as $produto) {
        $prompt .= "- Nome: {$produto['nome']}, Preço: {$produto['preco']}, Marca: {$produto['marca_id']}, Especificações: {$produto['especificacoes_id']}\n";
        }

        $prompt .= "\nMonte os desktops bronze, silver e gold garantindo que cada um contenha os componentes essenciais e compátiveis (CPU, GPU, Memória, Fonte, Placa mãe, Cooler) e que o preço total esteja dentro de uma faixa razoável para cada categoria.";

        return $prompt;
    }
}
