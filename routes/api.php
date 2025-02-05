<?php

use App\Http\Controllers\Api\FornecedorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/fornecedores', FornecedorController::class);
