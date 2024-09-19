<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $softwares = Software::all();
        return view('softwares.index', [
            'softwares' => $softwares
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('softwares.createSoftware');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos, incluindo a imagem
        $request->validate([
            'nome' => 'required',
            'software_imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Valida a imagem
        ]);

        // Verifica se uma imagem foi enviada
        if ($request->hasFile('software_imagem')) {
            // Armazena a imagem na pasta 'public/images'
            $imagemPath = $request->file('software_imagem')->store('images', 'public');

            // Cria um novo registro no banco de dados
            $software = Software::create([
                'tipo' => $request->input('tipo'),
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
                'requisitos' => $request->input('requisitos'),
                'imagem' => $imagemPath, // Salva o caminho da imagem
            ]);

            if ($software) {
                return response()->redirectTo('/softwares');
            }
        }

        // Em caso de falha, retorna um erro ou redireciona
        return back()->withErrors(['message' => 'Erro ao salvar o software.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $search = $request->input('search');
        $results = Software::where('nome', 'like', "%$search%")
            ->orWhere('descricao', 'like', "%$search%")
            ->orWhere('requisitos', 'like', "%$search%")
            ->get();



        return view('softwares.searchSoftware', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Software $software)
    {
        return view('softwares.editSoftware', compact('software'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Busca o registro do software pelo ID
        $software = Software::findOrFail($id);

        // Validação dos dados, incluindo a imagem (opcional no caso do update)
        $request->validate([
            'nome' => 'required',
            'software_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // A imagem é opcional no update
        ]);

        if ($request->has('remover_imagem') && $software->imagem) {
            // Remove a imagem do diretório 'public/storage'
            Storage::disk('public')->delete($software->imagem);
            // Remove o caminho da imagem do banco de dados
            $software->imagem = null;
        }

        // Verifica se uma nova imagem foi enviada
        if ($request->hasFile('software_imagem')) {
            // Remove a imagem antiga se existir
            if ($software->imagem) {
                Storage::disk('public')->delete($software->imagem);
            }

            // Armazena a nova imagem
            $imagemPath = $request->file('software_imagem')->store('images', 'public');

            // Atualiza o caminho da imagem no banco de dados
            $software->imagem = $imagemPath;
        }

        // Atualiza os demais campos
        $software->nome = $request->input('nome');
        $software->tipo = $request->input('tipo');
        $software->descricao = $request->input('descricao');
        $software->requisitos = $request->input('requisitos');

        // Salva as alterações no banco de dados
        $software->save();

        return redirect()->route('softwares.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Software::FindOrFail($id);
        if (request()->has('_token')){
            $delete->delete();
            return redirect()->route('softwares.index');
        } else {
            return redirect()->route('softwares.index');
        }
    }
}
