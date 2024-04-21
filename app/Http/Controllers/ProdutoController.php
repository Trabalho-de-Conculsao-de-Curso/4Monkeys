<?php

namespace App\Http\Controllers;

use App\Models\Marca;
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
            'lojasOnline' => 'required'
        ]);

        $produto = new Produto();
        $produto ->nome = $request ->input('nome');
        $produto ->marca = $request ->input('marca');
        $produto ->especificacoes = $request ->input('especificacoes');
        $produto ->preco = $request ->input('preco');
        $produto ->lojasOnline = $request ->input('lojasOnline');
        $produto ->save();

        $marca = new Marca();
        $marca->nome = $request->input('marca');
        $marca->produto_id = $produto->id;
        $marca->save();

        // Verifique se o produto foi criado com sucesso
        if ($produto) {
            return redirect('/produtos')->with('success', 'Produto criado com sucesso.');
        } else {
            // Se houver algum erro ao criar o produto, redirecione de volta com uma mensagem de erro
            return back()->withInput()->with('error', 'Erro ao criar o produto.');
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
