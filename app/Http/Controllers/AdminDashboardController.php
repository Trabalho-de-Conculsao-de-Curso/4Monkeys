<?php

namespace App\Http\Controllers;

use App\Models\Admin;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Busca todos os administradores no banco de dados
        $admins = Admin::paginate(10); // Paginação de 10 administradores por página

        // Retorna a view com os dados dos administradores
        return view('admin.dashboard', compact('admins'));
    }
}
