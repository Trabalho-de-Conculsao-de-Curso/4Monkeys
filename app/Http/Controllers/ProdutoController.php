<?php

namespace App\Http\Controllers;

use App\Models\CustomLog;
use App\Models\Estoque;
use App\Models\LojaOnline;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    protected $custom_log;

    public function __construct(CustomLog $custom_log)
    {
        $this->custom_log = $custom_log;
    }

    public function index()
    {
        try {
            $produtos = Produto::with('lojaOnline')->paginate(10000);
        } catch (\Exception $e) {
            $this->custom_log->create([
                'descricao' => $e->getMessage(),
                'operacao' => 'index',
                'user_id' => auth()->id() ?? 1,
            ]);
            $produtos = [];
        }

        return view('produtos.index', ['produtos' => $produtos]);
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

        try {
            $lojaOnline = LojaOnline::create([
                'valor' => $request->input('preco_valor'),
                'moeda' => $request->input('preco_moeda'),
                'urlLoja' => $request->input('urlLojaOnline'),
            ]);

            $produto = Produto::create([
                'nome' => $request->input('nome'),
                'disponibilidade' => $request->input('disponibilidade'),
                'loja_online_id' => $lojaOnline->id,
            ]);

            if ($produto->disponibilidade == 1) {
                Estoque::create(['produto_id' => $produto->id]);
            }

            $this->custom_log->create([
                'descricao' => "Produto criado: {$produto->nome}",
                'operacao' => 'create',
                'user_id' => auth()->id() ?? 1,
            ]);

            return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            $this->custom_log->create([
                'descricao' => $e->getMessage(),
                'operacao' => 'store',
                'user_id' => auth()->id() ?? 1,
            ]);

            return back()->withErrors('Erro ao criar o produto.');
        }
    }

    public function show(Request $request)
    {
        $search = $request->input('search');

        $results = Produto::where(function($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                ->orWhere('nome', 'like', "%$search%");
        })
            ->orWhereHas('lojaOnline', function($query) use ($search) {
                $query->where('urlLoja', 'like', "%$search%")
                    ->orWhere('valor', 'like', "%$search%")
                    ->orWhere('moeda', 'like', "%$search%");
            })
            ->paginate(10)->appends(['search' => $search]);

        return view('produtos.searchProduto', compact('results'));
    }

    public function edit($id)
    {
        try {
            $produto = Produto::with('lojaOnline')->findOrFail($id);
            return view('produtos.editProduto', compact('produto'));
        } catch (\Exception $e) {
            $this->custom_log->create([
                'descricao' => $e->getMessage(),
                'operacao' => 'edit',
                'user_id' => auth()->id() ?? 1,
            ]);

            return redirect()->route('produtos.index')->withErrors('Erro ao acessar o produto para edição.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $produto = Produto::findOrFail($id);
            $oldValues = $produto->getAttributes();

            $produto->update($request->all());
            $newValues = $produto->getAttributes();
            $this->logChanges($oldValues, $newValues, 'update', $id);

            $lojaOnline = LojaOnline::find($produto->loja_online_id);
            $oldLojaValues = $lojaOnline->getAttributes();

            $lojaOnline->update([
                'urlLoja' => $request->input('urlLojaOnline'),
                'valor' => $request->input('preco_valor'),
                'moeda' => $request->input('preco_moeda'),
            ]);

            $newLojaValues = $lojaOnline->getAttributes();
            $this->logChanges($oldLojaValues, $newLojaValues, 'update', $id, 'loja online');

            $this->updateEstoque($produto);

            return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            $this->custom_log->create([
                'descricao' => $e->getMessage(),
                'operacao' => 'update',
                'user_id' => auth()->id() ?? 1,
            ]);

            return back()->withErrors('Erro ao atualizar o produto.');
        }
    }

    public function destroy($id)
    {
        try {
            $produto = Produto::findOrFail($id);

            // Excluir a loja online associada
            $produto->lojaOnline()->delete();

            // Excluir o produto
            $produto->delete();

            $this->custom_log->create([
                'descricao' => "Produto excluído: {$produto->nome}",
                'operacao' => 'destroy',
                'user_id' => auth()->id() ?? 1,
            ]);

            return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            $this->custom_log->create([
                'descricao' => $e->getMessage(),
                'operacao' => 'destroy',
                'user_id' => auth()->id() ?? 1,
            ]);

            return back()->withErrors('Erro ao excluir o produto.');
        }
    }

    private function logChanges($oldValues, $newValues, $operation, $id, $entity = 'produto')
    {
        $changes = [];

        foreach ($newValues as $key => $newValue) {
            if ($key !== 'updated_at' && array_key_exists($key, $oldValues) && $oldValues[$key] != $newValue) {
                $changes[$key] = ['old' => $oldValues[$key], 'new' => $newValue];
            }
        }

        if (!empty($changes)) {
            $this->custom_log->create([
                'descricao' => json_encode(['id' => $id, 'entity' => $entity, 'changes' => $changes]),
                'operacao' => $operation,
                'user_id' => auth()->id() ?? 1,
            ]);
        }
    }

    private function updateEstoque($produto)
    {
        if ($produto->disponibilidade == 1) {
            Estoque::firstOrCreate(['produto_id' => $produto->id]);
        } else {
            Estoque::where('produto_id', $produto->id)->delete();
        }
    }
}
