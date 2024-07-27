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
            'model' =>'text-davinci-003'
        ]);
        if ($response = Gemini::geminiPro()->generateContent([$prompt])) {
            $recommendations = $this->parseResponse($response);
            return $this->calculateTotals($recommendations['desktops']);
        }
    }

    protected function generatePrompt(array $softwares, array $produtos)
    {
        $prompt = "Avalie os requisitos de desempenho dos softwares selecionados e utilize esses requisitos para montar três desktops categorizados como bronze, silver e gold.\n\n";
        $prompt .= "Monte os desktops de forma que atendam aos requisitos mínimos dos softwares escolhidos, focando na custo-efetividade dos componentes utilizados.\n";
        $prompt .= "Crie um arquivo JSON mostrando todos os produtos necessários para que cada desktop atenda os requisitos das categorias de acordo com os produtos cadastrados no banco de dados.\n\n";
        $prompt .= "Certifique-se de que todos os componentes são compatíveis entre si e que cada desktop inclui os seguintes componentes essenciais: CPU, GPU, RAM, HD ou SSD, Fonte, MOTHERBOARD e Cooler.\n\n";
        $prompt .= "Retorne os dados estruturados no seguinte formato JSON, mantendo apenas as especificações dos componentes e removendo qualquer informação adicional:\n\n";
        $prompt .= "{ \"desktops\": [ { \"categoria\": \"bronze\", \"componentes\": { \"CPU\": \"Especificação do Produto\", \"GPU\": \"Especificação do Produto\", \"RAM\": \"Especificação do Produto\", \"Fonte\": \"Especificação do Produto\", \"MOTHERBOARD\": \"Especificação do Produto\", \"Cooler\": \"Especificação do Produto\", \"HD\": \"Especificação do Produto\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS }, { \"categoria\": \"silver\", \"componentes\": { \"CPU\": \"Especificação do Produto\", \"GPU\": \"Especificação do Produto\", \"RAM\": \"Especificação do Produto\", \"Fonte\": \"Especificação do Produto\", \"MOTHERBOARD\": \"Especificação do Produto\", \"Cooler\": \"Especificação do Produto\", \"HD\": \"Especificação do Produto\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS SELECIONADOS }, { \"categoria\": \"gold\", \"componentes\": { \"CPU\": \"Especificação do Produto\", \"GPU\": \"Especificação do Produto\", \"RAM\": \"Especificação do Produto\", \"Fonte\": \"Especificação do Produto\", \"MOTHERBOARD\": \"Especificação do Produto\", \"Cooler\": \"Especificação do Produto\", \"HD\": \"Especificação do Produto\" }, \"total\": VALOR_DA_SOMA TOTAL_DOS_ITENS SELECIONADOS } ] }\n\n";
        $prompt .= "Garanta a integridade e consistência de todas as informações.\n\n";
        $prompt .= "Softwares selecionados e seus requisitos:\n";

        foreach ($softwares as $software) {
            $prompt .= "- Nome: {$software['nome']}\n";
            $prompt .= "  Requisitos de desempenho: {$software['requisitos']}\n";
        }

        $prompt .= "\nProdutos Disponíveis:\n";
        foreach ($produtos as $produto) {
            $produtoModel = Produto::find($produto['id']);
            $marcaNome = $produtoModel->marca->nome;
            $especificacoes = $produtoModel->especificacoes->detalhes;
            $preco = $produtoModel->preco->valor;

            $prompt .= "- Nome: {$produto['nome']}, Preço: R$ {$preco}, Marca: {$marcaNome}, Especificações: {$especificacoes}\n";
        }

        $prompt .= "\nDicas adicionais:\n";
        $prompt .= "- Priorize componentes com melhor relação custo-efetividade.\n";
        $prompt .= "- Para a categoria bronze, escolha componentes que atendam aos requisitos mínimos dos softwares com o menor custo.\n";
        $prompt .= "- Para a categoria silver, escolha componentes que ofereçam um bom equilíbrio entre desempenho e custo.\n";
        $prompt .= "- Para a categoria gold, escolha componentes de alta performance, mas ainda mantendo a preocupação com o custo-efetividade.\n";

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
                    $normalizedContent = $this->normalizeComponentNames($decodedContent);
                    return $normalizedContent;
                } else {
                    Log::error('Erro ao decodificar o JSON: ' . json_last_error_msg());
                    throw new \Exception('Erro ao decodificar a resposta JSON: ' . json_last_error_msg());
                }
            }
        }

        throw new \Exception('Resposta da API do Gemini não está no formato esperado');

    }


    protected function normalizeComponentNames($decodedContent){

        foreach ($decodedContent['desktops'] as &$desktop) {
            foreach ($desktop['componentes'] as $key => &$component) {
                $component = $this->extractSpecifications($component);
            }
        }
        return $decodedContent;
    }


    protected function extractSpecifications($componentName){
        $patterns=[
            '/CPU\s*/i',
            '/Processador\s*/i',
            '/Memória\s*/i',
            '/Fonte\s*/i',
            '/PLACA DE VIDEO\s*/i',
            '/PLACA MAE\s*/i',
            '/Placa Mãe \s*/i',
            '/COOLER PARA\s*/i',
            '/Water Cooler \s*/i',
            '/Water\s*/i',
        ];

        $cleanName = preg_replace($patterns, "", $componentName);
        return trim($cleanName);
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





