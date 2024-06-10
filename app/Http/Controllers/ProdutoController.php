<?php

namespace App\Http\Controllers;

use App\Models\Especificacoes;
use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function index()
    {
        $produtos = Produto::with('marca','especificacoes')->get();
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
            'preco' => 'required',
            'lojasOnline' => 'required'
        ]);


        $marca = Marca::where('nome', $request->input('marca_nome'))->first();
        $especificacoes = Especificacoes::where('detalhes', $request->input('especificacoes_detalhes'))->first();

        // Se a marca não existir, criar uma nova marca
        if (!$marca) {
            $marca = new Marca();
            $marca->nome = $request->input('marca_nome');
            $marca->qualidade = $request->input('marca_qualidade');
            $marca->garantia = $request->input('marca_garantia');
            $marca->save();
        }

        if (!$especificacoes) {
            $especificacoes = new Especificacoes();
            $especificacoes->detalhes = $request->input('especificacoes_detalhes');
            $especificacoes->save();
        }



        // Criar o produto associado à marca
        $produto = new Produto();
        $produto->nome = $request->input('nome');
        $produto->especificacoes_id = $especificacoes->id;
        $produto->preco = $request->input('preco');
        $produto->lojasOnline = $request->input('lojasOnline');
        $produto->marca_id = $marca->id;
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
        $produto= Produto::with('marca','especificacoes')->find($id);
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




        return redirect()->route('produtos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        if (request()->has('_token')){
            $produto = Produto::findOrFail($id);
            $produto->delete();
            $produto->marca()->delete();
            $produto->especificacoes()->delete();
            return redirect()->route('produtos.index');
        } else {
            return redirect()->route('produtos.index');
        }
    }
}
