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
        return view('auth.admin.createAdmin');
    }

    public function create(){

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

        return redirect('/admin');

    }
}

