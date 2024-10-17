<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoftwareRequest;
use App\Http\Requests\UpdateSoftwareRequest;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\RequisitoSoftware;

class SoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $search = $request->input('search'); // Obtém o parâmetro de busca

    if ($search) {
        // Filtra os softwares com base no nome, descrição ou peso
        $softwares = Software::where('nome', 'like', "%$search%")
            ->orWhere('descricao', 'like', "%$search%")
            ->orWhere('peso', 'like', "%$search%")
            ->with('requisitos')
            ->get();
    } else {
        // Se não houver pesquisa, obtém todos os softwares
        $softwares = Software::with('requisitos')->get();
    }

    return view('softwares.index', [
        'softwares' => $softwares,
        'search' => $search // Envia o termo de busca para a view
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $software = Software::findOrFail($id);

        // Busca os requisitos de hardware relacionados
        $requisitos = [
            'Minimo' => RequisitoSoftware::where('software_id', $software->id)->where('requisito_nivel', 'Minimo')->first(),
            'Medio' => RequisitoSoftware::where('software_id', $software->id)->where('requisito_nivel', 'Medio')->first(),
            'Recomendado' => RequisitoSoftware::where('software_id', $software->id)->where('requisito_nivel', 'Recomendado')->first()
        ];

        // Passa o software e os requisitos para a view
        return view('softwares.editSoftware', compact('software', 'requisitos'));
    }



    public function store(StoreSoftwareRequest $request)
    {
        // Verifica se uma imagem foi enviada
        if ($request->hasFile('software_imagem')) {
            // Armazena a imagem na pasta 'public/images'
            $imagemPath = $request->file('software_imagem')->store('images', 'public');

            // Cria um novo registro no banco de dados
            $software = Software::create([
                'tipo' => $request->input('tipo'),
                'nome' => $request->input('nome'),
                'descricao' => $request->input('descricao'),
                'peso' => $request->input('peso'),
                'imagem' => $imagemPath, // Salva o caminho da imagem
            ]);

            if ($software) {
                // Criação dos requisitos mínimo, médio e recomendado para esse software
                $requisitos = [
                    [
                        'software_id' => $software->id,
                        'requisito_nivel' => 'Minimo',
                        'cpu' => $request->input('cpu_min'),
                        'gpu' => $request->input('gpu_min'),
                        'ram' => $request->input('ram_min'),
                        'placa_mae' => $request->input('placa_mae_min'),
                        'ssd' => $request->input('ssd_min'),
                        'cooler' => $request->input('cooler_min'),
                        'fonte' => $request->input('fonte_min')
                    ],
                    [
                        'software_id' => $software->id,
                        'requisito_nivel' => 'Medio',
                        'cpu' => $request->input('cpu_med'),
                        'gpu' => $request->input('gpu_med'),
                        'ram' => $request->input('ram_med'),
                        'placa_mae' => $request->input('placa_mae_med'),
                        'ssd' => $request->input('ssd_med'),
                        'cooler' => $request->input('cooler_med'),
                        'fonte' => $request->input('fonte_med')
                    ],
                    [
                        'software_id' => $software->id,
                        'requisito_nivel' => 'Recomendado',
                        'cpu' => $request->input('cpu_rec'),
                        'gpu' => $request->input('gpu_rec'),
                        'ram' => $request->input('ram_rec'),
                        'placa_mae' => $request->input('placa_mae_rec'),
                        'ssd' => $request->input('ssd_rec'),
                        'cooler' => $request->input('cooler_rec'),
                        'fonte' => $request->input('fonte_rec')
                    ],
                ];

                // Insere os requisitos no banco de dados
                foreach ($requisitos as $requisito) {
                    RequisitoSoftware::create($requisito);
                }

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
            ->orWhere('peso', 'like', "%$search%")
            ->get();

        return view('softwares.searchSoftware', compact('results'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSoftwareRequest $request, $id)
    {
        // Busca o registro do software pelo ID
        $software = Software::findOrFail($id);


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
        $software->peso = $request->input('peso');

        // Salva as alterações no banco de dados
        $software->save();

        $requisitos = [
            'Minimo' => [
                'cpu' => $request->input('cpu_min'),
                'gpu' => $request->input('gpu_min'),
                'ram' => $request->input('ram_min'),
                'placa_mae' => $request->input('placa_mae_min'),
                'ssd' => $request->input('ssd_min'),
                'cooler' => $request->input('cooler_min'),
                'fonte' => $request->input('fonte_min')
            ],
            'Medio' => [
                'cpu' => $request->input('cpu_med'),
                'gpu' => $request->input('gpu_med'),
                'ram' => $request->input('ram_med'),
                'placa_mae' => $request->input('placa_mae_med'),
                'ssd' => $request->input('ssd_med'),
                'cooler' => $request->input('cooler_med'),
                'fonte' => $request->input('fonte_med')
            ],
            'Recomendado' => [
                'cpu' => $request->input('cpu_rec'),
                'gpu' => $request->input('gpu_rec'),
                'ram' => $request->input('ram_rec'),
                'placa_mae' => $request->input('placa_mae_rec'),
                'ssd' => $request->input('ssd_rec'),
                'cooler' => $request->input('cooler_rec'),
                'fonte' => $request->input('fonte_rec')
            ],
        ];

        // Atualiza ou cria os requisitos no banco de dados
        foreach ($requisitos as $nivel => $requisito) {
            RequisitoSoftware::updateOrCreate(
                ['software_id' => $software->id, 'requisito_nivel' => $nivel],
                $requisito
            );
        }

        return redirect()->route('softwares.index')->with('success', 'Software atualizado com sucesso!');
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
