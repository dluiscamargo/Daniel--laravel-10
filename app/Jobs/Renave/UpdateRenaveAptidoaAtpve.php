<?php

namespace App\Jobs\Renave;

use App\Models\Integracao\Integracao;
use App\Models\Negociacao\NegociacaoTipoOperacao;
use App\Models\Renave\RenaveAptidaoAtpve;
use App\Models\Revenda\RevendaIntegracao;
use App\Models\Log\LogIntegracaoNew;
use App\Models\Renave\ApiClient;
use App\Models\Status\Status;
use App\Models\Util\Slack;
use Dflydev\DotAccessData\Data;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Notificacoes\SendNotificacao;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Carbon\Carbon;
use Illuminate\Support\Str;
use ShiftOneLabs\LaravelSqsFifoQueue\Bus\SqsFifoQueueable;

class UpdateRenaveAptidoaAtpve implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SqsFifoQueueable;

    protected $revenda_id, $apiClient, $slack;
    protected $logMongo;
    protected $usuario_id, $usuario, $renaveAutoAptidaoAtpve, $renaveAutoAptidaoAtpve_id, $revendaNegociacao, $revendaIntegracao;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($revenda_id, $usuario_id, $renaveAutoAptidaoAtpve_id)
    {
        $this->revenda_id = $revenda_id;
        $this->usuario_id = $usuario_id;
        $this->renaveAutoAptidaoAtpve_id = $renaveAutoAptidaoAtpve_id;

        $this->onConnection('redis')->onQueue('integracao');
    }

    public function tags()
    {
        return ['UpdateRenaveAuto', 'renaveAutoAptidaoAtpve_id:'. $this->renaveAutoAptidaoAtpve_id];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->slack = new Slack();

        try {
            $this->renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('id', $this->renaveAutoAptidaoAtpve_id)->first();

            if (!$this->renaveAutoAptidaoAtpve) {
                abort(404);
            }

            $this->revendaIntegracao = RevendaIntegracao::where('revenda_id', $this->revenda_id)->where('integracao_id', Integracao::RENAVE_AUTO)->where('status_id', Status::ATIVO)->first();

            $this->apiClient = new ApiClient($this->revendaIntegracao);
            $this->insertRenaveAutoCliente();

            return;
        } catch (\Exception $e) {

            $this->slack->sendBug('[Revenda Id] ' . $this->revenda_id . '[Cliente Renave Auto Id] ' . $this->renaveAutoAptidaoAtpve_id . '  - Cliente Renave Auto - | [Erro] - ' . $e->getMessage());
        }
    }

    private function insertRenaveAutoCliente()
    {
        $params = $this->paramsCliente();

        $url = ApiClient::getEndpoint() . '/cadastrarPessoa';

        $log = $this->saveLog('POST', $params, $url, 'Cadastro cliente Renave Auto');

        $response = $this->apiClient->request($url, 'POST', $params);
        $data = json_decode($response["response"]);

        $this->updateLog($log, $data, $response["response_type"]);

        if($response["response_type"] == 200 && $data->Dados->codigo_pessoa > 0){

            //atualizar o registro na tabela renaveAutoAptidaoAtpve para tela de listagem/renave auto
            $this->renaveAutoAptidaoAtpve->mensagem_cliente = $data->Mensagem;
            $this->renaveAutoAptidaoAtpve->codigo_pessoa = $data->Dados->codigo_pessoa;
            $this->renaveAutoAptidaoAtpve->save();

            //disparar o dump no slack com mensagen de Sucess true
            $message = "[Renave Auto Id] ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ".$data->Dados->codigo_pessoa;
            $this->slack->sendJobQueue($message);
        }elseif ($response["response_type"] == 400) {

            $this->renaveAutoAptidaoAtpve->mensagem_cliente = $data->Message;
            $this->renaveAutoAptidaoAtpve->save();

            //atualizar o registro na tabela renaveAutoAptidaoAtpve para tela de listagem/renave auto
            $message = "ERRO 400";
            $message = "[Renave Auto Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ". $message;
            $this->slack->sendJobQueue($message);

         }elseif ($response["response_type"] == 401) {
            $message = "- ERRO 401 | Unauthorized";
            $message = "[Renave Auto Id] - " . $this->renaveAutoAptidaoAtpve_id . "  [Revenda Id] - " . $this->revenda_id . "  message " . $message;
            $this->slack->sendJobQueue($message);
            return;
        }else{
            $message = "[Renave Auto Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ".json_encode($data);
            $this->slack->sendJobQueue($message);
        }

        if(isset($this->renaveAutoAptidaoAtpve->codigo_pessoa) && ($this->renaveAutoAptidaoAtpve->negociacaoVeiculo->tipo_operacao_id == NegociacaoTipoOperacao::ENTRADA)) {
              $this->insertRenaveAutoVeiculo();
        }

        return;
    }

    public function insertRenaveAutoVeiculo()
    {
        $params = $this->paramsVeiculo();

        $url = ApiClient::getEndpoint() . '/cadastrarVeiculo';

        $log = $this->saveLog('POST', $params, $url, 'Cadastro veículo Renave Auto');

        $response = $this->apiClient->request($url, 'POST', $params);

        $data = json_decode($response["response"]);

        $this->updateLog($log, $data, $response["response_type"]);

        if($response["response_type"] == 200 && $data->Dados->codigo_veiculo > 0 ){

            //atualizar o registro na tabela renaveAutoAptidaoAtpve para tela de listagem/renave auto
            $this->renaveAutoAptidaoAtpve->mensagem_veiculo = $data->Mensagem;
            $this->renaveAutoAptidaoAtpve->codigo_veiculo = $data->Dados->codigo_veiculo;
            $this->renaveAutoAptidaoAtpve->save();

            //disparar o dump no slack com mensagen de Sucess true
            $message = "SUCESS TRUE 200";
            $message = "[Renave Auto Veiculo Id]". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ".$message;
            $this->slack->sendJobQueue($message);
        }elseif ($response["response_type"] == 400) {

            $this->renaveAutoAptidaoAtpve->mensagem_veiculo = $data->Message;
            $this->renaveAutoAptidaoAtpve->save();

            //atualizar o registro na tabela renaveAutoAptidaoAtpve para tela de listagem/renave auto
            $message = "ERRO 400";
            $message = "[Renave Auto Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ". $message;
            $this->slack->sendJobQueue($message);

        }elseif ($response["response_type"] == 401) {
            $this->renaveAutoAptidaoAtpve = "- ERRO 401 | Unauthorized";
            $message = "[Renave Auto Veiculo Id] - " . $this->renaveAutoAptidaoAtpve_id . "  [Revenda Id] - " . $this->revenda_id . "  message " . $this->renaveAutoAptidaoAtpve;
            $this->slack->sendJobQueue($message);
            return;
        }else{
            $message = "[Renave Auto Veiculo Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ".json_encode($data);
            $this->slack->sendJobQueue($message);
        }

        if (isset($this->renaveAutoAptidaoAtpve->codigo_veiculo)) {
            $this->getRenaveAutoAptidao();
        }

        return;
    }

    private function getRenaveAutoAptidao()
    {
        $params = $this->paramsConsultaAptidao();

        $url = ApiClient::getEndpoint() . '/consultaVeiculo?placa_veiculo=' . $this->renaveAutoAptidaoAtpve->veiculo->placa . '&renavam_veiculo=' . $this->renaveAutoAptidaoAtpve->veiculo->renavam . '&numero_crv_veiculo=' . $this->renaveAutoAptidaoAtpve->numero_crv . '&tipo_crv_veiculo=' . $this->renaveAutoAptidaoAtpve->tipo_crv;

        $log = $this->saveLog('GET', $params, $url, 'Consulta Aptidão Renave');

        $response = $this->apiClient->request($url, 'GET', $params);

        $data = json_decode($response["response"]);

        $this->updateLog($log, $data, $response["response_type"]);

        if($response["response_type"] == 200){

            //atualizar o registro na tabela renaveAutoAptidaoAtpve para tela de listagem/renave
            $this->renaveAutoAptidaoAtpve->mensagem_consulta_aptidao = $data->Mensagem;
            $this->renaveAutoAptidaoAtpve->aptidao_veiculo_apto = $data->Dados->aptidao->diagnostico->veiculoApto;
            $this->renaveAutoAptidaoAtpve->motivos_para_nao_aptidao = $data->Dados->aptidao->diagnostico->motivosParaNaoAptidao[0] ?? null;
            $this->renaveAutoAptidaoAtpve->numero_codigo_barras = $data->Dados->aptidao->informacoesDebitos->boletos[0]->numeroCodigoBarras ?? null;
            $this->renaveAutoAptidaoAtpve->data_vencimento_boleto = $data->Dados->aptidao->informacoesDebitos->boletos[0]->dataVencimentoBoleto ?? null;
            $this->renaveAutoAptidaoAtpve->valor_boleto = $data->Dados->aptidao->informacoesDebitos->boletos[0]->valorBoleto ?? null;
            $this->renaveAutoAptidaoAtpve->save();

            //disparar o dump no slack com mensagen de Sucess true
            $message = "SUCESS TRUE CONSULTA APTIDÃO";
            $message = "[Renave Auto Id] ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ".$message;
            $this->slack->sendJobQueue($message);
        }elseif ($response["response_type"] == 500) {
            $this->renaveAutoAptidaoAtpve = "ERRO 500";
            $message = "[Renave Auto Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ". $this->renaveAutoAptidaoAtpve;
            $this->slack->sendJobQueue($message);
            return;
        }elseif ($response["response_type"] == 401) {
            $this->renaveAutoAptidaoAtpve = "- ERRO 401 | Unauthorized";
            $message = "[Renave Auto Id] - " . $this->renaveAutoAptidaoAtpve_id . "  [Revenda Id] - " . $this->revenda_id . "  message " . $this->renaveAutoAptidaoAtpve;
            $this->slack->sendJobQueue($message);
            return;
        }else{
            $message = "[Renave Auto Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ".json_encode($data);
            $this->slack->sendJobQueue($message);
        }

        return;
    }

    private function paramsCliente()
    {
        $data = [

            'nome' => $this->renaveAutoAptidaoAtpve->nome_razao_social,
            'cpfCnpj' => $this->renaveAutoAptidaoAtpve->cpf_cnpj,
            'email' => $this->renaveAutoAptidaoAtpve->email,
            'cep' => $this->renaveAutoAptidaoAtpve->cep,
            'endereco' => $this->renaveAutoAptidaoAtpve->endereco,
            'numeroEndereco' => $this->renaveAutoAptidaoAtpve->numero,
            'bairro' => $this->renaveAutoAptidaoAtpve->desc_bairro,
            'complemento' => $this->renaveAutoAptidaoAtpve->complemento,
            'municipio' => $this->renaveAutoAptidaoAtpve->cod_municipio,
        ];

        return $data;
    }

    public function paramsVeiculo()
    {
        $avaliacao = $this->renaveAutoAptidaoAtpve->veiculo->avaliacoes->where('ultima', 1)->first();

        $data = [
            'placa' => $this->renaveAutoAptidaoAtpve->veiculo->placa,
            'chassi' => $this->renaveAutoAptidaoAtpve->veiculo->chassi,
            'renavam' => $this->renaveAutoAptidaoAtpve->veiculo->renavam,
            'numeroCRV' => $this->renaveAutoAptidaoAtpve->numero_crv,
            'codigoSegurancaCRV' => $this->renaveAutoAptidaoAtpve->codigo_seguranca_crv,
            'tipoCRV' => $this->renaveAutoAptidaoAtpve->tipo_crv,
            'descricaoVeiculo' => $this->renaveAutoAptidaoAtpve->descricao_veiculo,
            'km' => $avaliacao->km,
            'dataHoraMedicaoHodometro' =>  Carbon::parse($avaliacao->data_fim_avaliacao),
            'anoFabricacao' => $this->renaveAutoAptidaoAtpve->veiculo->anofabricacao,
            'anoModelo' => $this->renaveAutoAptidaoAtpve->veiculo->anomodelo,
            'corVeiculo' => $this->renaveAutoAptidaoAtpve->veiculo->cor->nome

        ];


        return $data;

    }

    private function paramsConsultaAptidao()
    {
        $data = [

            'placa_veiculo' => $this->renaveAutoAptidaoAtpve->veiculo->placa,
            'cnpj_empresa' => $this->renaveAutoAptidaoAtpve->revenda->cnpj,
            'renavam_veiculo' => $this->renaveAutoAptidaoAtpve->veiculo->renavam,
            'numero_crv_veiculo' => $this->renaveAutoAptidaoAtpve->numero_crv,
            'tipo_crv_veiculo' => $this->renaveAutoAptidaoAtpve->tipo_crv,

        ];

        return $data;
    }

    private function saveLog($request_type, $params, $method, $description)
    {
        $log = new \stdClass();
        $log->usuario_id = $this->usuario_id;
        $log->revenda_id = $this->revendaIntegracao->revenda_id;
        $log->integracao_id = Integracao::RENAVE_AUTO;
        $log->request = json_encode($params);
        $log->request_type = $request_type;
        $log->url = $method;
        $log->descricao = $description;
        $log->response = null;
        $log->response_type = null;
        $this->logMongo = LogIntegracaoNew::saveLog($log);

        return $log;
    }

    private function updateLog($log, $response, $response_type)
    {
        LogIntegracaoNew::updateLogAds($this->logMongo, $response, $response_type);
        return;
    }


}
