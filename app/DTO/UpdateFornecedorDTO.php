<?php

namespace App\DTO;

use App\Http\Requests\StoreUpdateFornecedor;

class UpdateFornecedorDTO
{
    public function __construct(
       public string $id,
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

    public static function makeFromRequest(StoreUpdateFornecedor $request): self
    {

        return new self(
            $request->id,
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
