<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeGeminiService
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
        // Gerar o prompt baseado nos softwares e produtos disponíveis
        $prompt = $this->generatePrompt($softwares, $produtos);

        // Enviar requisição para a API gratuita
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post($this->apiUrl, [
            'prompt' => $prompt,
            'model' => 'text-davinci-003'
        ]);

        if ($response=Gemini::geminiPro()->generateContent([$prompt])) {
            $recommendations = $this->parseResponse($response);
            return $recommendations['desktops']; // Retorna os desktops sem cálculo de preços
        } else {
            Log::error('Erro ao chamar a API gratuita do Gemini: ' . $response->body());
            throw new \Exception('Erro ao chamar a API gratuita do Gemini');
        }
    }

    protected function generatePrompt(array $softwares, array $produtos)
    {
        // Similar ao generatePrompt do GeminiAPIService, mas simplificado
        $prompt = "Crie três desktops categorizados como bronze, silver e gold com base nos softwares selecionados e produtos disponíveis.\n\n";
        $prompt .= "Softwares selecionados:\n";
        foreach ($softwares as $software) {
            $prompt .= "- Nome: {$software['nome']}\n";
        }

        $prompt .= "Produtos disponíveis:\n";
        foreach ($produtos as $produto) {
            $prompt .= "- Nome: {$produto['nome']}\n";
        }

        return $prompt;
    }

    protected function parseResponse($response)
    {
        // Parsear a resposta da API, similar ao GeminiAPIService
        if (isset($response['choices'][0]['text'])) {
            $content = $response['choices'][0]['text'];
            $cleanContent = preg_replace('/```json|```/', '', $content);
            $cleanContent = trim($cleanContent);

            Log::info('Resposta da API gratuita do Gemini: ' . $cleanContent);
            $decodedContent = json_decode($cleanContent, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                Log::info('JSON decodificado com sucesso: ' . print_r($decodedContent, true));
                return $decodedContent;
            } else {
                Log::error('Erro ao decodificar o JSON: ' . json_last_error_msg());
                throw new \Exception('Erro ao decodificar a resposta JSON: ' . json_last_error_msg());
            }
        }

        throw new \Exception('Resposta da API gratuita do Gemini não está no formato esperado');
    }
}
