<?php

namespace App\Http\Controllers;

use App\Models\Especificacoes;
use App\Models\Marca;
use App\Models\LojaOnline;
use App\Models\Preco;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function index()
    {
        $produtos = Produto::with('marca','especificacoes', 'preco', 'lojaOnline')->get();
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
            'marca_nome' => 'required',
            'marca_qualidade' => 'required',
            'marca_garantia' => 'required',
            'especificacoes_detalhes' => 'required',
            'preco_valor' => 'required',
            'preco_moeda' => 'required',
            'lojasOnline' => 'required',
            'urlLojaOnline' => 'required'
        ]);


        $marca = Marca::where('nome', $request->input('marca_nome'))->first();
        $especificacoes = Especificacoes::where('detalhes', $request->input('especificacoes_detalhes'))->first();
        $preco = Preco::where('valor', $request->input('preco_valor'))->first();
        $lojaOnline = LojaOnline::where('nome', $request->input('lojasOnline'))->first();

        // Cria uma nova Marca, Especificacoes, Preco
        $marca = new Marca();
        $marca->nome = $request->input('marca_nome');
        $marca->qualidade = $request->input('marca_qualidade');
        $marca->garantia = $request->input('marca_garantia');
        $marca->save();

        $especificacoes = new Especificacoes();
        $especificacoes->detalhes = $request->input('especificacoes_detalhes');
        $especificacoes->save();

        $preco = new Preco();
        $preco->valor = $request->input('preco_valor');
        $preco->moeda = $request->input('preco_moeda');
        $preco->save();

        $lojaOnline = new LojaOnline();
        $lojaOnline->nome = $request->input('lojasOnline');
        $lojaOnline->urlLoja = $request->input('urlLojaOnline');
        $lojaOnline->save();




        // Criar o produto associado à marca
        $produto = new Produto();
        $produto->nome = $request->input('nome');
        $produto->especificacoes_id = $especificacoes->id;
        $produto->marca_id = $marca->id;
        $produto->preco_id = $preco->id;
        $produto->loja_online_id = $lojaOnline->id;

        $produto->save();

        return redirect('/produtos');
    }


    public function show(Request $request)
    {
        $search = $request->input('search');

        $results = Produto::where(function($query) use ($search) {
            $query->where('nome', 'like', "%$search%")
                ->orWhere('preco', 'like', "%$search%")
                ->orWhere('lojasOnline', 'like', "%$search%");
        })
            ->orWhereHas('marca', function($query) use ($search) {
                $query->where('nome', 'like', "%$search%")
                    ->orWhere('qualidade', 'like', "%$search%")
                    ->orWhere('garantia', 'like', "%$search%");
            })

            ->orWhereHas('especificacoes', function($query) use ($search) {
                $query->where('detalhes', 'like', "%$search%");

            })
            ->get();

        return view('produtos.searchProduto', compact('results'));
    }


    public function edit($id)
    {
        $produto= Produto::with('marca','especificacoes', 'preco','lojaOnline')->find($id);
        return view('produtos.editProduto',compact('produto'));
    }


    public function update( Request $request,$id)
    {
        $produto = Produto::find($id);
        $produto->update($request->all());

        $marca = Marca::find($produto->marca_id);
        $marca->update([
            'nome' => $request->input('marca_nome'),
            'qualidade' => $request->input('marca_qualidade'),
            'garantia' => $request->input('marca_garantia'),

        ]);
        $especificacoes = Especificacoes::find($produto->especificacoes_id);
        $especificacoes->update([
            'detalhes' => $request->input('especificacoes_detalhes'),
        ]);

        $marca = Preco::find($produto->preco_id);
        $marca->update([
            'valor' => $request->input('preco_valor'),
            'moeda' => $request->input('preco_moeda'),

        ]);

        $lojaOnline = LojaOnline::find($produto->loja_online_id);
        $lojaOnline->update([
            'nome' => $request->input('lojasOnline'),
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
            $produto->marca()->delete();
            $produto->especificacoes()->delete();
            $produto->preco()->delete();
            $produto->lojaOnline()->delete();
            $produto->delete();
            return redirect()->route('produtos.index');
        } else {
            return redirect()->route('produtos.index');
        }
    }
}
