<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use App\Models\Software;
use App\Services\FreeGeminiAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FreeConjuntoController extends Controller
{
    protected $FreeGeminiAPIService;

    public function __construct(FreeGeminiAPIService $FreeGeminiAPIService)
    {
        $this->FreeGeminiAPIService = $FreeGeminiAPIService;
    }



    public function selecionar(Request $request)
    {
        // Recuperar os softwares selecionados e todos os produtos disponíveis
        $softwaresSelecionados = Software::find($request->input('softwares'));
        $produtos = Estoque::with('produto')->get()->pluck('produto.nome');
        // Preparar os dados para enviar ao FreeGeminiAPIService
        $softwaresData = $softwaresSelecionados->toArray();
        $produtosData = $produtos->toArray();

        try {
            // Obter as recomendações da API
            $recommendations = $this->FreeGeminiAPIService->getRecommendations($softwaresData, $produtosData);
            Log::info('Resposta bruta da API Free Gemini:', ['response' => $recommendations]);

            // Retornar a view com os dados das recomendações, sem salvar no banco
            //return response()->json(['desktops' => $recommendations]);

            return view('resultado_free', ['desktops' => $recommendations]);

        } catch (\Exception $e) {
            // Logar qualquer erro e redirecionar de volta com uma mensagem de erro
            Log::error("Erro ao processar: " . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao processar a seleção.');
        }
    }
}
