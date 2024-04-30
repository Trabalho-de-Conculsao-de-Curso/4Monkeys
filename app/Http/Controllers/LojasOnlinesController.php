<?php

namespace App\Http\Controllers;

use App\Models\LojasOnlines;
use Illuminate\Http\Request;

class LojasOnlinesController extends Controller
{
    /**
     * Exibe uma listagem.
     */
    public function index()
    {
        $lojasOnlines = LojasOnlines::all();
        return view('lojasOnlines.index', [
            'lojasOnlines' => $lojasOnlines
        ]);
    }

    /**
     * Mostrar o formulário para criação de um novo recurso.
     */
    public function create()
    {
        return view('lojasOnlines.createLojasOnlines');
    }

    /**
     * Armazena um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'url' => 'required',
        
        ]);

        $lojasOnline = new LojasOnlines();
        $lojasOnline->nome = $request->input('nome');
        $lojasOnline->url = $request->input('url');
        $lojasOnline->save();

        // Verifique se a loja foi criada com sucesso
        if ($lojasOnline) {
            return redirect('/lojasOnlines')->with('success', 'Loja criada com sucesso.');
        } else {
            // Se houver algum erro ao criar a loja, redirecione de volta com uma mensagem de erro
            return back()->withInput()->with('error', 'Erro ao criar a loja.');
        }
    }

    /**
     * Exibir o recurso especificado.
     */
    public function show($id)
    {
        $lojasOnline = LojasOnlines::find($id);
        return view('lojasOnlines.showLojasOnlines', compact('lojasOnline'));
    }

    /**
     * Mostrar o formulário para edição do recurso especificado.
     */
    public function edit($id)
    {
        $lojasOnline = LojasOnlines::find($id);
        return view('lojasOnlines.editLojasOnlines', compact('lojasOnline'));
    }

    /**
     * Atualiza o recurso especificado no armazenamento.
     */
    public function update(Request $request, $id)
    {
        $lojasOnline = LojasOnlines::find($id);
        $lojasOnline->update($request->all());
        return redirect()->route('lojasOnlines.index');
    }

    /**
     * Remove o recurso especificado do armazenamento.
     */
    public function destroy($id)
    {
        $delete = LojasOnlines::findOrFail($id);
        if (request()->has('_token')) {
            $delete->delete();
        }
        return redirect()->route('lojasOnlines.index');
    }
}