<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CreateFornecedorDTO;
use App\DTO\UpdateFornecedorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFornecedor;
use App\Models\Fornecedor;
use App\Services\FornecedorService;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{

    public function __construct(
        protected FornecedorService $service

    ){}

    public function index(Request $request)
    {

        $fornecedores = $this->service->getAll($request->filter ?? '');
        // dd($fornecedores);
        return view('admin/fornecedores/index', compact('fornecedores'));

    }

    public function show(string $id)
    {

        if (!$fornecedor = $this->service->findOne($id)){

            return back();

        }

        return view('admin/fornecedores/show', compact('fornecedor'));

    }

    public function create()
    {

        return view('admin/fornecedores/create');
    }

    public function store(StoreUpdateFornecedor $request, Fornecedor $fornecedor)
    {
        $this->service->new(
            CreateFornecedorDTO::makeFromRequest($request)

        );

        return redirect()->route('fornecedores.index');

    }

    public function edit(string $id)
    {

        if (!$fornecedor = $this->service->findOne($id))
        {
            return back();

        }

        return view('admin/fornecedores.edit', compact('fornecedor'));


    }

    public function update(StoreUpdateFornecedor $request, Fornecedor $fornecedor, string $id)
    {

        $fornecedor = $this->service->update(
            UpdateFornecedorDTO::makeFromRequest($request)

        );

        if (!$fornecedor){

            return back();

        }

        return redirect()->route('fornecedores.index');


    }

    public function destroy(string $id)
    {

        $this->service->delete($id);

        return redirect()->route('fornecedores.index');

    }



}
