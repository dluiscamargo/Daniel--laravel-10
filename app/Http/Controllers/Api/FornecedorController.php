<?php

namespace App\Http\Controllers\Api;

use App\DTO\CreateFornecedorDTO;
use App\DTO\UpdateFornecedorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFornecedor;
use App\Http\Resources\FornecedorResource;
use App\Models\Fornecedor;
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
    public function index(Request $request)
    {
        // paginator default ok
        // $fornecedores = Fornecedor::paginate();
        // return FornecedorResource::collection($fornecedores);


        $fornecedores = $this->service->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 3),
            filter: $request->filter,
        );


        return FornecedorResource::collection($fornecedores->item())
                                   ->additional([
                                    'meta' => [
                                        'total' => $fornecedores->total(),
                                        'is_first_page' => $fornecedores->isFirstPage(),
                                        'is_last_page' => $fornecedores->isLastPage(),
                                        'current_page' => $fornecedores->currentPage(),
                                        'next_page' => $fornecedores->getNumberNextPage(),
                                        'previous_page' => $fornecedores->getNumberPreviousPage(),
                                    ]
                                ]);
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
        if (!$fornecedor = $this->service->findOne($id)){
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        return new FornecedorResource($fornecedor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateFornecedor $request, string $id)
    {
        $fornecedor = $this->service->update(
            UpdateFornecedorDTO::makeFromRequest($request)
        );

        if (!$fornecedor) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        return new FornecedorResource($fornecedor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$this->service->findOne($id)){
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        $this->service->delete($id);

        return response()->json([
            'No Content' => 'No Content'
        ], 204);
    }
}
