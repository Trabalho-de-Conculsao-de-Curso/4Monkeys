<?php

namespace App\Http\Controllers;

use App\Models\LojasOnline;
use Illuminate\Http\Request;

class LojaOnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Lojas = LojaOnline::all();
        return view('LojaOnline.index', [
            'Lojas' => $Lojas
        ]);

    }
   
}