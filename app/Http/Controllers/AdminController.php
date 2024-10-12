<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    public function index()
    {
        // Busca todos os administradores no banco de dados
        $admins = Admin::paginate(10); // Paginação de 10 administradores por página

        // Retorna a view com os dados dos administradores
        return view('auth.admin.index', compact('admins'));
    }

    public function create(){
    return view('auth.admin.createAdmin');
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        event(new Registered($admin));

        Auth::login($admin);

        return redirect('/create-admin');

    }

    public function show(Request $request)
    {
        $search = $request->input('search');

        $results = Admin::where(function($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })->paginate(10)->appends(['search' => $search]); // Adicionando o parâmetro de busca à paginação

        return view('auth.admin.searchAdmin', compact('results'));

    }

    public function edit($id)
    {

        $admin = Admin::all()->find($id);
        return view('auth.admin.editAdmin', compact('admin'));
    }

    public function update(Request $request, $id)
    {

        $admin = Admin::findOrFail($id);

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = Hash::make($request->input('password'));

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        return redirect()->route('create-admin.index')->with('success', 'Admin atualizado com sucesso!');
    }

    public function destroy($id)
    {
        if (request()->has('_token')){
            $admins = Admin::findOrFail($id);
            $admins->delete();
            return redirect()->route('create-admin.index')->with('success', 'Admin apagado com sucesso!');
        }else{
            return redirect()->route('create-admin.index');
        }
    }
}

