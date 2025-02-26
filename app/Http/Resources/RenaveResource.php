<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identify' => $this->id,
            'razao_social' => $this->razao_social,
            'name' => strtoupper($this->name),
            'cnpj' => $this->cnpj,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'dt_created' => Carbon::make($this->created_at)->format('Y-m-d'),
        ];
    }
}
