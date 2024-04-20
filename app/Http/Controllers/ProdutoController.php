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
    public function show(Request $request)
    {
        $search = $request->input('search');

        $results = Produto::where(function($query) use ($search) {
            $query->where('nome', 'like', "%$search%")
                ->orWhere('marca', 'like', "%$search%")
                ->orWhere('especificacoes', 'like', "%$search%")
                ->orWhere('lojasOnline', 'like', "%$search%");
            // Adicione mais campos aqui conforme necessário
        })->get();
        return view('produtos.searchProduto', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produto= Produto::find($id);
        return view('produtos.editProduto',compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request,$id)
    {
        $produto = Produto::find($id);
        $produto->update($request->all());
        return redirect()->route('produtos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Produto::FindOrFail($id);
        if (request()->has('_token')){
            $delete->delete();
            return redirect()->route('produtos.index');
        } else {
            return redirect()->route('produtos.index');
        }
    }
}
