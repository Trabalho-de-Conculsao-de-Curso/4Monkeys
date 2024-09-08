<?php

namespace App\Http\Controllers;

use App\Models\LojaOnline;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function index()
    {
        $produtos = Produto::with(  'lojaOnline')->paginate(10);
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
            'urlLojaOnline' => 'required',
            'disponibilidade' => 'required',
        ]);


        $lojaOnline = new LojaOnline();
        $lojaOnline->valor =$request->input('preco_valor');
        $lojaOnline->moeda =$request->input('preco_moeda');
        $lojaOnline->urlLoja = $request->input('urlLojaOnline');
        $lojaOnline->save();

        // Criar o produto associado à marca
        $produto = new Produto();
        $produto->nome = $request->input('nome');
        $produto->disponibilidade = $request->input('disponibilidade');
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
                    ->orWhere('urlLoja', 'like', "%$search%")
                    ->orWhere('valor', 'like', "%$search%")
                    ->orWhere('moeda', 'like', "%$search%");
            })
            ->paginate(10)->appends(['search' => $search]); // Adicionando o parâmetro de busca à paginação

        return view('produtos.searchProduto', compact('results'));
    }


    public function edit($id)
    {
        $produto= Produto::with( 'lojaOnline')->find($id);
        return view('produtos.editProduto',compact('produto'));
    }


    public function update( Request $request,$id)
    {
        $produto = Produto::find($id);
        $produto->disponibilidade = $request->input('disponibilidade');
        $produto->update($request->all());


        $lojaOnline = LojaOnline::find($produto->loja_online_id);
        $lojaOnline->update([
            'urlLoja' => $request->input('urlLojaOnline'),
            'valor' => $request->input('preco_valor'),
            'moeda' => $request->input('preco_moeda')
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
            $produto->lojaOnline()->delete();
            $produto->delete();
            return redirect()->route('produtos.index');
        } else {
            return redirect()->route('produtos.index');
        }
    }
}
