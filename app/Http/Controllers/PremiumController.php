<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::paginate(10); // Paginar 10 usuários por página
        return view('premium.index', compact('usuarios'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('premium.createPremium');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'email' => 'required',
            'situacao' => 'required',
        ]);
        $usuario = new User();
        $usuario->nome = $request->input('nome');
        $usuario->email = $request->input('email');
        $usuario->situacao = $request->input('situacao');
        $usuario->password = $request->input('password');
        $usuario->save();

        return redirect()->route('premium.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $search = $request->input('search');

        $results = User::where(function($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                ->orWhere('nome', 'like', "%$search%")
                ->orWhere('situacao', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
        })
            ->paginate(10)->appends(['search' => $search]); // Adicionando o parâmetro de busca à paginação

        return view('premium.searchPremium', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuarios = User::all()->find($id);
        return view('premium.editPremium', compact('usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $usuarios = User::find($id);
        $usuarios->nome = $request->input('nome');
        $usuarios->email = $request->input('email');
        $usuarios->situacao = $request->input('situacao');
        $usuarios->password = $request->input('password');
        $usuarios->update($request->all());

        return redirect()->route('usuario-premium.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (request()->has('_token')){
            $usuarios = User::findOrFail($id);
            $usuarios->delete();
            return redirect()->route('usuario-premium.index');
        } else {
            return redirect()->route('usuario-premium.index');
        }
    }
}
