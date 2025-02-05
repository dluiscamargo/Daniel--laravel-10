<?php

namespace App\Http\Controllers\Api;

use App\DTO\CreateFornecedorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFornecedor;
use App\Http\Resources\FornecedorResource;
use Illuminate\Http\Request;
use App\Services\FornecedorService;

class FornecedorController extends Controller
{

    public function __construct(
        protected FornecedorService $service

    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateFornecedor $request)
    {
        $fornecedor = $this->service->new(
            CreateFornecedorDTO::makeFromRequest($request)
        );

        return new FornecedorResource($fornecedor);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
