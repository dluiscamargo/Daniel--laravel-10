<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateFornecedor extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'razao_social' => 'required|min:3|max:255|unique:fornecedors',

            'name' => [
                'required',
                'max:255',
            ],

            'cnpj' => [
                'required',
                'max:14',
            ],

            'email' => [
                'required',
                'email',
                'max:191',
            ],

            'telefone' => [
                'required',
                'max:15',
            ],

            'endereco' => [
                'required',
                'max:191',
            ],

            'numero' => [
                'required',
                'max:20',
            ],

            'complemento' => [
                'required',
                'max:191',
            ],

            'cidade' => [
                'required',
                'max:191',
            ],

            'uf' => [
                'required',
                'max:2',
            ],
        ];

        if ($this->method() === 'PUT') {
            $rules['razao_social'] = [
                    'required',
                    'min:3',
                    'max:255',
                    // "unique:fornecedors,razao_social,{$this->id},id",
                    Rule::unique('fornecedors')->ignore($this->id),
            ];

        }

        return $rules;
    }
}
