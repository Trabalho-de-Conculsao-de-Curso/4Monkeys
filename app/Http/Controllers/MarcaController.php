<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::all();
        return view('marcas.index', [
            'marcas' => $marcas
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.createMarca');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required']);

        if(Marca::query()->create($request->all())){
            return response()->redirectTo('/marcas');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $search = $request->input('search');
        $results = Marca::where('nome','like',"%$search%")->get();
        return view('marcas.searchMarca', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $marca= Marca::find($id);
        return view('marcas.editMarca',compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);
        $marca->update($request->all());
        return redirect()->route('marcas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Marca::FindOrFail($id);
        if (request()->has('_token')){
            $delete->delete();
            return redirect()->route('marcas.index');
        } else {
            return redirect()->route('marcas.index');
        }
    }
}
