<?php

namespace App\Http\Controllers\Api;

use App\DTO\CreateRenaveDTO;
use App\DTO\UpdateRenaveDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRenave;
use App\Http\Resources\RenaveResource;
use App\Models\Renave;
use Illuminate\Http\Request;
use App\Services\RenaveService;

class RenaveController extends Controller
{

    public function __construct(
        protected RenaveService $service

    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // paginator default ok
        // $renaves = Renave::paginate();
        // return RenaveResource::collection($renaves);

        $renaves = $this->service->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 3),
            filter: $request->filter,
        );

        return RenaveResource::collection($renaves->item())
                                   ->additional([
                                    'meta' => [
                                        'total' => $renaves->total(),
                                        'is_first_page' => $renaves->isFirstPage(),
                                        'is_last_page' => $renaves->isLastPage(),
                                        'current_page' => $renaves->currentPage(),
                                        'next_page' => $renaves->getNumberNextPage(),
                                        'previous_page' => $renaves->getNumberPreviousPage(),
                                    ]
                                ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateRenave $request)
    {
        $renave = $this->service->new(
            CreateRenaveDTO::makeFromRequest($request)
        );

        return new RenaveResource($renave);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$renave = $this->service->findOne($id)){
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        return new RenaveResource($renave);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRenave $request, string $id)
    {
        $renave = $this->service->update(
            UpdateRenaveDTO::makeFromRequest($request)
        );

        if (!$renave) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        return new RenaveResource($renave);
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
