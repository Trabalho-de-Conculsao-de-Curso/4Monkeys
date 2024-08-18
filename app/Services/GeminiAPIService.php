<?php
namespace App\Services;

use App\Models\LojaOnline;
use App\Models\Preco;
use App\Models\Produto;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Cache;
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
        if ($response=Gemini::geminiPro()->generateContent([$prompt])) {
            $recommendations = $this->parseResponse($response);
            return $this->calculateTotals($recommendations['desktops']);
        } else {
            Log::error('Erro ao chamar a API do Gemini: ');
            throw new \Exception('Erro ao chamar a API do Gemini');
        }
    }

    protected function generatePrompt(array $softwares, array $produtos)
    {

        $prompt = "Avalie os softwares selecionados e utilize como base para montar três desktops categorizados como bronze, silver e gold.\n\n";
        $prompt .= "Monte os desktops de forma que atendam aos requisitos mínimos dos softwares escolhidos, focando na custo-efetividade dos componentes utilizados.\n";
        $prompt .= "Crie um arquivo JSON mostrando todos os produtos necessários para que cada desktop atenda os requisitos das categorias de acordo com os produtos cadastrados no banco de dados.\n\n";
        $prompt .= "Certifique-se de que todos os componentes são compatíveis entre si e que cada desktop inclui os seguintes componentes essenciais: CPU, GPU, RAM, HD ou SSD, Fonte, MOTHERBOARD e Cooler.\n\n";
        $prompt .= "Retorne os dados estruturados no seguinte formato JSON, mantendo apenas os nomes dos componentes e removendo qualquer informação adicional:\n\n";
        $prompt .= "{ \"desktops\": [ { \"categoria\": \"1\", \"componentes\": { \"CPU\": \"Nome do Produto\", \"GPU\": \"Nome do Produto\", \"RAM\": \"Nome do Produto\", \"Fonte\": \"Nome do Produto\", \"MOTHERBOARD\": \"Nome do Produto\", \"Cooler\": \"Nome do Produto\", \"HD\": \"Nome do Produto\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS }, { \"categoria\": \"2\", \"componentes\": { \"CPU\": \"Nome do Produto\", \"GPU\": \"Nome do Produto\", \"RAM\": \"Nome do Produto\", \"Fonte\": \"Nome do Produto\", \"MOTHERBOARD\": \"Nome do Produto\", \"Cooler\": \"Nome do Produto\", \"HD\": \"Nome do Produto\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS SELECIONADOS }, { \"categoria\": \"3\", \"componentes\": { \"CPU\": \"Nome do Produto\", \"GPU\": \"Nome do Produto\", \"RAM\": \"Nome do Produto\", \"Fonte\": \"Nome do Produto\", \"MOTHERBOARD\": \"Nome do Produto\", \"Cooler\": \"Nome do Produto\", \"HD\": \"Nome do Produto\" }, \"total\": VALOR_DA_SOMA TOTAL_DOS ITENS SELECIONADOS } ] }\n\n";
        $prompt .= "Garanta a integridade e consistência de todas as informações.\n\n";
        $prompt .= "Softwares selecionados e seus requisitos:\n";

        foreach ($softwares as $software) {
            $prompt .= "- Nome: {$software['nome']}\n";
        }
        $prompt .= "Utilize APENAS os seguintes produtos disponíveis\n";
        $prompt .= "\nProdutos Disponíveis:\n";
        foreach ($produtos as $produto) {
            $preco = Preco::find($produto['preco_id'])->valor ?? 'N/A';
            $url = LojaOnline::find($produto['loja_online_id'])->url ?? 'URL não disponível';

            if ($preco !== 'N/A') {
                $prompt .= "- Nome: {$produto['nome']}, Preço: R$ {$preco}, URL: {$url}\n";
            } else {
                Log::warning("Preço não encontrado para o produto ID: {$produto['id']}");
                $prompt .= "- Nome: {$produto['nome']}, Preço: N/A\n";
            }
            $prompt .= "\nGaranta que seja passado exatamente o mesmo nome do array\n";
            $prompt .= "- Nome: {$produto['nome']}, Preço: R$ {$preco}\n";
        }


        $prompt .= "\nDicas adicionais:\n";
        $prompt .= "- Priorize componentes com melhor relação custo-efetividade.\n";
        $prompt .= "- Para a categoria 1, escolha componentes que atendam aos requisitos mínimos dos softwares com o menor custo.\n";
        $prompt .= "- Para a categoria 2, escolha componentes que ofereçam um bom equilíbrio entre desempenho e custo.\n";
        $prompt .= "- Para a categoria 3, escolha componentes de alta performance, mas ainda mantendo a preocupação com o custo-efetividade.\n";

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

    public function findProductIdBySimilarity($componentName)
    {
        // Busca pelo produto diretamente pelo nome
        $produto = Produto::where('nome', 'like', "%$componentName%")->first();

        // Se o produto não for encontrado, buscar por nomes semelhantes
        if (!$produto) {
            $possiveisProdutos = Produto::all(); // Carrega todos os produtos para comparar similaridade

            if ($possiveisProdutos->isNotEmpty()) {
                // Usar a função Levenshtein para encontrar o nome mais parecido
                $produto = $possiveisProdutos->sortBy(function ($produto) use ($componentName) {
                    return levenshtein($componentName, $produto->nome);
                })->first();

                if ($produto) {
                    Log::info("Produto mais semelhante encontrado: '{$produto->nome}'");
                } else {
                    Log::warning("Nenhum produto semelhante encontrado para: $componentName");
                }
            }
        }

        return $produto ? $produto->id : null;
    }



    public function calculateTotals($desktops)
    {
        $totals = [
            'bronze' => 0,
            'silver' => 0,
            'gold' => 0,
        ];


        foreach ($desktops as &$desktop) {
            $category = $desktop['categoria'];
            $components = $desktop['componentes'];
            $total = 0;

            foreach ($components as $componentName) {

                $productId = $this->findProductIdBySimilarity($componentName);

                if ($productId) {
                    $produto = Produto::find($productId);
                    $price = $produto->preco->valor;
                    Log::info("Produto: {$produto->nome}, Preço: $price");
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





