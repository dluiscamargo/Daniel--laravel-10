<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CreateRenaveDTO;
use App\DTO\UpdateRenaveDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRenave;
use App\Models\Renave;
use App\Services\RenaveService;
use Illuminate\Http\Request;

class RenaveController extends Controller
{

    public function __construct(
        protected RenaveService $service

    ){}

    public function index(Request $request)
    {

        $renaves = $this->service->paginate(
            page: $request->get('page', 1),
            totalPerPage: $request->get('per_page', 1),
            filter: $request->filter,
        );

        $filters = ['filter' => $request->get('filter', '')];

        return view('admin/renaves/index', compact('fornecedores', 'filters'));

    }

    public function show(string $id)
    {

        if (!$renave= $this->service->findOne($id)){

            return back();

        }

        return view('admin/renaves/show', compact('renave'));

    }

    public function create()
    {

        return view('admin/renaves/create');
    }

    public function store(StoreUpdateRenave $request, Renave $renave)
    {
        $this->service->new(
            CreateRenaveDTO::makeFromRequest($request)

        );

        return redirect()->route('fornecedores.index');

    }

    public function edit(string $id)
    {

        if (!$renave = $this->service->findOne($id))
        {
            return back();

        }

        return view('admin/renaves.edit', compact('renave'));


    }

    public function update(StoreUpdateRenave $request, Renave $renave, string $id)
    {

        $renave = $this->service->update(
            UpdateRenaveDTO::makeFromRequest($request)

        );

        if (!$renave){

            return back();

        }

        return redirect()->route('renaves.index');


    }

    public function destroy(string $id)
    {

        $this->service->delete($id);

        return redirect()->route('renaves.index');

    }



}
