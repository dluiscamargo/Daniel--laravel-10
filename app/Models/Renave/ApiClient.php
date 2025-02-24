<?php

namespace App\Models\Renave;

use App\Models\Integracao\Integracao;
use App\Models\Log\LogIntegracaoNew;
use App\Models\Renave\RenaveAptidaoAtpve;
use App\Models\Util\Slack;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Traits\UploadsPictures;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class ApiClient extends Model
{
    const ENDPOINT = "http://integracao.renaveauto.com.br/integracao";
    const ENDPOINT_HOMOL = "http://integracao.dev.renaveauto.com.br/integracao";

    protected $revendaIntegracao = null;
    protected $count = 0;
    protected $logMongo;

    public function __construct($revendaIntegracao)
    {
        $this->revendaIntegracao = $revendaIntegracao;
    }

    public function request($url, $type = 'GET', $params = [])
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->revendaIntegracao->authorization_code
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        if ($error) {
            return ['response' => $error, 'response_type' => $info['http_code']];
        } else {
            return ['response' => $response, 'response_type' => $info['http_code']];
        }

    }

    public function searchHistoricoVeiculo($codigo_veiculo)
    {
        $this->renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();

        $url = ApiClient::getEndpoint() . '/historicoVeiculo?placa_veiculo=' . $this->renaveAutoAptidaoAtpve->placa;

        $log = $this->saveLog('GET', [], $url, 'Consulta histórico veículo');

        $response = $this->requestSearchHistoricoVeiculo($url);

        $data = json_decode($response["data"]);

        $this->updateLog($log, $data, $response["response_type"]);

        if($response['response_type'] == 200 && isset($data->Dados->codigo_veiculo)){

            $renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();

            if (isset($renaveAutoAptidaoAtpve->codigo_veiculo) && !empty($renaveAutoAptidaoAtpve->codigo_veiculo > 0)) {

                $renaveAutoAptidaoAtpve->update([

                    'codigo_situacao'    => $data->Dados->situacao_veiculo->codigo_situacao,
                    'situacao_veiculo'   => $data->Dados->situacao_veiculo->stituacao_veiculo,
                    'mensagem_historico' => $data->Mensagem,
                    'data_entrada_veiculo' => $data->Dados->data_entrada_veiculo,
                    'data_saida_veiculo' => $data->Dados->data_saida_veiculo,

                ]);
            }

        }elseif ($response["response_type"] == 400) {

            $this->renaveAutoAptidaoAtpve->mensagem_veiculo = $data->Message;
            $this->renaveAutoAptidaoAtpve->save();

            //atualizar o registro na tabela renaveAutoAptidaoAtpve para tela de listagem/renave
            $message = "ERRO 400";
            $message = "[Renave Auto Id] - ". $this->renaveAutoAptidaoAtpve_id ."[Revenda Id] - ". $this->revenda_id. "message ". $message;

        }

        return $data;
    }

    public function downloadAtpveVeiculo($codigo_veiculo)
    {
        $this->renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();

        $url = ApiClient::getEndpoint() . '/atpve?placa_veiculo=' . $this->renaveAutoAptidaoAtpve->placa;
        //$url = ApiClient::getEndpoint() . '/atpve?chassi_veiculo=' . $this->renaveAutoAptidaoAtpve->chassi;

        $log = $this->saveLog('GET', [], $url, 'Baixar documento ATPVE veículo');

        $response = $this->requestDownloadAtpveVeiculo($url);

        $data = json_decode($response["data"]);

        $this->updateLog($log, $data, $response["response_type"]);

        if($response['response_type'] == 200 && $data->Sucesso == true && $this->renaveAutoAptidaoAtpve->codigo_veiculo > 0){

            $renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();

            if (isset($renaveAutoAptidaoAtpve->codigo_veiculo) && !empty($renaveAutoAptidaoAtpve->codigo_veiculo > 0)) {

                $pdfAtpveBase64 = base64_decode($data->Dados->data->pdfAtpvBase64);

                $url = $this->uploadPdfAtpveRenave($pdfAtpveBase64, $this->revendaIntegracao->revenda_id);

                    $this->renaveAutoAptidaoAtpve->sucesso_atpve = $data->Sucesso;
                    $this->renaveAutoAptidaoAtpve->mensagem_atpve = $data->Mensagem;
                    $this->renaveAutoAptidaoAtpve->numero_atpve = $data->Dados->data->numeroAtpv;
                    $this->renaveAutoAptidaoAtpve->pdf_atpv_Base64 = $url;

                    $this->renaveAutoAptidaoAtpve->save();

            }

        }

        return $data;

    }

    public function consultaAptidaoVeiculo($codigo_veiculo)
    {
        $this->renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();

        $url = ApiClient::getEndpoint() . '/consultaVeiculo?placa_veiculo=' . $this->renaveAutoAptidaoAtpve->placa . '&renavam_veiculo=' . $this->renaveAutoAptidaoAtpve->renavam . '&numero_crv_veiculo=' . $this->renaveAutoAptidaoAtpve->numero_crv . '&tipo_crv_veiculo=' . $this->renaveAutoAptidaoAtpve->tipo_crv;

        $log = $this->saveLog('GET', [], $url, 'Consulta aptidão veículo');

        $response = $this->requestConsultaAptidaoVeiculo($url);

        $data = json_decode($response["data"]);

        $this->updateLog($log, $data, $response["response_type"]);

        if($response['response_type'] == 200 && $data->Sucesso == true && $this->renaveAutoAptidaoAtpve->codigo_veiculo > 0){

            $renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();

            if (isset($renaveAutoAptidaoAtpve->codigo_veiculo) && !empty($renaveAutoAptidaoAtpve->codigo_veiculo > 0)) {

                $this->renaveAutoAptidaoAtpve->mensagem_consulta_aptidao = $data->Mensagem;
                $this->renaveAutoAptidaoAtpve->aptidao_veiculo_apto = $data->Dados->aptidao->diagnostico->veiculoApto;
                $this->renaveAutoAptidaoAtpve->motivos_para_nao_aptidao = $data->Dados->aptidao->diagnostico->motivosParaNaoAptidao[0] ?? null;
                $this->renaveAutoAptidaoAtpve->numero_codigo_barras = $data->Dados->aptidao->informacoesDebitos->boletos[0]->numeroCodigoBarras ?? null;
                $this->renaveAutoAptidaoAtpve->data_vencimento_boleto = $data->Dados->aptidao->informacoesDebitos->boletos[0]->dataVencimentoBoleto ?? null;
                $this->renaveAutoAptidaoAtpve->valor_boleto = $data->Dados->aptidao->informacoesDebitos->boletos[0]->valorBoleto ?? null;

                $this->renaveAutoAptidaoAtpve->update();

            }

        }

        //session()->flash('Sucesso', 'Consulta aptidão realizada com sucesso!');
        //return response()->json(['status' => true]);
        return $data;

    }

    public function requestSearchHistoricoVeiculo($url, $type = 'GET', $params = []){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: Bearer ' . $this->revendaIntegracao->authorization_code
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);

        if ($error) {
            return ['data' => $error, 'response_type' => $info['http_code']];
        } else {
            return ['data' => $response, 'response_type' => $info['http_code']];
        }
    }

    public function requestDownloadAtpveVeiculo($url, $type = 'GET', $params = []){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                ': ',
                'Authorization: Bearer ' . $this->revendaIntegracao->authorization_code
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);

        if ($error) {
            return ['data' => $error, 'response_type' => $info['http_code']];
        } else {
            return ['data' => $response, 'response_type' => $info['http_code']];
        }
    }

    public function requestConsultaAptidaoVeiculo($url, $type = 'GET', $params = []){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                   ': ',
                   'Authorization: Bearer ' . $this->revendaIntegracao->authorization_code
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $info = curl_getinfo($curl);

        curl_close($curl);

        if ($error) {
            return ['data' => $error, 'response_type' => $info['http_code']];
        } else {
            return ['data' => $response, 'response_type' => $info['http_code']];
        }
    }

    //    public function downloadCrlveVeiculo($codigo_veiculo)
//    {
//        $this->renaveAutoAptidaoAtpve = RenaveAutoAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();
//
//        //"http://integracao.dev.renaveauto.com.br/integracao/atpve?cnpj_empresa=00235520000183&placa_veiculo=REH1230"
//        $url = ApiClient::getEndpoint() . '/atpve?cnpj_empresa=' . $this->renaveAutoAptidaoAtpve->revenda->cnpj . '&placa_veiculo=' . $this->renaveAutoAptidaoAtpve->placa;
//
//        $log = $this->saveLog('GET', [], $url, 'Baixar documento CRLVE veículo');
//
//        $response = $this->requestDownloadCrlveVeiculo($url);
//
//        $data = json_decode($response["data"]);
//
//        $this->updateLog($log, $data, $response["response_type"]);
//
//        if($response['response_type'] == 200 && $data->Sucesso == true && $this->renaveAutoAptidaoAtpve->codigo_veiculo > 0){
//
//            $renaveAutoAptidaoAtpve = RenaveAutoAptidaoAtpve::where('codigo_veiculo', $codigo_veiculo)->first();
//
//            if (isset($renaveAutoAptidaoAtpve->codigo_veiculo) && !empty($renaveAutoAptidaoAtpve->codigo_veiculo > 0)) {
//
//                $pdfBase64 = base64_decode($data->Dados->pdfBase64);
//
//                $url = $this->uploadPdfCrlveRenave($pdfBase64, $this->revendaIntegracao->revenda_id);
//
//                $this->renaveAutoAptidaoAtpve->sucesso_atpve = $data->Sucesso;
//                $this->renaveAutoAptidaoAtpve->mensagem_atpve = $data->Mensagem;
//                $this->renaveAutoAptidaoAtpve->pdf_crlve_Base64 = $url;
//
//                $this->renaveAutoAptidaoAtpve->save();
//
//            }
//
//        }
//
//        return $data;
//    }

    //    public function requestDownloadCrlveVeiculo($url, $type = 'GET', $params = []){
//
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => $url,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'GET',
//            CURLOPT_HTTPHEADER => array(
//                ': ',
//                'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6WyJSZW5hdmVBcGlDbGllbnQiLCJSRVRJUCBJTVBMRU1FTlRPUyBST0RPVklBUklPUyBMVERBIl0sImp0aSI6ImFiYWVkNzA1NDM2OTQ2ZWM5ZWIyNmE0ZmNlMmI2ZjQ4Iiwicm9sZSI6ImNsaWVudGUiLCJjb2RpZ29FbXByZXNhIjoiMjU4IiwibmJmIjoxNzI4NDkyOTE3LCJleHAiOjE4ODYxNzI5MTcsImlhdCI6MTcyODQ5MjkxNywiaXNzIjoiUmVuYXZlQXV0b19BcGlXb3JrZXIiLCJhdWQiOiJSZW5hdmVBdXRvX0FwaVdvcmtlciJ9.E8bd54IKVpuiMJ3Wt2lRbMqHcbkx_ZUBqcf-rf_EQmU'
//            ),
//        ));
//
//        $response = curl_exec($curl);
//        $error = curl_error($curl);
//        $info = curl_getinfo($curl);
//
//        curl_close($curl);
//
//        if ($error) {
//            return ['data' => $error, 'response_type' => $info['http_code']];
//        } else {
//            return ['data' => $response, 'response_type' => $info['http_code']];
//        }
//    }

    private function saveLog($request_type, $params, $method, $description)
    {
        // cria o log
        $log = new \stdClass();
        $log->usuario_id = auth()->user()->id;
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
        // atualiza o log
        LogIntegracaoNew::updateLogAds($this->logMongo, $response, $response_type);
        return;
    }

    public static function getEndpoint(){
        if(App::environment('production')){
            return Self::ENDPOINT;
        }
        return Self::ENDPOINT_HOMOL;
    }

    public function uploadPdfAtpveRenave($file, $revenda_id)
    {
        if (!$file) {
            return null;
        }

        $fileName = sprintf('%s.%s', Uuid::uuid4(), 'pdf');
        $path = Storage::disk('s3')->put('/aptve/renave/'. $revenda_id . '/'.$fileName, $file, 'public');
        $url = Storage::disk('s3')->url('/aptve/renave/'. $revenda_id . '/'.$fileName);

        return $url;
    }

    //    public function uploadPdfCrlveRenave($file, $revenda_id)
//    {
//        if (!$file) {
//            return null;
//        }
//
//        $fileName = sprintf('%s.%s', Uuid::uuid4(), 'pdf');
//        $path = Storage::disk('s3')->put('/crlve/renave/'. $revenda_id . '/'.$fileName, $file, 'public');
//        $url = Storage::disk('s3')->url('/crlve/renave/'. $revenda_id . '/'.$fileName);
//
//        return $url;
//    }

}
