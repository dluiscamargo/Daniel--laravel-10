<?php

namespace App\Http\Controllers\Api\Renave;

use App\Exports\ExtratoLeadCotacaoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRenaveAutoAptidaoAtpve;
use App\Jobs\Renave\UpdateRenaveAptidoaAtpve;
use App\Models\Integracao\Integracao;
use App\Models\Negociacao\Negociacao;
use App\Models\Negociacao\NegociacaoCliente;
use App\Models\Negociacao\NegociacaoVeiculo;
use App\Models\Renave\RenaveAptidaoAtpve;
use App\Models\Revenda\Revenda;
use App\Models\Revenda\RevendaIntegracao;
use App\Models\Revenda\RevendaNegociacao;
use App\Models\Status\Status;
use App\Models\Veiculo\Veiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RenaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('hasAccessNegociacao', new Revenda());
        // $this->authorize('hasAccessServicosAdicionais', new Revenda());

        return view('renave-auto.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $negociacao_id, $veiculo_id)
    {
        //$this->authorize('hasAccessNegociacao', new Revenda());
        //$this->authorize('hasAccessServicosAdicionais', new Revenda());

        // $negociacao = Negociacao::where('id', $negociacao_id)->filterRevenda()->first();
        // if (!$negociacao) {
        //     abort(404);
        // }

        # [validação] apenas o mesmo vendedor que inicia a negociação pode editar ou ADMIN, REVENDA e COMERCIAL
        // if(!$negociacao->canCreateOrEditNegotiation()){
        //     abort(404);
        // }

        #validação [para negociações canceladas]
        // if ($negociacao->canceled()) {
        //     return redirect(Negociacao::redirectTo($negociacao));
        // }

        #step validation
        // if ($negociacao->current_step < Negociacao::STEP6_RESUMO) {
        //     session()->flash('error', 'Por favor, defina a(s) data(s) de recebimento/entrega para todos os veículos.');

        //     return redirect(Negociacao::redirectTo($negociacao));
        // }

        // $revendaNegociacao = RevendaNegociacao::where('id', $negociacao->revenda_negociacao_id)->first();
        // if (!$revendaNegociacao) {
        //     abort(404);
        // }

        #multirevenda
        // $revendas = Revenda::getIdRevendas();
        // $veiculo = Veiculo::where('id', $veiculo_id)->whereIn('revenda_id', $revendas)->first();
        // if (!$veiculo) {
        //     abort(404);
        // }

        // $negociacaoVeiculo = NegociacaoVeiculo::where('negociacao_id', $negociacao_id)->where('veiculo_id', $veiculo_id)->first();
        // if (!$negociacaoVeiculo) {
        //     abort(404);
        // }

        // $clienteNegociacao = NegociacaoCliente::where('id', $negociacao->negociacao_cliente_id)->first();

        $renaveAutoAptidaoAtpve = new RenaveAptidaoAtpve();

        return view('negociacao.renave-auto.create', compact('negociacao', 'revendaNegociacao','negociacaoVeiculo','clienteNegociacao','renaveAutoAptidaoAtpve'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRenaveAutoAptidaoAtpve $request, $negociacao_id, $veiculo_id)
    {

        $this->authorize('hasAccessNegociacao', new Revenda());
        //$this->authorize('hasAccessServicosAdicionais', new Revenda());

        $negociacao = Negociacao::where('id', $negociacao_id)->filterRevenda()->first();
        if (!$negociacao) {
            abort(404);
        }

        # [validação] apenas o mesmo vendedor que inicia a negociação pode editar ou ADMIN, REVENDA e COMERCIAL
        if(!$negociacao->canCreateOrEditNegotiation()){
            abort(404);
        }

        #validação [para negociações canceladas]
        if ($negociacao->canceled()) {
            return redirect(Negociacao::redirectTo($negociacao));
        }

        #step validation
        if ($negociacao->current_step < Negociacao::STEP6_RESUMO) {
            session()->flash('error', 'Por favor, defina a(s) data(s) de recebimento/entrega para todos os veículos.');

            return redirect(Negociacao::redirectTo($negociacao));
        }

        $revendaNegociacao = RevendaNegociacao::where('id', $negociacao->revenda_negociacao_id)->first();
        if (!$revendaNegociacao) {
            abort(404);
        }

        #multirevenda
        $revendas = Revenda::getIdRevendas();

        $veiculo = Veiculo::where('id', $veiculo_id)->whereIn('revenda_id', $revendas)->first();
        if (!$veiculo) {
            abort(404);
        }

        $negociacaoVeiculo = NegociacaoVeiculo::where('negociacao_id', $negociacao_id)->where('veiculo_id', $veiculo_id)->first();
        if (!$negociacaoVeiculo) {
            abort(404);
        }

        $clienteNegociacao = NegociacaoCliente::where('id', $negociacao->negociacao_cliente_id)->first();

        $revendaIntegracao = RevendaIntegracao::where('integracao_id', Integracao::RENAVE_AUTO)->where('revenda_id', Revenda::getRevenda()->id)->where('status_id', Status::ATIVO)->first();

        $renaveAutoAptidaoAtpve = DB::transaction(function () use ($request, $revendaIntegracao, $revendaNegociacao, $negociacao, $negociacaoVeiculo, $clienteNegociacao, $revendas){

            $request->merge([
                'revenda_id' => Revenda::getRevenda()->id,
                'negociacao_id' => $negociacao->id,
                'negociacao_cliente_id' => $clienteNegociacao->id,
                'negociacao_veiculo_id' => $negociacaoVeiculo->id,
                'revenda_integracao_id' => $revendaIntegracao->id,
                'aptidao_veiculo_apto' => (bool) $request->aptidao_veiculo_apto,
                'motivos_para_nao_aptidao' => $request->motivos_para_nao_aptidao ?: null,
                'existemDebitos' => (bool) $request->existemDebitos,
                'cnpj_empresa' => preg_replace('/[^0-9]/', '',$revendaNegociacao->cpf_cnpj),
                //aptidao
                'nome_razao_social' => $negociacao->cliente->nome_razao_social ?: null,
                'cpf_cnpj' => preg_replace('/[^0-9]/', '',$negociacao->cliente->cpf_cnpj),
                'email'    => $negociacao->cliente->email ?: null,
                'cep' => preg_replace('/[^0-9]/', '', $negociacao->cliente->cep),
                'endereco' => $negociacao->cliente->endereco,
                'numero' => $negociacao->cliente->numero,
                'complemento' => $negociacao->cliente->complemento,
                'cidade' => $negociacao->cliente->cidade,
                'bairro' => $negociacao->cliente->bairro,
                'cod_municipio' => $negociacao->cliente->cod_municipio,
                'codigo_pessoa' => $request->codigo_pessoa ?: null,
                //cliente
                'placa' => $negociacaoVeiculo->veiculo->placa,
                'chassi' => $negociacaoVeiculo->veiculo->chassi,
                'renavam' => $negociacaoVeiculo->veiculo->renavam,
                'numero_crv' => $request->numero_crv,
                'tipo_crv' => $request->tipo_crv,
                'km' => $negociacaoVeiculo->veiculo->km ?: null,
                'data_hora_medicao_hodometro' => $negociacaoVeiculo->veiculo->data_hora_medicao_hodometro,
                'anofabricacao' => $negociacaoVeiculo->veiculo->anofabricacao,
                'anomodelo' => $negociacaoVeiculo->veiculo->anomodelo,
                'versao' => $negociacaoVeiculo->veiculo->versao,
                'cor_veiculo' => $negociacaoVeiculo->veiculo->cor_id,
                'veiculo_id' => $negociacaoVeiculo->veiculo->id,
                'codigo_veiculo' => $request->codigo_veiculo ?: null,
                //veiculo
                'created_by' => Auth::user()->id
            ]);

            $renaveAutoAptidaoAtpve = RenaveAptidaoAtpve::create($request->all());

            if($renaveAutoAptidaoAtpve) {
                $revenda = Revenda::getRevenda();
                if(App::environment('production')){
                    UpdateRenaveAptidoaAtpve::dispatch($revenda->id, Auth::user()->id, $renaveAutoAptidaoAtpve->id)->afterCommit();
                } else {
                    UpdateRenaveAptidoaAtpve::dispatchSync($revenda->id, Auth::user()->id, $renaveAutoAptidaoAtpve->id);
                }
            }

            return $renaveAutoAptidaoAtpve;
        });

        session()->flash('success', 'Consulta aptidão, cadastrado cliente veiculo com sucesso.');

        if($request->redirect_uri){
            return redirect($request->redirect_uri);
        }

        return redirect()->route('renave-auto.index');
    }

    public function exportExtrato(Request $request)
    {
        # filtro por período
        $inicio = Carbon::now()->startOfMonth()->format('d/m/Y');
        $fim = Carbon::now()->endOfMonth()->format('d/m/Y');

        // seta no request
        if ($request->periodo){
            $periodos = explode(' até ', $request->periodo);
            if(count($periodos) == 1){
                $periodos[1] = $periodos[0];
            }
            try {
                $inicio = Carbon::createFromFormat('d/m/Y', $periodos[0])->format('Y-m-d');
                $fim = Carbon::createFromFormat('d/m/Y', $periodos[1])->format('Y-m-d');

            } catch (\Exception $e) {
                abort(404);
            }
        }

        // verifica se o período é maior do que 60 dias
        $diff = datetimeDifference($fim, $inicio);
        if ($diff > 60) {
            $renaveAutoAptidaoAtpve = collect();

            request()->session()->flash('error', 'Período máximo permitido: 60 dias');
            return redirect()->route('ituran_lead_cotacao.index');
        }

        $revendaIntegracao = RevendaIntegracao::where('id', $request->revenda_integracao_id)->where('integracao_id', Integracao::RENAVE_AUTO)->where('revenda_id', Revenda::getRevenda()->id)->where('status_id', Status::ATIVO)->first();
        if (!$revendaIntegracao) {
            abort(404);
        }

        $renaveAutoAptidaoAtpve = RenaveAutoAptidaoAtpve::where('revenda_integracao_id', $revendaIntegracao->id)->whereNotNull('codigo_veiculo')->where('created_at', '>=', $inicio . ' 00:00:00')->where('created_at', '<=', $fim . ' 23:59:59');

        return Excel::download(new ExtratoLeadCotacaoExport($renaveAutoAptidaoAtpve->get()), 'extrato-lead-cotacao.xlsx');
    }

}
