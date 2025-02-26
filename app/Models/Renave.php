<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renave extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
        'endereco',
        'numero',
        'complemento',
        'cidade',
        'uf'

    ];
}
