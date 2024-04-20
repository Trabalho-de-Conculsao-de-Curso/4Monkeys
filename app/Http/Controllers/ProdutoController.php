<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos= Produto::all();
        return view('produtos.index', [
            'produtos' => $produtos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.createProduto');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'marca' => 'required',
            'especificacoes' => 'required',
            'preco' => 'required',
            'lojasOnline'=>'required'
        ]);

        $produto = Produto::create([
            'nome' => $request->input('nome'),
            'marca' => $request->input('marca'),
            'especificacoes' => $request->input('especificacoes'),
            'preco' => $request->input('preco'),
            'lojasOnline' => $request->input('lojasOnline'),
        ]);

        if($produto){
            // Redireciona após a conclusão
            return redirect('/produtos');
        } else {
            // Trate o erro, se a criação do produto falhar
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produto $produto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produto $produto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        //
    }
}
