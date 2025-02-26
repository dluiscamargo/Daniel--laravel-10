<?php

use App\Http\Controllers\Api\FornecedorController;
use App\Http\Controllers\Api\RenaveController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\Renave\RenaveController;


Route::apiResource('/fornecedores', FornecedorController::class);
Route::apiResource('/renaves', RenaveController::class);
// Route::apiResource('/renave', RenaveController::class);

/* Renave - gird cliente & veiculo & relatorio planilha livewire*/
// Route::get('/renave', [RenaveController::class, 'index'])->name('renave.index');
// Route::get('/renave/create', [RenaveController::class, 'create'])->name('renave.create');
// Route::post('/renave/store', [RenaveController::class, 'store'])->name('renave.store');
// Route::get('/renave/relatorio/extrato/export', [RenaveController::class, 'exportExtrato'])->name('relatorio.extrato-renave.export');
