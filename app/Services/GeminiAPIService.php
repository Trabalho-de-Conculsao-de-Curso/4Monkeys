<?php
namespace App\Services;

use App\Models\Produto;
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
            'prompt' => $prompt,
            'model'
        ]);
        if ($response = Gemini::geminiPro()->generateContent([$prompt])) {
            return $this->parseResponse($response);
        }


    }

    protected function generatePrompt(array $softwares, array $produtos)
    {
        // Gerar o prompt que será enviado para a API do Gemini
        $prompt = "Baseado nos seguintes produtos e suas especificações, e nos softwares selecionados, monte 3 desktops categorizados como bronze, silver e gold.\n\n";
        $prompt .= "Retorne os dados estruturados no seguinte formato JSON:\n\n";
        $prompt .= "{ \"desktops\": [ { \"categoria\": \"bronze\", \"componentes\": { \"CPU\": \"Intel Core I5 3470 3ª Geração\", \"GPU\": \"GEFORCE GT 1030 2GB DDR4\", \"RAM\": \"Memória Oxy, 8GB, 1333MHz, DDR3\", \"Fonte\": \"Fonte Brazil PC Bpc-230, ATX 230W Real\", \"MOTHERBOARD\": \"PLACA MAE TGT H61 M.2, DDR3, SOCKET LGA1155\", \"Cooler\": \"COOLER PARA PROCESSADOR PCYES LORX, RAINBOW, 92MM\", \"HD\": \"SSD WD GREEN, 240GB, 2.5, SATA III 6GB/S\" }, \"total\": 71900 }, { \"categoria\": \"silver\", \"componentes\": { \"CPU\": \"Intel Core i5 12400F\", \"GPU\": \"GALAX GEFORCE GTX 1650 EX PLUS 4GB GDDR6\", \"RAM\": \"Memória DDR4 Kingston Fury Beast, 8GB, 3200Mhz, Black\", \"Fonte\": \"Fonte Gamemax GS600, 600W, 80 Plus White\", \"MOTHERBOARD\": \"Placa Mãe ASRock H610M-HVS, Chipset H610, Intel LGA 1700\", \"Cooler\": \"COOLER DEEPCOOL GAMMAXX SERIES AG400 WH, ARGB, 120MM\", \"HD\": \"SSD Kingston NV2, 500GB, M.2 NVMe\" }, \"total\": 247886 }, { \"categoria\": \"gold\", \"componentes\": { \"CPU\": \"AMD Ryzen 9 7950X3D\", \"GPU\": \"GALAX GEFORCE RTX 4060 TI 1-CLICK OC, 8GB, GDDR6\", \"RAM\": \"Memória RAM Kingston Fury Beast, RGB, 32GB, 6000MHz, DDR5\", \"Fonte\": \"Fonte XPG Kyber, 750W, 80 Plus Gold\", \"MOTHERBOARD\": \"Placa Mãe Asus Rog Strix X670E-A Gaming Wi-Fi, AMD X670, AM5\", \"Cooler\": \"Water Cooler Gigabyte Aorus Liquid Cooler 240, RGB, 240mm\", \"HD\": \"SSD KINGSTON NV2, 1TB, M.2 2280, PCIE NVME\" }, \"total\": 852955 } ] }";
        foreach ($softwares as $software) {
        $prompt .= "- {$software['nome']}\n";
        }

        $prompt .= "\nProdutos Disponíveis:\n";
        foreach ($produtos as $produto) {
            $produtoModel = Produto::find($produto['id']);
            $marcaNome = $produtoModel->marca->nome;
            $especificacoes = $produtoModel->especificacoes->detalhes;
            $preco = $produtoModel->preco->valor;

            $prompt .= "- Nome: {$produto['nome']}, Preço: {$preco}, Marca: {$marcaNome}, Especificações: {$especificacoes}\n";
        }

        $prompt .= "\nMonte os desktops bronze, silver e gold garantindo que cada um contenha os componentes essenciais e compátiveis (CPU, GPU, RAM, Fonte, MOTHERBOARD, Cooler, HD ou SSD) e Analise os preços de todos os produtos e calcule o preço total  para cada categoria e Retorne a resposta em JSON.";

        return $prompt;
    }

    protected function parseResponse($response)
    {
        $candidates = $response->candidates ?? [];

        foreach ($candidates as $candidate) {
            $content = $candidate->content->parts[0]->text ?? null;
            if ($content) {
                $decodedContent = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decodedContent;
                } else {
                    throw new \Exception('Erro ao decodificar a resposta JSON: ' . json_last_error_msg());
                }
            }
        }

        throw new \Exception('Resposta da API do Gemini não está no formato esperado');

    }
}
