<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoftwareRequest;
use App\Http\Requests\UpdateSoftwareRequest;
use App\Models\CustomLog;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\RequisitoSoftware;

class SoftwareController extends Controller
{
    protected $custom_log;

    public function __construct(CustomLog $custom_log)
    {
        $this->custom_log = $custom_log;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $softwares = Software::with('requisitos')->get();
        } catch (\Exception $e) {
            Log::warning('Ocorreu o seguinte erro ao listar Software: ' . $e->getMessage());
            $softwares = []; // Definindo $softwares como vazio caso ocorra erro
        }
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $software = Software::findOrFail($id);
            Log::alert('Acessou para editar Software: ' . $id);

            // Busca os requisitos de hardware relacionados
            $requisitos = [
                'Minimo' => RequisitoSoftware::where('software_id', $software->id)->where('requisito_nivel', 'Minimo')->first(),
                'Medio' => RequisitoSoftware::where('software_id', $software->id)->where('requisito_nivel', 'Medio')->first(),
                'Recomendado' => RequisitoSoftware::where('software_id', $software->id)->where('requisito_nivel', 'Recomendado')->first()
            ];

            return view('softwares.editSoftware', compact('software', 'requisitos'));

        } catch (\Exception $e) {
            // Registra log personalizado em caso de erro
            $this->custom_log->create([
                'descricao' => $e->getMessage(),
                'operacao' => 'edit',
                'user_id' => auth()->id() ?? 1, // Pega o ID do usuário autenticado ou '1' como fallback
            ]);

            return redirect()->route('softwares.index')->with('error', 'Erro ao acessar o software para edição.');
        }
    }

    public function store(StoreSoftwareRequest $request)
    {
        try {
            if ($request->hasFile('software_imagem')) {
                // Armazenamento da imagem
                $imagemPath = $request->file('software_imagem')->store('images', 'public');

                // Criação do software
                $software = Software::create([
                    'tipo' => $request->input('tipo'),
                    'nome' => $request->input('nome'),
                    'descricao' => $request->input('descricao'),
                    'peso' => $request->input('peso'),
                    'imagem' => $imagemPath,
                ]);

                // Log de criação do software
                $this->custom_log->create([
                    'descricao' => 'Software criado: ' . $software->nome,
                    'operacao' => 'create',
                    'user_id' => auth()->id() ?? 1,
                ]);

                // Criação dos requisitos
                $this->createRequisitos($request, $software->id);

                return redirect()->route('softwares.index')->with('success', 'Software cadastrado com sucesso!');
            }

            return back()->withErrors(['message' => 'Imagem obrigatória para o cadastro.']);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar o software: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Erro ao salvar o software.']);
        }
    }

    public function update(UpdateSoftwareRequest $request, $id)
    {
        // Busca o registro do software pelo ID
        $software = Software::findOrFail($id);
        $oldData = $software->toArray(); // Captura os dados antigos antes da atualização

        // Log da alteração de imagem
        if ($request->has('remover_imagem') && $software->imagem) {
            Storage::disk('public')->delete($software->imagem);
            $software->imagem = null;
        }

        // Verifica se uma nova imagem foi enviada
        if ($request->hasFile('software_imagem')) {
            if ($software->imagem) {
                Storage::disk('public')->delete($software->imagem);
            }
            $imagemPath = $request->file('software_imagem')->store('images', 'public');
            $software->imagem = $imagemPath;
        }

        // Atualiza os campos
        $software->nome = $request->input('nome');
        $software->tipo = $request->input('tipo');
        $software->descricao = $request->input('descricao');
        $software->peso = $request->input('peso');

        // Salva as alterações no banco de dados
        $software->save();

        // Log de alterações
        $newData = $software->toArray();
        $this->logChanges($oldData, $newData, 'update', $software->id);

        // Atualiza os requisitos
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
            $oldRequisito = RequisitoSoftware::where('software_id', $software->id)
                ->where('requisito_nivel', $nivel)
                ->first();

            RequisitoSoftware::updateOrCreate(
                ['software_id' => $software->id, 'requisito_nivel' => $nivel],
                $requisito
            );

            // Se o requisito já existia, logue as alterações
            if ($oldRequisito) {
                $this->logChanges($oldRequisito->toArray(), $requisito, 'update_requisito', $oldRequisito->id);
            }
        }

        return redirect()->route('softwares.index')->with('success', 'Software atualizado com sucesso!');
    }



    private function createRequisitos(Request $request, int $softwareId)
    {
        foreach (['Minimo', 'Medio', 'Recomendado'] as $nivel) {
            RequisitoSoftware::create([
                'requisito_nivel' => $nivel,
                'software_id' => $softwareId,
                'requisito' => $request->input('requisitos.' . strtolower($nivel)),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $software = Software::findOrFail($id);
            $softwareName = $software->nome;

            // Remove os requisitos relacionados
            RequisitoSoftware::where('software_id', $id)->delete();

            // Remove o software
            $software->delete();

            // Log da exclusão
            $this->custom_log->create([
                'descricao' => 'Software excluído: ' . $softwareName,
                'operacao' => 'destroy',
                'user_id' => auth()->id() ?? 1,
            ]);

            return redirect()->route('softwares.index')->with('success', 'Software excluído com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir o software: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Erro ao excluir o software.']);
        }
    }

    private function logChanges($oldData, $newData, $operacao)
    {
        $changes = [];

        // Comparar os dados antigos e novos
        foreach ($newData as $key => $value) {
            if (array_key_exists($key, $oldData) && $oldData[$key] != $value) {
                $changes[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value,
                ];
            }
        }

        // Remover o campo updated_at se estiver presente
        unset($changes['updated_at']);

        // Registrar no log se houver alterações
        if (!empty($changes)) {
            $this->custom_log->create([
                'descricao' => json_encode($changes),
                'operacao' => $operacao,
                'user_id' => 1,
            ]);
        }
    }



}
