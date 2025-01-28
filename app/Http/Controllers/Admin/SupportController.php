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
        dd($supports);

        return view('admin/supports/index', compact('supports'));

    }
}
