<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\GeminiLog;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Contagem de administradores para o "Primary Card" (exemplo)
        $adminCount = Admin::count();

        // Quantidade de usuários registrados para o "Warning Card"
        $userCount = User::count();

        // Quantidade de operações "criarConjunto" na tabela gemini_logs para o "Success Card"
        $createConjuntoCount = GeminiLog::where('operacao', 'criarConjunto')->count();

        $errorCount = GeminiLog::where('status', 'erro')->count();
        // Placeholder para o "Danger Card"
        $placeholderDanger = 0; // Pode ser substituído por uma contagem específica

        // Busca de logs para a tabela de logs com paginação
        $logs = GeminiLog::paginate(10);

        // Retorna a view com os dados dos cards e dos logs
        return view('admin.dashboard', compact('adminCount', 'userCount', 'createConjuntoCount', 'errorCount', 'logs'));
    }

}
