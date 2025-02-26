<?php

use App\Http\Controllers\Admin\{SupportController};
use App\Http\Controllers\Admin\{FornecedorController};
use App\Http\Controllers\Admin\{RenaveController};
use App\Http\Controllers\Site\SiteController;
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
Route::delete('/renaves/{id}/', [RenaveController::class, 'destroy'])->name('renaves.destroy');
Route::put('/renaves/{id}', [RenaveController::class, 'update'])->name('renaves.update');
Route::get('/renaves/{id}/edit', [RenaveController::class, 'edit'])->name('renaves.edit');
Route::get('/renaves/create', [RenaveController::class, 'create'])->name('renaves.create');
Route::get('/renaves/{id}', [RenaveController::class, 'show'])->name('renaves.show');
Route::post('/renaves', [RenaveController::class, 'store'])->name('renaves.store');
Route::get('/renaves', [RenaveController::class, 'index'])->name('renaves.index');


Route::delete('/fornecedores/{id}/', [FornecedorController::class, 'destroy'])->name('fornecedores.destroy');
Route::put('/fornecedores/{id}', [FornecedorController::class, 'update'])->name('fornecedores.update');
Route::get('/fornecedores/{id}/edit', [FornecedorController::class, 'edit'])->name('fornecedores.edit');
Route::get('/fornecedores/create', [FornecedorController::class, 'create'])->name('fornecedores.create');
Route::get('/fornecedores/{id}', [FornecedorController::class, 'show'])->name('fornecedores.show');
Route::post('/fornecedores', [FornecedorController::class, 'store'])->name('fornecedores.store');
Route::get('/fornecedores', [FornecedorController::class, 'index'])->name('fornecedores.index');

Route::delete('/supports/{id}/', [SupportController::class, 'destroy'])->name('supports.destroy');
Route::put('/supports/{id}', [SupportController::class, 'update'])->name('supports.update');
Route::get('/supports/{id}/edit', [SupportController::class, 'edit'])->name('supports.edit');
Route::get('/supports/create', [SupportController::class, 'create'])->name('supports.create');
Route::get('/supports/{id}', [SupportController::class, 'show'])->name('supports.show');
Route::post('/supports', [SupportController::class, 'store'])->name('supports.store');
Route::get('/supports', [SupportController::class, 'index'])->name('supports.index');

Route::get('/contato', [SiteController::class, 'contact']);

Route::get('/', function () {
    return view('welcome');
});
