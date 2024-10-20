<?php

use App\Http\Controllers\ConjuntoLocalController;
use App\Http\Controllers\FreeConjuntoController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ConjuntoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*Route::get('/home', function () {
    return view('home');
});*/

Route::get('/dashboard', [ConjuntoController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/selecionar', [ConjuntoController::class, 'selecionar'])->name('home.selecionar');
});

Route::post('/selecionar-free', [FreeConjuntoController::class, 'selecionar'])->name('free.selecionar');
Route::get('/', [ConjuntoController::class, 'createFree'])->name('home.create');


Route::post('/conjunto-produtos', [ConjuntoLocalController::class, 'getConjuntoProdutos'])->name('conjunto.produtos');

Route::resource('/produtos', ProdutoController::class);
Route::resource('/softwares', SoftwareController::class);
Route::resource('/usuario-premium', PremiumController::class);

Route::get('/create-admin', [AdminController::class, 'create'])->name('auth.admin.index');
//Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/dashboard-admin', function () {
    return view('admin.dashboard'); // Substitua 'admin.dashboard' pelo nome da sua view
})->name('admin.dashboard');


/*Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('auth.admin.AdminDashboard');
});*/ //implementação da rota com o token

require __DIR__.'/auth.php';
