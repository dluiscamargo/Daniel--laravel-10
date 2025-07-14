<?php

namespace App\DTO;

use App\Http\Requests\StoreUpdateRenave;

class CreateRenaveDTO
{
    public function __construct(
       public string $name,
       public string $razao_social,
       public string $cnpj,
       public string $email,
       public string $telefone,
       public string $endereco,
       public string $numero,
       public string $complemento,
       public string $cidade,
       public string $uf,

    ){}

    public static function makeFromRequest(StoreUpdateRenave $request): self
    {

        return new self(
            $request->name,
            $request->razao_social,
            $request->cnpj,
            $request->email,
            $request->telefone,
            $request->endereco,
            $request->numero,
            $request->complemento,
            $request->cidade,
            $request->uf,
        );
    }
}
