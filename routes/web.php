<?php
use App\Http\Controllers\TaxonController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [TaxonController::class, 'obtenerDatos'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/form', [FormController::class, 'index'])->name('form.index');
Route::get('/buscar-especie', [FormController::class, 'buscar'])->name('especies.buscar');


Route::post('/guardar_seccion', [FormController::class, 'guardarSeccion']);
Route::get('/obtener-sinonimos', [FormController::class, 'obtenerSinonimos']);
Route::get('/editar-ficha/{id}', [FormController::class, 'editarFicha'])->name('form.edit');
Route::put('/actualizar_seccion/{id}', [FormController::class, 'guardarSeccion']);

Route::get('/obtener-municipios/{nombreEdo}', [FormController::class, 'obtenerMunicipios']);



require __DIR__.'/auth.php';
