<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeGeminiAPIService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.free_gemini.api_key');
        $this->apiUrl = config('services.free_gemini.api_url');
    }

    public function getRecommendations(array $softwares, array $produtos)
    {
        $prompt = $this->generatePrompt($softwares, $produtos);

        // Fazer a chamada à API do Gemini usando a classe Gemini (sem a apiUrl explícita)
        $response = Gemini::geminiPro()->generateContent([$prompt]);

        if ($response && !empty($response->candidates) && is_array($response->candidates)) {
            // Extrair o conteúdo da resposta
            $content = $response->candidates[0]->content->parts[0]->text;

            // Parsear as recomendações do conteúdo textual
            return $this->parseRecommendations($content);
        } else {
            // Tratar erros no caso de resposta inesperada
            Log::error('Resposta inesperada da API do Gemini.');
            throw new \Exception('Resposta inesperada da API do Gemini');
        }
    }

    protected function generatePrompt(array $softwares, array $produtos)
    {
        // Similar ao generatePrompt do GeminiAPIService, mas simplificado
        $prompt = "Crie três desktops categorizados como bronze, silver e gold com base nos softwares selecionados e produtos disponíveis.\n\n";
        $prompt .= "Retorne os dados estruturados no seguinte formato JSON, mantendo apenas os nomes dos componentes e removendo qualquer informação adicional:\n\n";
        $prompt .= "{ \"desktops\": [ { \"categoria\": \"bronze\", \"componentes\": { \"CPU\": \"Nome do Produto\", \"GPU\": \"Nome do Produto\", \"RAM\": \"Nome do Produto\", \"Fonte\": \"Nome do Produto\", \"MOTHERBOARD\": \"Nome do Produto\", \"Cooler\": \"Nome do Produto\", \"HD\": \"Nome do Produto\" } }, { \"categoria\": \"silver\", \"componentes\": { \"CPU\": \"Nome do Produto\", \"GPU\": \"Nome do Produto\", \"RAM\": \"Nome do Produto\", \"Fonte\": \"Nome do Produto\", \"MOTHERBOARD\": \"Nome do Produto\", \"Cooler\": \"Nome do Produto\", \"HD\": \"Nome do Produto\" } }, { \"categoria\": \"gold\", \"componentes\": { \"CPU\": \"Nome do Produto\", \"GPU\": \"Nome do Produto\", \"RAM\": \"Nome do Produto\", \"Fonte\": \"Nome do Produto\", \"MOTHERBOARD\": \"Nome do Produto\", \"Cooler\": \"Nome do Produto\", \"HD\": \"Nome do Produto\" } } ] }\n\n";
        $prompt .= "Softwares selecionados:\n";
        foreach ($softwares as $software) {
            $prompt .= "- Nome: {$software['nome']}\n";
        }

        $prompt .= "Produtos disponíveis:\n";
        foreach ($produtos as $produto) {
            $prompt .= "- Nome: {$produto}\n";
        }


        return $prompt;
    }

    protected function parseRecommendations($content)
    {
        // Decodifica o JSON retornado pela API
        $decodedContent = json_decode($content, true);

        // Adiciona logs para verificar o conteúdo decodificado
        Log::info('Resposta JSON decodificada: ', $decodedContent);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Erro ao decodificar JSON: ' . json_last_error_msg());
            throw new \Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
        }

        // Extrai os desktops do conteúdo decodificado
        $desktops = [];
        foreach ($decodedContent['desktops'] as $desktop) {
            $desktops[] = [
                'categoria' => $desktop['categoria'],  // Pode ser 'bronze', 'silver' ou 'gold'
                'componentes' => $desktop['componentes'],  // Contém os componentes do desktop
            ];
        }

        // Log dos desktops extraídos
        Log::info('Desktops parseados: ', $desktops);

        return $desktops;
    }
}
