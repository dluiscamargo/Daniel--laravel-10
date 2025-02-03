<?php

namespace App\Http\Controllers\Admin;

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
        // dd($request);
        $fornecedores = $this->service->getAll($request->filter ?? '');

        return view('admin/fornecedores/index', compact('fornecedores'));

    }

    //old_ok
    // public function index(Fornecedor $fornecedor)
    // {
    //     $fornecedores = $fornecedor->all();

    //     return view('admin/fornecedores/index', compact('fornecedores'));

    // }

    public function show(string $id)
    {

        if (!$fornecedor = $this->service->findOne($request->id ?? '')){

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

        $data = $request->validated();

        $fornecedor->create($data);

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
        if (!$fornecedor = $fornecedor->find($id)){

            return back();

        }
        // outra forma de atualizar os dados para edição
        // $fornecedor->subject = $request->subject;
        // $fornecedor->body = $request->body;
        // $fornecedor->save();

        // $fornecedor->update($request->only([
        //     'subject', 'body'
        // ]));
        $fornecedor->update($request->validated());

        return redirect()->route('fornecedores.index');


    }

    public function destroy(string $id)
    {

        $this->service->delete($id);

        return redirect()->route('fornecedores.index');

    }



}
