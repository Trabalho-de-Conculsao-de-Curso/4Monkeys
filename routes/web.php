<?php


use App\Http\Controllers\FreeConjuntoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ConjuntoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoftwareController;
use Illuminate\Support\Facades\Route;

/*Route::get('/home', function () {
    return view('home');
});*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/selecionar', [ConjuntoController::class, 'selecionar'])->name('home.selecionar');

});
    Route::post('/selecionar-free', [FreeConjuntoController::class, 'selecionar'])
    ->name('free.selecionar');

    Route::get('/', [ConjuntoController::class, 'create'])->name('home.create');

Route::resource('/produtos', ProdutoController::class);
Route::resource('/softwares', SoftwareController::class);




require __DIR__.'/auth.php';
