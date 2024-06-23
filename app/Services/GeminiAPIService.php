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
            return $this->parseResponse($response);
        }


    }

    protected function generatePrompt(array $softwares, array $produtos)
    {

        foreach ($softwares as $software) {
            $prompt = "- {$software['nome']}\n";
            $prompt .= "- {$software['descricao']}\n";

            // Gerar o prompt que será enviado para a API do Gemini
            $prompt .= "Avalie a descrição dos softwares selecionados e baseie-se nelas para a montagem dos desktops.\n\n";
            $prompt .= "Baseado nos seguintes produtos e suas especificações de acordo com os requisitos dos softwares, monte 3 desktops categorizados como bronze, silver e gold. Os requisitos de desempenho para cada categoria são:\n";
            $prompt .= "- Bronze: no mínimo 30 a 59 fps\n";
            $prompt .= "- Silver: no mínimo 60 a 119 fps\n";
            $prompt .= "- Gold: no mínimo 120 ou mais fps\n\n";
            //  $prompt .= "Crie outro Json mostrando todas as etapas realizadas durante esta pesquisa\n\n";
            $prompt .= "Certifique-se de que todos os componentes são compatíveis entre si e que cada desktop inclui os seguintes componentes essenciais: CPU, GPU, RAM, Fonte, MOTHERBOARD, Cooler, HD ou SSD.\n\n";
            $prompt .= "Retorne os dados estruturados no seguinte formato JSON:\n\n";
            $prompt .= "{ \"desktops\": [ { \"categoria\": \"bronze\", \"componentes\": { \"CPU\": \"Produto analisado\", \"GPU\": \"Produto analisado\", \"RAM\": \"Produto analisado\", \"Fonte\": \"Produto analisado\", \"MOTHERBOARD\": \"Produto analisado\", \"Cooler\": \"Produto analisado\", \"HD\": \"Produto analisado\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS }, { \"categoria\": \"silver\", \"componentes\": { \"CPU\": \"Produto analisado\", \"GPU\": \"Produto analisado\", \"RAM\": \"Produto analisado\", \"Fonte\": \"Produto analisado\", \"MOTHERBOARD\": \"Produto analisado\", \"Cooler\": \"Produto analisado\", \"HD\": \"Produto analisado\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS }, { \"categoria\": \"gold\", \"componentes\": { \"CPU\": \"Produto analisado\", \"GPU\": \"Produto analisado\", \"RAM\": \"Produto analisado\", \"Fonte\": \"Produto analisado\", \"MOTHERBOARD\": \"Produto analisado\", \"Cooler\": \"Produto analisado\", \"HD\": \"Produto analisado\" }, \"total\": VALOR_DA_SOMA_TOTAL_DOS_ITENS_SELECIONADOS } ] }\n\n";
            $prompt .= "Realize a soma dos preços da CPU, GPU, RAM, Fonte, MOTHERBOARD, Cooler, HD ou SSD de cada desktop (Bronze, Silver Gold) individualmente de acordo com os valores informados no cadastro do produto.\n";
            $prompt .= "Garanta a integridade e consistência de todas as informaçoes\n\n";
            $prompt .= "Softwares selecionados:\n";

        }

        $prompt .= "\nProdutos Disponíveis:\n";
        foreach ($produtos as $produto) {
            $produtoModel = Produto::find($produto['id']);
            $marcaNome = $produtoModel->marca->nome;
            $especificacoes = $produtoModel->especificacoes->detalhes;
            $preco = $produtoModel->preco->valor;

            $prompt .= "- Nome: {$produto['nome']}, Preço: {$preco}, Marca: {$marcaNome}, Especificações: {$especificacoes}\n";
        }

        $prompt .= "\nMonte os desktops bronze, silver e gold, garantindo que:\n";
        $prompt .= "- Bronze: execute os softwares selecionados com no mínimo 30 a 59 fps fps\n";
        $prompt .= "- Silver: execute os softwares selecionados com no mínimo 60 a 119 fps\n";
        $prompt .= "- Gold: execute os softwares selecionados com no mínimo 120 ou mais fps\n";
        $prompt .= "Cada desktop deve conter os componentes essenciais e compatíveis (CPU, GPU, RAM, Fonte, MOTHERBOARD, Cooler, HD ou SSD).\n";
        $prompt .= "Analise os preços de todos os produtos e calcule o preço total para cada categoria, calculando todos os itens CPU, GPU, RAM, Fonte, MOTHERBOARD, Cooler, HD ou SSD de cada desktop individualmente de acordo com os valores informados no cadastro do produto.\n";
        $prompt .= "Retorne a resposta em JSON no formato especificado acima.\n";

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
}
