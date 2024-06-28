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


        $recommendations = $this->geminiAPIService->getRecommendations($softwaresData, $produtosData);



        $produtoFinals = [];

        foreach ($recommendations['desktops'] as $desktop) {
            $produtoFinal = new ProdutoFinal();
            $produtoFinal->nome = 'Produto Final ' . ucfirst($desktop['categoria']);
            $produtoFinal->categoria = $desktop['categoria'];
            $produtoFinal->preco_total = $desktop['total'];
            $produtoFinal->cpu = $desktop['componentes']['CPU'] ?? null;
            $produtoFinal->gpu = $desktop['componentes']['GPU'] ?? null;
            $produtoFinal->ram = $desktop['componentes']['RAM'] ?? null;
            $produtoFinal->hdd = $desktop['componentes']['HD'] ?? null;
            $produtoFinal->fonte = $desktop['componentes']['Fonte'] ?? null;
            $produtoFinal->placa_mae = $desktop['componentes']['MOTHERBOARD'] ?? null;
            $produtoFinal->cooler = $desktop['componentes']['Cooler'] ?? null;
            $produtoFinal->save();

            $produtoFinals[] = $produtoFinal;



        }

        return view('resultado', compact('produtoFinal'));
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
