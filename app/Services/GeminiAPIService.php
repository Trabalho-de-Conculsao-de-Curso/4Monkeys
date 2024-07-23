<?php
namespace App\Services;

use App\Models\Produto;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


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
            $recommendations = $this->parseResponse($response);
            return $this->calculateTotals($recommendations['desktops']);
        }
    }

    protected function generatePrompt(array $softwares, array $produtos)
    {
        $prompt = "Avalie os requisitos dos softwares selecionados e baseie-se nelas para a montagem dos desktops.\n\n";
        $prompt .= "Monte 3 desktops categorizados como bronze, silver e gold baseado nos softwares escolhidos e seus requisitos.\n";
        $prompt .= "Crie um arquivo tipo Json mostrando todos os produtos necessarios para que um desktop atenda os requisitos das categorias de acordo com os produtos cadastrados no banco\n\n";
        $prompt .= "Certifique-se de que todos os componentes são compatíveis entre si e que cada desktop inclui os seguintes componentes essenciais: CPU, GPU, RAM, HD ou SSD, Fonte, MOTHERBOARD e Cooler.\n\n";
        $prompt .= "Retorne os dados estruturados no seguinte formato JSON:\n\n";
        $prompt .= "{ \"desktops\": [ { \"categoria\": \"bronze\", \"componentes\": { \"CPU\": \"Produto analisado\", \"GPU\": \"Produto analisado\", \"RAM\": \"Produto analisado\", \"Fonte\": \"Produto analisado\", \"MOTHERBOARD\": \"Produto analisado\", \"Cooler\": \"Produto analisado\", \"HD\": \"Produto analisado\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS }, { \"categoria\": \"silver\", \"componentes\": { \"CPU\": \"Produto analisado\", \"GPU\": \"Produto analisado\", \"RAM\": \"Produto analisado\", \"Fonte\": \"Produto analisado\", \"MOTHERBOARD\": \"Produto analisado\", \"Cooler\": \"Produto analisado\", \"HD\": \"Produto analisado\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS }, { \"categoria\": \"gold\", \"componentes\": { \"CPU\": \"Produto analisado\", \"GPU\": \"Produto analisado\", \"RAM\": \"Produto analisado\", \"Fonte\": \"Produto analisado\", \"MOTHERBOARD\": \"Produto analisado\", \"Cooler\": \"Produto analisado\", \"HD\": \"Produto analisado\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS } ] }\n\n";
        $prompt .= "Garanta a integridade e consistência de todas as informações.\n\n";
        $prompt .= "Softwares selecionados:\n";

        foreach ($softwares as $software) {
            $prompt .= "- {$software['nome']}\n";
            $prompt .= "- {$software['requisitos']}\n";
        }

        $prompt .= "\nProdutos Disponíveis:\n";
        foreach ($produtos as $produto) {
            $produtoModel = Produto::find($produto['id']);
            $marcaNome = $produtoModel->marca->nome;
            $especificacoes = $produtoModel->especificacoes->detalhes;
            $preco = $produtoModel->preco->valor;

            $prompt .= "- Nome: {$produto['nome']}, Preço: {$preco}, Marca: {$marcaNome}, Especificações: {$especificacoes}\n";
        }

        return $prompt;
    }

    protected function parseResponse($response)
    {
        $candidates = $response->candidates ?? [];

        foreach ($candidates as $candidate) {
            $content = $candidate->content->parts[0]->text ?? null;
            if ($content) {
                Log::info('Resposta da API Gemini: ' . $content);

                $cleanContent = preg_replace('/```json|```/', '', $content);
                $cleanContent = trim($cleanContent);


                Log::info('Tentando decodificar o seguinte JSON: ' . $cleanContent);
                $decodedContent = json_decode($cleanContent, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    Log::info('JSON decodificado com sucesso: ' . print_r($decodedContent, true));
                    return $decodedContent;
                } else {
                    Log::error('Erro ao decodificar o JSON: ' . json_last_error_msg());
                    throw new \Exception('Erro ao decodificar a resposta JSON: ' . json_last_error_msg());
                }
            }
        }

        throw new \Exception('Resposta da API do Gemini não está no formato esperado');

    }

    public function calculateTotals($desktops)
    {
        $totals = [
            'bronze' => 0,
            'silver' => 0,
            'gold' => 0,
        ];

        foreach ($desktops as &$desktop) { // Use a referência para modificar o array original
            $category = $desktop['categoria'];
            $components = $desktop['componentes'];
            $total = 0;

            foreach ($components as $componentName) {
                $product = Produto::where('nome', $componentName)
                    ->orWhereHas('especificacoes', function ($query) use ($componentName) {
                        $query->where('detalhes', 'like', "%$componentName%");
                    })
                    ->first();

                if ($product) {
                    $price = $product->preco->valor;
                    Log::info("Produto: $componentName, Preço: $price");
                    $total += $price;
                } else {
                    Log::warning("Produto não encontrado: $componentName");
                }
            }

            Log::info("Total para a categoria $category: $total");
            $desktop['total'] = $total; // Atualize o total no array $desktops
            $totals[$category] = $total;
        }

        return [
            'desktops' => $desktops,
            'totals' => $totals,
        ];
    }

}





