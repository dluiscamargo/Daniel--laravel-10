<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Support $support)//injeção de dependencias do laravel
    {
        // $support = new Support();
        $supports = $support->all();
        // dd($supports);

        return view('admin/supports/index', compact('supports'));

    }

    public function create()
    {

        return view('admin/supports/create');
    }

    public function store(Request $request, Support $support)
    {
        $data = $request->all();
        $data['status'] = 'a';

        $support->create($data);

        return redirect()->route('supports.index');

        // $support =  $support->create($data);//aqui objeto $data
        // dd($support);
        // Support::create($data); //aqui colection array
        // dd($request->only(['subject', 'body']));
        // dd($request->body);
    }
}
