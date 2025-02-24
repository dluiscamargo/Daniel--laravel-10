<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreRenaveAutoAptidaoAtpve extends FormRequest
{
    protected $errorBag = 'storeRenaveAutoAptidaoAtpve';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'numero_crv'           => 'sometimes',
            'tipo_crv'             => 'sometimes',
            'codigo_seguranca_crv' => 'sometimes',
            'descricao_veiculo'    => 'sometimes',

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [

            'numero_crv'           => 'numero do documento CRV',
            'tipo_crv'             => 'Tipo CRV',
            'codigo_seguranca_crv' => 'código segurança CRV',
            'descricao_veiculo'    => 'descrição do veiculo',

        ];
    }

}
