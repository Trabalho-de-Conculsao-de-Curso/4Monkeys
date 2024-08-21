<?php

namespace App\Http\Controllers;

use App\Models\LojaOnline;
use App\Models\Preco;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function index()
    {
        $produtos = Produto::with( 'preco', 'lojaOnline')->paginate(10);
        return view('produtos.index', [
            'produtos' => $produtos
        ]);

    }

    public function create()
    {
        return view('produtos.createProduto');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'preco_valor' => 'required',
            'preco_moeda' => 'required',
            'urlLojaOnline' => 'required'
        ]);

        $preco = new Preco();
        $preco->valor = $request->input('preco_valor');
        $preco->moeda = $request->input('preco_moeda');
        $preco->save();

        $lojaOnline = new LojaOnline();
        $lojaOnline->urlLoja = $request->input('urlLojaOnline');
        $lojaOnline->save();

        // Criar o produto associado à marca
        $produto = new Produto();
        $produto->nome = $request->input('nome');
        $produto->preco_id = $preco->id;
        $produto->loja_online_id = $lojaOnline->id;

        $produto->save();

        return redirect('/produtos');
    }


    public function show(Request $request)
    {
        $search = $request->input('search');

        $results = Produto::where(function($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                ->orWhere('preco', 'like', "%$search%")
                ->orWhere('lojasOnline', 'like', "%$search%");
        })
            ->orWhereHas('lojaOnline', function($query) use ($search) {
                $query->where('nome', 'like', "%$search%")
                    ->orWhere('urlLoja', 'like', "%$search%");
            })
            ->orWhereHas('preco', function($query) use ($search) {
                $query->where('valor', 'like', "%$search%")
                    ->orWhere('moeda', 'like', "%$search%");
            })
            ->paginate(10)->appends(['search' => $search]); // Adicionando o parâmetro de busca à paginação

        return view('produtos.searchProduto', compact('results'));
    }


    public function edit($id)
    {
        $produto= Produto::with( 'preco','lojaOnline')->find($id);
        return view('produtos.editProduto',compact('produto'));
    }


    public function update( Request $request,$id)
    {
        $produto = Produto::find($id);
        $produto->update($request->all());


        $marca = Preco::find($produto->preco_id);
        $marca->update([
            'valor' => $request->input('preco_valor'),
            'moeda' => $request->input('preco_moeda'),

        ]);

        $lojaOnline = LojaOnline::find($produto->loja_online_id);
        $lojaOnline->update([
            'urlLoja' => $request->input('urlLojaOnline'),
        ]);


        return redirect()->route('produtos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        if (request()->has('_token')){
            $produto = Produto::findOrFail($id);
            $produto->preco()->delete();
            $produto->lojaOnline()->delete();
            $produto->delete();
            return redirect()->route('produtos.index');
        } else {
            return redirect()->route('produtos.index');
        }
    }
}
