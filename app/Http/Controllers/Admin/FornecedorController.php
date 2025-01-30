<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateFornecedor;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index(Fornecedor $fornecedor)//injeção de dependencias do laravel
    {
        //$support = new Support();
        $fornecedores = $fornecedor->all();

        return view('admin/fornecedores/index', compact('fornecedores'));

    }

    public function show(string|int $id)
    {
        // Support::find($id);
        // Support::where('id', $id)->first();
        if (!$fornecedor = Fornecedor::find($id)){

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
        // $data = $request->all();
        $data = $request->validated();

        $fornecedor->create($data);

        return redirect()->route('fornecedores.index');

        // $support =  $support->create($data);//aqui objeto $data
        // $support::create($data);//aqui colection array

    }

    public function edit(Fornecedor $fornecedor, string|int $id)
    {

        if (!$fornecedor = $fornecedor->where('id', $id)->first())
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

    public function destroy(string|int $id)
    {

        if (!$fornecedor = Fornecedor::find($id)) {

            return back();

        }

        $fornecedor->delete();

        return redirect()->route('fornecedores.index');

    }



}
