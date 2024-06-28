<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Software;
use Illuminate\Http\Request;

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
        $request->validate([
            'nome' => 'required']);

        if(Software::query()->create($request->all())){
            return response()->redirectTo('/softwares');
        }
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
        $software = Software::find($id);
        $software->update($request->all());
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
