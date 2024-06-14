<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\ProdutoFinal;
use App\Models\Software;
use App\Services\GeminiAPIService;
use Illuminate\Http\Request;

class ProdutoFinalController extends Controller
{
    protected $geminiAPIService;

    public function __construct(GeminiAPIService $geminiAPIService)
    {
        $this->geminiAPIService = $geminiAPIService;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $softwares = Software::all();
        return view('home', compact('softwares'));
    }

    public function selecionar(Request $request)
    {
        $softwaresSelecionados = Software::find($request->input('softwares'));
        $produtos = Produto::all();

        // Preparar dados para enviar ao GeminiAPI
        $softwaresData = $softwaresSelecionados->toArray();
        $produtosData = $produtos->toArray();


        // Obter recomendações do GeminiAPI
        $response = $this->geminiAPIService->getRecommendations($softwaresData, $produtosData);
        dd($response);
        $recommendations = $response['choices']; // Acessar o array 'choices' da resposta


        // Melhorar logica
        // Aqui você deve processar as recomendações para criar os produtos finais
        $produtoFinals = [];
        foreach ($recommendations as $choice) {
            $categoria = $choice['category'];
            $produtoFinal = new ProdutoFinal();
            $produtoFinal->nome = 'Produto Final ' . ucfirst($categoria);
            $produtoFinal->categoria = $categoria;
            $produtoFinal->save();

            $produtosCategoria = Produto::whereIn('id', $choice['produto_ids'])->get();
            $produtoFinal->produtos()->attach($produtosCategoria);
            $produtoFinal->softwares()->attach($softwaresSelecionados);

            $produtoFinals[$categoria] = $produtoFinal;
        }

        return view('resultado', compact('produtoFinals'));
    }
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdutoFinal $produtoFinal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdutoFinal $produtoFinal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProdutoFinal $produtoFinal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdutoFinal $produtoFinal)
    {
        //
    }
}
