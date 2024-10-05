<?php

namespace App\Http\Controllers;

use App\Models\Software;
use App\Models\Conjunto;
use Illuminate\Http\Request;

class ConjuntoLocal extends Controller
{
    public function getConjuntoProdutos(Request $request)
    {
        // Validação: garantir que 3 softwares foram selecionados
        $request->validate([
            'softwares' => 'required|array|min:3|max:3',
            'softwares.*' => 'exists:softwares,id'
        ]);

        // Receber os IDs dos softwares selecionados
        $softwareIds = $request->input('softwares');

        // Encontra o software com o maior peso
        $softwareMaisPesado = Software::whereIn('id', $softwareIds)
            ->orderBy('peso', 'desc')
            ->first();

        if (!$softwareMaisPesado) {
            return response()->json(['message' => 'Nenhum software encontrado'], 404);
        }

        // Identificar o conjunto relacionado ao software mais pesado
        $conjunto = $softwareMaisPesado->conjuntos()->first();

        if (!$conjunto) {
            return response()->json(['message' => 'Nenhum conjunto encontrado para o software mais pesado'], 404);
        }

        // Buscar os produtos relacionados ao conjunto
        $produtos = $conjunto->produtos()->get();

        // Opcional: Redirecionar para uma página ou retornar os dados como JSON
        return response()->json([
            'software' => $softwareMaisPesado,
            'conjunto' => $conjunto,
            'produtos' => $produtos,
        ]);
    }
}
