<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Agenda;
use App\Http\Livewire\Reserva;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::view('/aviso-privacidad', 'aviso-privacidad');
Route::view('/seguridad', 'seguridad');
Route::view('/condiciones-uso', 'condiciones-uso');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(["middleware" => "role:recepcionista,admin"],function () {
    Route::get('agenda', Agenda::class)->name('agenda');
    Route::get('/', Agenda::class)->name('/');
    Route::get('data/etiquetas',[DataController::class, 'getTags'])->name('data.etiquetas');

    
});

Route::view('/cita-confirmada', 'cita-confirmada');
Route::get('/reservar', Reserva::class)->name('reservar');

require __DIR__.'/auth.php';
