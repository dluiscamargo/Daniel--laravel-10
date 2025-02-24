<?php

namespace App\Models\Renave;

use App\Models\Negociacao\NegociacaoVeiculo;
use App\Models\Revenda\Revenda;
use App\Models\Veiculo\Veiculo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Veiculo\Combustivel;
use App\Http\Requests\UpdateIntegracaoRevendaRenaveAuto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class RenaveAptidaoAtpve extends Model
{
    protected $fillable = [
        'id',
        'revenda_id',
        'negociacao_id',
        'negociacao_cliente_id',
        'negociacao_veiculo_id',
        'revenda_integracao_id',
        'aptidao_veiculo_apto',
        'detalhe_aptidao',
        'motivos_para_nao_aptidao',
        'existemDebitos',
        'mensagem_consulta_aptidao',
        'cnpj_empresa',
        'nome_razao_social',
        'cpf_cnpj',
        'email',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'cidade',
        'bairro',
        'desc_bairro',
        'cod_municipio',
        'codigo_pessoa',
        'mensagem_cliente',
        'placa',
        'chassi',
        'renavam',
        'numero_crv',
        'codigo_seguranca_crv',
        'descricao_veiculo',
        'tipo_crv',
        'km',
        'data_hora_medicao_hodometro',
        'anofabricacao',
        'anomodelo',
        'versao',
        'cor_veiculo',
        'codigo_veiculo',
        'mensagem_veiculo',
        'codigo_situacao',
        'situacao_veiculo',
        'mensagem_historico',
        'data_entrada_veiculo',
        'data_saida_veiculo',
        'codigo_mov',
        'codigo_empresa',
        'codigo_proprietario',
        'codigo_operacao',
        'descricao_operacao',
        'datahora_movimentacao',
        'valor',
        'datahora_inserção',
        'chave_nfe',
        'created_by',
        'created_at',
        'updated_at',
        'status',
        'searchable',
        'veiculo_id'
    ];

    protected $connection = 'mysql';

    protected $table = 'renave_auto_aptidao_atpve';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public function revenda()
    {
        return $this->hasOne(Revenda::class, 'id', 'revenda_id');
    }

    public function veiculo()
    {
        return $this->hasOne(Veiculo::class, 'id', 'veiculo_id');
    }

    public function negociacaoVeiculo()
    {
        return $this->hasOne(NegociacaoVeiculo::class, 'id', 'negociacao_veiculo_id');
    }



    public function scopeSearch($query, $search){

        $search = remove_stop_words($search);
        $search = remove_special_character($search);
        $search = remove_extra_spaces($search);
        $search = preg_replace('!\s+!', ' ', $search);
        $words = explode(' ', $search);
        if (!empty($search)) {
            if(count($words) <= 1){
                $search = normalize_search($search);
                return $query->whereRaw("MATCH (renave_auto_aptidao_atpve.searchable) AGAINST ('{$search}\"' IN BOOLEAN MODE)");
            }

            $string = '';
            foreach($words as $key => $word){
                $word = normalize_search($word);
                $string .= '+';
                $string .= $word . '*';
                if(($key + 1) <= count($words)){
                    $string .= ' ';
                }
            }
            return $query->whereRaw("MATCH (renave_auto_aptidao_atpve.searchable) AGAINST ('{$string}\"' IN BOOLEAN MODE)");
        }

        return null;
    }

    public static function getSearchable($renaveAutoAptidaoAtpve){

        $searchable = null;

        if(isset($renaveAutoAptidaoAtpve->codigo_veiculo)){
            $searchable .= $renaveAutoAptidaoAtpve->codigo_veiculo;
        }

        if(isset($renaveAutoAptidaoAtpve->telefone)){
            $searchable .= ' ' . $renaveAutoAptidaoAtpve->telefone;
        }

        // remove os acentos e caracteres especiais para realizar a busca
        $searchable = normalize_search($searchable);

        return $searchable;
    }

}
