<?php

namespace App\Models\Integracao;

use App\Jobs\Notificacoes\SendNotificacao;
use App\Models\Anuncio\Anuncio;
use App\Models\Contato\Cliente;
use App\Models\Followize\ApiClient;
use App\Models\Lead\HistoricoAtendimento;
use App\Models\Lead\LeadConsolidado;
use App\Models\Lead\Origem\Origem;
use App\Models\Lead\Veiculo\AtendimentoVeiculo;
use App\Models\Revenda\Plano;
use App\Models\Revenda\Webhook;
use App\Models\Usuario\Notificacao;
use App\Models\Usuario\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Revenda\Revenda;
use App\Models\Lead\Atendimento;
use App\Models\Revenda\RevendaIntegracao;
use App\Models\Status\Status;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Integracao extends Model
{
    protected $connection = 'mysql';

    protected $table = 'integracao';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'data';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = false;

    protected $fillable = ['nome', 'slug', 'tipo'];

    /* constantes integrações */

    const OLX = 1;

    const WEBMOTORS = 2;

    const ICARROS = 3;

    const SOCARRAO = 4;

    const SYONET = 5;

    const AUTOCORP = 6;

    const AUTOLINE = 7;

    const MERCADOLIVRE = 8;

    const MEUCARRONOVO = 9;

    const SANCES = 10;

    const CHAVESNAMAO = 11;

    const C2S = 12;

    const PHONETRACK = 13;

    const SIRENA = 14;

    const BIP = 15;

    const SHOPCAR = 16;

    const CATARINACARROS = 17;

    const MOBIAUTO = 18;

    const CREDAUTO = 19;

    const SEMINOVOSBH = 20;

    const MYHONDA = 21;

    const FACEBOOK = 22;

    const PORTALAUTOSHOPPING = 23;

    const VRUM = 24;

    const ITAU = 25;

    const COCKPIT = 26;

    const OBUSCA = 27;

    const WMESTOQUESITE = 28;

    const ALTIMUS_ESTOQUE_SITE = 29;

    const CARROSP = 30;

    const LITORALCAR = 31;

    const BOOM_SISTEMAS = 32;

    const FOCUS_NFE = 33;

    const COMPRE_BLINDADOS = 34;

    const ZUL = 35;

    const CLICKSIGN = 36;

    const SBS_CARROS = 37;

    const USADOSBR = 38;

    const ECOMPLETO = 39;

    const LEAD_FORCE = 40;

    const CREDERE = 41;

    const GOOGLE_MERCHANT_CENTER = 42;

    const AUTOAVALIAR = 43;

    const MR_SALES = 44;

    const APOLLO = 45;

    const TAKE_BLIP = 46;

    const AUTOMOTIVO_SHOPPING = 47;

    const VALE_AUTO_SHOPPING = 48;

    const AUTO360 = 49;

    const C7 = 50;

    const CUSTOMERX = 51;

    const KARVI = 52;

    const API_SITE = 53;

    const E_FICAZZ = 54;

    const LIBERTY_SEGUROS = 55;

    const RD_STATION = 56;

    const MEUBOT = 57;

    const MAQUINETA_VIRTUAL = 58;

    const DUOTALK = 59;

    const FOLLOWIZE = 60;

    const EASY_CHANNEL = 61;

    const ITURAN = 62;

    const AUTOGESTOR = 63;

    const EU_QUERO_CARROS = 64;

    const LEADSOK = 65;

    const COMPRECAR = 66;

    const GESTAUTO = 67;

    const NA_PISTA = 68;

    const SEMINOVOS_HONDA = 69;

    const CORRECTDATA = 70;

    const ITURANLEAD = 71;

    const FACEBOOK_LEAD_ADS = 72;

    const UNION_SOLUTIONS = 73;

    const RECICLALEAD = 74;

    const K8_BANK = 75;

    const SUPERLOGICA = 76;

    const AUTO_BUSINESS = 77;

    const RENAVE_AUTO = 78;

    const VUMOGRAPHY = 79;

    const DEBITO_DIRETO = 80;

    const DIGICAR = 81;

    /* constantes tipos */
    const TIPO_ADS = 'ADS';

    const TIPO_CRM = 'CRM';

    const TIPO_ERP = 'ERP';

    const TIPO_FINANCE = 'FINANCE';

    const TIPO_ESTOQUE_SITE = 'ESTOQUE_SITE';

    /* TIPO API */

    const TIPO_OAUTH = 'OAUTH';

    const TIPO_WSDL = 'WSDL';

    const TIPO_DATABASE = 'DATABASE';

    /* Status Lead API - WEBMOTORS */

    const WMPENDENTE = 1;

    const WMRECUSADAPENDENTE = 2;

    const WMAPROVADAORIGEM = 4;

    const WMAPROVADA = 5;

    const WMAPROVADAAUTOMATICAMENTE = 6;

    const WMRECUSADAPENDENTEORIGEM = 7;

    const INTEGRACOES_SITE = [
        Integracao::BIP,
        Integracao::COCKPIT,
        Integracao::C2S,
        Integracao::MYHONDA,
        Integracao::PHONETRACK,
        Integracao::WMESTOQUESITE,
        Integracao::ALTIMUS_ESTOQUE_SITE,
        Integracao::BOOM_SISTEMAS,
        Integracao::ECOMPLETO,
        Integracao::LEAD_FORCE,
        //Integracao::CREDERE,
        Integracao::MR_SALES,
        Integracao::SYONET,
        Integracao::AUTO360,
        Integracao::C7,
        Integracao::API_SITE,
        Integracao::FACEBOOK,
        Integracao::GOOGLE_MERCHANT_CENTER,
        Integracao::AUTOGESTOR,
        Integracao::FOLLOWIZE,
        Integracao::VUMOGRAPHY
    ];

    const INTEGRACOES_ESTOQUE_SITE =[
        Integracao::WMESTOQUESITE,
        Integracao::ALTIMUS_ESTOQUE_SITE,
        Integracao::BOOM_SISTEMAS
    ];

    const INTEGRACOES_ANUNCIO_SEM_VERSAO =[
        Integracao::FACEBOOK,
        Integracao::GOOGLE_MERCHANT_CENTER,
        Integracao::PORTALAUTOSHOPPING,
        Integracao::VRUM,
        Integracao::AUTO_BUSINESS,
    ];

    const INTEGRACOES_ANUNCIO = [
        Integracao::AUTOLINE,
        Integracao::CHAVESNAMAO,
        Integracao::ICARROS,
        Integracao::MERCADOLIVRE,
        //Integracao::MEUCARRONOVO,
        Integracao::NA_PISTA,
        Integracao::MOBIAUTO,
        Integracao::OLX,
        Integracao::PORTALAUTOSHOPPING,
        Integracao::CATARINACARROS,
        Integracao::SEMINOVOSBH,
        Integracao::SHOPCAR,
        Integracao::SOCARRAO,
        Integracao::VRUM,
        Integracao::WEBMOTORS,
        //Integracao::OBUSCA,
        Integracao::CARROSP,
        Integracao::LITORALCAR,
        //Integracao::COMPRE_BLINDADOS,
        Integracao::SBS_CARROS,
        Integracao::USADOSBR,
        Integracao::AUTOMOTIVO_SHOPPING,
        Integracao::VALE_AUTO_SHOPPING,
        Integracao::COMPRECAR,
        Integracao::SEMINOVOS_HONDA,
        Integracao::KARVI,
        Integracao::AUTO_BUSINESS,
        //Integracao::DIGICAR
    ];

    const INTEGRACOES_ANUNCIO_PUBLICACAO = [
        Integracao::AUTOLINE,
        Integracao::CHAVESNAMAO,
        Integracao::ICARROS,
        Integracao::MERCADOLIVRE,
        //Integracao::MEUCARRONOVO,
        Integracao::MOBIAUTO,
        Integracao::OLX,
        Integracao::CATARINACARROS,
        Integracao::SEMINOVOSBH,
        Integracao::SHOPCAR,
        Integracao::SOCARRAO,
        Integracao::WEBMOTORS,
        Integracao::NA_PISTA,
        //Integracao::OBUSCA,
        Integracao::CARROSP,
        Integracao::LITORALCAR,
        //Integracao::COMPRE_BLINDADOS,
        Integracao::SBS_CARROS,
        Integracao::USADOSBR,
        Integracao::COMPRECAR,
        Integracao::SEMINOVOS_HONDA,
        Integracao::CHAVESNAMAO
    ];

    const INTEGRACOES_ANUNCIO_SEM_FILA = [
        Integracao::AUTOMOTIVO_SHOPPING,
        //Integracao::MEUCARRONOVO,
        Integracao::SBS_CARROS,
        Integracao::ICARROS,
        Integracao::WEBMOTORS,
        Integracao::SOCARRAO,
        Integracao::OLX,
        Integracao::AUTOLINE,
        Integracao::MERCADOLIVRE,
        Integracao::USADOSBR,
        Integracao::CARROSP,
        Integracao::VALE_AUTO_SHOPPING,
        Integracao::SEMINOVOSBH,
        Integracao::MOBIAUTO,
        Integracao::CATARINACARROS,
        Integracao::COMPRECAR,
        Integracao::SEMINOVOS_HONDA,
        Integracao::SHOPCAR,
        Integracao::LITORALCAR,
        Integracao::CHAVESNAMAO,
        Integracao::NA_PISTA
    ];

    const INTEGRACOES_LEAD = [
        Integracao::AUTOLINE,
        Integracao::CHAVESNAMAO,
        Integracao::ICARROS,
        Integracao::MERCADOLIVRE,
        //Integracao::MEUCARRONOVO,
        Integracao::MOBIAUTO,
        Integracao::OLX,
        Integracao::PORTALAUTOSHOPPING,
        Integracao::SEMINOVOSBH,
        Integracao::SHOPCAR,
        Integracao::SOCARRAO,
        Integracao::WEBMOTORS,
        Integracao::PHONETRACK,
        Integracao::SIRENA,
        Integracao::VRUM,
        Integracao::BIP,
        Integracao::LITORALCAR,
        Integracao::ZUL,
        Integracao::USADOSBR,
        Integracao::ECOMPLETO,
        //Integracao::TAKE_BLIP,
        Integracao::VALE_AUTO_SHOPPING,
        Integracao::AUTOMOTIVO_SHOPPING,
        Integracao::CARROSP,
        Integracao::KARVI,
        //Integracao::COMPRE_BLINDADOS,
        Integracao::RD_STATION,
        Integracao::MEUBOT,
        Integracao::RECICLALEAD,
        Integracao::DUOTALK,
        Integracao::EASY_CHANNEL,
        Integracao::EU_QUERO_CARROS,
        Integracao::LEADSOK,
        Integracao::COMPRECAR,
        Integracao::SEMINOVOS_HONDA,
        Integracao::NA_PISTA,
        Integracao::CATARINACARROS,
        Integracao::CREDERE
    ];

    //TODO
    const INTEGRACOES_MOTO = [
        Integracao::OLX,
        Integracao::CARROSP,
        Integracao::SOCARRAO,
        Integracao::SBS_CARROS,
        Integracao::USADOSBR,
        Integracao::MERCADOLIVRE,
        //Integracao::MEUCARRONOVO,
        Integracao::SEMINOVOSBH,
        Integracao::WEBMOTORS,
        Integracao::MOBIAUTO,
        Integracao::CATARINACARROS,
        Integracao::COMPRECAR,
        Integracao::SHOPCAR,
        Integracao::LITORALCAR,
        Integracao::CHAVESNAMAO,
        Integracao::KARVI,
        Integracao::NA_PISTA,
        Integracao::AUTOLINE,
    ];

    //TODO
    const INTEGRACOES_CAMINHAO = [
        Integracao::OLX,
        Integracao::MERCADOLIVRE,
        Integracao::USADOSBR,
        Integracao::SOCARRAO,
        //Integracao::MEUCARRONOVO,
        Integracao::MOBIAUTO,
        Integracao::CATARINACARROS,
        Integracao::COMPRECAR,
        Integracao::SHOPCAR,
        Integracao::LITORALCAR,
        Integracao::KARVI,
        Integracao::NA_PISTA
    ];

    const INTEGRACOES_ACTIVATED_IN_LEAD = [
        Integracao::SIRENA,
        //Integracao::TAKE_BLIP,
        //Integracao::KARVI,
        Integracao::RD_STATION,
        Integracao::MEUBOT,
        Integracao::DUOTALK,
        Integracao::CREDERE,
        Integracao::RECICLALEAD,
        Integracao::EASY_CHANNEL,
        Integracao::EU_QUERO_CARROS,
        Integracao::LEADSOK,
        Integracao::PHONETRACK,
        Integracao::FACEBOOK_LEAD_ADS
    ];

    const INTEGRACOES_AVALIACAO = [
        Integracao::SANCES,
        Integracao::AUTOAVALIAR,
        Integracao::AUTOCORP,
        Integracao::SANCES,
        Integracao::APOLLO,
        Integracao::E_FICAZZ,
        Integracao::UNION_SOLUTIONS
    ];

    const INTEGRACOES_NEGOCIACAO = [
        Integracao::FOCUS_NFE,
        Integracao::CLICKSIGN,
        Integracao::LIBERTY_SEGUROS,
        Integracao::MAQUINETA_VIRTUAL,
        Integracao::K8_BANK,
        Integracao::ITURAN,
        Integracao::GESTAUTO,
        Integracao::ITURANLEAD,
        Integracao::RENAVE_AUTO,
        Integracao::CORRECTDATA,
        Integracao::DEBITO_DIRETO
    ];

    const INTEGRACOES_LEAD_MULTI_CONTA = [
        Integracao::WEBMOTORS,
        Integracao::OLX,
        //Integracao::MEUCARRONOVO,
        Integracao::MOBIAUTO,
        Integracao::MERCADOLIVRE
    ];

    const INTEGRACOES_SEM_MULTI_CONTA = [
        Integracao::FACEBOOK,
        Integracao::GOOGLE_MERCHANT_CENTER,
        Integracao::AUTOMOTIVO_SHOPPING,
        Integracao::VALE_AUTO_SHOPPING,
        Integracao::PORTALAUTOSHOPPING,
        Integracao::VRUM,
        Integracao::KARVI,
        Integracao::AUTO_BUSINESS,
        Integracao::DIGICAR
    ];
    const INTEGRACOES_FILA_JOBS = [
        Integracao::SBS_CARROS,
        Integracao::ICARROS,
        Integracao::WEBMOTORS,
        Integracao::OLX,
        Integracao::AUTOLINE,
        Integracao::MERCADOLIVRE,
        Integracao::SOCARRAO,
        //Integracao::MEUCARRONOVO,
        Integracao::USADOSBR,
        Integracao::SEMINOVOSBH,
        Integracao::MOBIAUTO,
        Integracao::CATARINACARROS,
        Integracao::COMPRECAR,
        Integracao::SEMINOVOS_HONDA,
        Integracao::SHOPCAR,
        Integracao::LITORALCAR,
        Integracao::CHAVESNAMAO,
        Integracao::NA_PISTA
    ];

    const INTEGRACOES_CONSULTAR_ESTOQUE = [
        Integracao::ICARROS,
        Integracao::WEBMOTORS,
        Integracao::OLX,
        Integracao::AUTOLINE,
        Integracao::SOCARRAO,
        //Integracao::MEUCARRONOVO,
        Integracao::USADOSBR,
        Integracao::MOBIAUTO,
        Integracao::CHAVESNAMAO,
        Integracao::LITORALCAR,
        Integracao::COMPRECAR,
        Integracao::NA_PISTA,
        Integracao::MERCADOLIVRE
    ];

    const INTEGRACOES_EXTRAS = [
        Integracao::CUSTOMERX,
        Integracao::MAQUINETA_VIRTUAL,
        Integracao::K8_BANK,
    ];

    const INTEGRACOES_SENHA_PAINEL = [
        Integracao::WEBMOTORS,
        Integracao::USADOSBR,
        Integracao::CATARINACARROS,
        Integracao::LITORALCAR,
        Integracao::NA_PISTA,
    ];

    const CONFIGURACAO_ABERTA = [
        Integracao::ICARROS,
        Integracao::OLX,
        Integracao::AUTOLINE,
        Integracao::MERCADOLIVRE,
        //Integracao::MEUCARRONOVO,
        Integracao::COMPRECAR,
        Integracao::SEMINOVOS_HONDA,
        Integracao::SHOPCAR,
        Integracao::LITORALCAR,
        Integracao::CHAVESNAMAO,
        Integracao::SOCARRAO,
        Integracao::CATARINACARROS,
        Integracao::MOBIAUTO,
        Integracao::SEMINOVOSBH,
        Integracao::USADOSBR,
        Integracao::WEBMOTORS,
        Integracao::NA_PISTA,
    ];

    const CONFIGURACAO_ABERTA_LEAD = [
        Integracao::RD_STATION,
       // Integracao::MEUBOT,
        Integracao::AUTOLINE,
        Integracao::CHAVESNAMAO,
        Integracao::DUOTALK,
        Integracao::RECICLALEAD,
        Integracao::EASY_CHANNEL,
        Integracao::ICARROS,
        Integracao::KARVI,
        Integracao::LEADSOK,
        Integracao::MERCADOLIVRE,
        Integracao::MOBIAUTO,
        Integracao::OLX,
        Integracao::USADOSBR,
        Integracao::EU_QUERO_CARROS,
        Integracao::NA_PISTA,
        Integracao::SOCARRAO,
        Integracao::WEBMOTORS,
        Integracao::FACEBOOK_LEAD_ADS,
        Integracao::CATARINACARROS,
    ];

    const CONFIGURACAO_ABERTA_LEAD_LINK = [
        Integracao::RD_STATION,
        Integracao::AUTOLINE,
        Integracao::CHAVESNAMAO,
        Integracao::DUOTALK,
        Integracao::RECICLALEAD,
        Integracao::EASY_CHANNEL,
        Integracao::ICARROS,
        Integracao::LEADSOK,
        Integracao::EU_QUERO_CARROS,
        Integracao::USADOSBR,
        Integracao::NA_PISTA,
        Integracao::SOCARRAO,
        Integracao::WEBMOTORS,
        Integracao::OLX,
    ];

    const INTEGRACOES_LEAD_QUADRO = [
        Integracao::AUTOLINE,
        Integracao::CHAVESNAMAO,
        Integracao::ICARROS,
        Integracao::MERCADOLIVRE,
        //Integracao::MEUCARRONOVO,
        Integracao::MOBIAUTO,
        Integracao::OLX,
        Integracao::PORTALAUTOSHOPPING,
        Integracao::SEMINOVOSBH,
        Integracao::SHOPCAR,
        Integracao::SOCARRAO,
        Integracao::WEBMOTORS,
        Integracao::VRUM,
        Integracao::BIP,
        Integracao::LITORALCAR,
        Integracao::ZUL,
        Integracao::USADOSBR,
        Integracao::ECOMPLETO,
        //Integracao::TAKE_BLIP,
        Integracao::VALE_AUTO_SHOPPING,
        Integracao::AUTOMOTIVO_SHOPPING,
        Integracao::CARROSP,
        Integracao::KARVI,
        //Integracao::COMPRE_BLINDADOS,
        Integracao::FACEBOOK,
        Integracao::DUOTALK,
        Integracao::RECICLALEAD,
        Integracao::EASY_CHANNEL,
        Integracao::PHONETRACK,
        Integracao::COMPRECAR,
        Integracao::NA_PISTA,
        Integracao::MEUBOT,
        Integracao::SEMINOVOS_HONDA
    ];

    const SYNC_ANUNIO = [
        Integracao::CHAVESNAMAO,
        Integracao::MERCADOLIVRE,
        Integracao::SOCARRAO,
        Integracao::MOBIAUTO
    ];

    const HAS_DESTAQUE = [
        Integracao::ICARROS,
        Integracao::WEBMOTORS,
        Integracao::SOCARRAO,
        Integracao::MERCADOLIVRE,
        Integracao::NA_PISTA,
        Integracao::MOBIAUTO,
        Integracao::USADOSBR,
    ];

    // Assuntos de e-mail considerodos comos span
    const SUBJECT_ICARROS = [
        "você tem ligações perdidas",
        "iCarros: Ligação recebida",
        "Atualize o seu estoque",
        "Você tem ligações perdidas.",
        "Boleto",
        "voc\u00ea precisa ter",
        "você precisa ter",
        "só falta você!",
        "falta voc\u00ea!",
        "pessoas andam",
        "quer ter sua loja",
        "seguimos",
        "juntos",
        "como foi sua",
        "desbloqueado",
        'lembrete',
        'destaque',
        'desbloqueado',
        'natal',
        'presente'
    ];

    // Assuntos de e-mail considerodos comos span
    const SUBJECT_MERCADO_LIVRE = [
        "pergunta para",
        "volte por",
        "voc\u00ea",
        "você",
        "Verifique sua conta",
        "Sua compra",
        "sua compra",
        "Finalize a sua compra",
        "A NF-e",
        "Confirme",
        "paquete",
        "boleto",
        "consulte",
        "compra",
        "produtos",
        "Finalize",
        "Tivemos",
        "Confirme",
        "Você precisa",
        "mais vendidos",
        "pacote",
        "Novos anúncios",
        "Novos an\u00fancios",
        "queremos",
        "confirmar",
        "concluir",
        "faturamento",
        "cancelados",
        "vencer em breve",
        "Gerencie a qualidade",
        "placa incorreta",
        "Alerta de segurança",
        "\u00a1Ajude-nos a melhorar",
        "Ajude-nos a melhorar",
        "Promotions",
        "fatura"
    ];

    // Assuntos de e-mail considerodos comos span
    const SUBJECT_RD = [
        "ud83d",
        "🐔🐔🐣🐣🐣🐣🐣🐔🐣🐔🐔🐔🐔"
    ];

    // Assuntos de e-mail considerodos comos span
    const SUBJECT_USADOSBR = [
        'Vence hoje: Boleto Usadosbr',
        'Lembrete de cobrança.',
        'Lembrete de cobran\u00e7a.',
        "Cadastro de anuncio :: Usadosbr",
        "Pague seu boleto antecipado e ganhe Destaques! Ultimo m\u00eas!",
        "Pague seu boleto antecipado e ganhe Destaques! Ultimo mês!",
        "Lead Super Quente!",
        "Vencido: Boleto",
        "3 feirões em 1 | Auto Festival",
        "confira pre\u00e7o,",
        "confira preço,",
        "SUSPENSÂO!",
        "sobre",
        "mais vendidos",
        "mais buscados",
        "esteira",
        "A melhor",
        "Meu Carro On",
        "velocidade"
    ];

    const SUBJECT_CARROSP = [
        'Limite',
        'limite',
        'Aviso de Vencimento',
    ];

    const SUBJECT_SOCARRAO = [
        'golpes',
        'revendedores'
    ];

    /**
     * The relationship with Status model (has one).
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function revendas()
    {
        return $this->belongsToMany(Revenda::class, 'revenda_integracao', 'integracao_id', 'revenda_id')
            ->withPivot('status_id', 'tipo', 'email', 'senha', 'dealer_id', 'client_id', 'client_secret', 'code', 'authorization_code', 'refresh_token','redirect_uri');
    }

    /**
     * The relationship with Integracao model (has one).
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function revendaIntegracao()
    {
        return $this->belongsTo(RevendaIntegracao::class, 'integracao_id');
    }

    /**
     * The relationship with Atendimento model (belongs to).
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class);
    }

    /**
     * @param $subject
     * @param $integracao
     * Metodo responsavel por validar os emails considerados como span
     * @return bool
     */
    public static function isValidateEmail($subject, $integracao): bool
    {
        $str_contains = false;
        $integracao_id = $integracao->id;

        if($subject){
            switch ($integracao_id) {
                case Self::ICARROS:
                    $str_contains =  Str::contains($subject, Integracao::SUBJECT_ICARROS);
                    break;
                case Self::MERCADOLIVRE:
                    $str_contains =  Str::contains($subject, Integracao::SUBJECT_MERCADO_LIVRE);
                    break;
                case Self::CARROSP:
                    $str_contains =  Str::contains($subject, Integracao::SUBJECT_CARROSP);
                    break;
                case Self::MOBIAUTO:
                    $str_contains = true;
                    break;
                case Self::USADOSBR:
                    $str_contains =  Str::contains($subject, Integracao::SUBJECT_USADOSBR);
                    break;
                case Self::RD_STATION:
                    $str_contains =  Str::contains($subject, Integracao::SUBJECT_RD);
                    break;
                case Self::SOCARRAO:
                    $str_contains =  Str::contains($subject, Integracao::SUBJECT_SOCARRAO);
                    break;
                default:
                    $str_contains = false;
                    break;
            }
        }

        return $str_contains;
    }

    /**
     * Função auxiliar para validar se uma revenda possui integrações de Estoque Site (WebMotors, Altimus, Boom Sistemas)
     * @param \App\Models\Revenda\Revenda $revenda
     * @return bool
     */
    public static function hasIntegracoesEstoqueSite(Revenda $revenda): bool
    {
        // count da tabela revenda integração
        if(RevendaIntegracao::where('revenda_id', $revenda->id)
            ->whereIn('integracao_id', SELF::INTEGRACOES_ESTOQUE_SITE)
            ->where('status_id', Status::ATIVO)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getIntegracaoById($revenda_integracao_id)
    {
        // integracoes ativas
        return DB::table('revenda')
            ->join('revenda_integracao', 'revenda_integracao.revenda_id', '=', 'revenda.id')
            ->join('integracao', 'revenda_integracao.integracao_id', '=', 'integracao.id')
            ->where('revenda_integracao.id', $revenda_integracao_id)
            ->select(
                'revenda_integracao.id as revenda_integracao_id',
                'integracao.id',
                'integracao.nome',
                'integracao.slug',
                'revenda_integracao.revenda_id',
                'revenda_integracao.descricao',
            )->first();
    }

    public static function getContainer($integracao_id)
    {
        $container = null;
        if($integracao_id){
            switch ($integracao_id) {
                case Self::ICARROS:
                    $container = "Icarros";
                break;

                case Self::AUTOLINE:
                    $container = "Autoline";
                break;

                case Self::WEBMOTORS:
                    $container = "WebMotors";
                break;

                case Self::SOCARRAO:
                    $container = "SoCarrao";
                break;

                case Self::OLX:
                    $container = "Olx";
                break;

                case Self::MERCADOLIVRE:
                    $container = "MercadoLivre";
                break;

                /*case Self::MEUCARRONOVO:
                    $container = "MeuCarroNovo";
                break;*/

                case Self::NA_PISTA:
                    $container = "NaPista";
                    break;

                case Self::CHAVESNAMAO:
                    $container = "ChavesNaMao";
                break;

                case Self::CATARINACARROS:
                    $container = "CatarinaCarros";
                break;

                case Self::MOBIAUTO:
                    $container = "Mobiauto";
                break;

                case Self::SEMINOVOSBH:
                    $container = "SeminovosBh";
                break;

                case Self::SHOPCAR:
                    $container = "ShopCar";
                break;

                /*
                case Self::OBUSCA:
                    $container = "Obusca";
                break;
                */

                case Self::CARROSP:
                    $container = "CarroSP";
                break;

                case Self::LITORALCAR:
                    $container = "LitoralCar";
                break;

                /*
                case Self::COMPRE_BLINDADOS:
                    $container = "CompreBlindados";
                break;
                */

                case Self::SBS_CARROS:
                    $container = "SbsCarros";
                break;

                case Self::USADOSBR:
                    $container = "Usadosbr";
                break;

                case Self::AUTOMOTIVO_SHOPPING:
                    $container = "AutomotivoShopping";
                break;

                case Self::VALE_AUTO_SHOPPING:
                    $container = "ValeAutoShopping";
                break;

                case Self::COMPRECAR:
                    $container = "Comprecar";
                break;

                case Self::SEMINOVOS_HONDA:
                    $container = "SeminovosHonda";
                break;

                case Self::KARVI:
                    $container = "Karvi";
                break;
            }
        }

        return $container;
    }

    // Metodo responsavel por criar a url de visualização do anúncio.
    public static function getPreviewAnuncio($integracao_id, $veiculo = null)
    {
        $url = "";
        switch ($integracao_id) {
            case Self::ICARROS:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://integrador.icarros.com.br/ache/detalhes.jsp?id='.$veiculo["uid"];
                }
            break;

            case Self::AUTOLINE:
                if (!empty($veiculo["uid"])) {
                    $slugAutoline = \App\Models\Autoline\ModeloVersao::getSlugAutoline($veiculo["versao_integracao_id"] ?? null, $veiculo);
                    //dd($slugAutoline);
                    $url = 'https://autoline.com.br/' . $slugAutoline. '/'. $veiculo["uid"];
                }
            break;

            case Self::WEBMOTORS:
                $slugWebmotors = \App\Models\WebMotors\Versao::getSlugWebmotors($veiculo["versao_integracao_id"] ?? null, $veiculo);
                if ($veiculo["tipo_id"] == \App\Models\Veiculo\Tipo::MOTOS) {
                    $slugWebmotors = \App\Models\WebMotors\Modelo::getSlugWebmotorsMotos($veiculo["versao_integracao_id"] ?? null, $veiculo);
                }
                if (!empty($veiculo["uid"])) {
                    $url = 'https://www.webmotors.com.br/'.$slugWebmotors.'/'.$veiculo["uid"];
                }
            break;

            case Self::SOCARRAO:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://www.socarrao.com.br/veiculos/detalhes/'.$veiculo["uid"];
                }
            break;

            case Self::OLX:
                if (!empty($veiculo["url"])) {
                    $url = 'https://www.olx.com.br/vi/'.$veiculo["url"].'.htm';
                }
            break;

            case Self::MERCADOLIVRE:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://carro.mercadolivre.com.br/' .str_replace('MLB', 'MLB-', $veiculo["uid"]).'-_JM';
                }
            break;

/*            case Self::MEUCARRONOVO:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://meucarronovo.com.br/carro/detalhe/'.$veiculo["uid"];
                }
            break;*/

            case Self::NA_PISTA:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://www.napista.com.br/anuncios-mcn/'. $veiculo["uid"];
                }
            break;

            case Self::CHAVESNAMAO:
                if (!empty($veiculo["url"])) {
                    $url = $veiculo["url"];
                }
            break;

            case Self::CATARINACARROS:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://catarinacarros.com.br/anuncio/'.$veiculo["uid"];
                }
            break;

            case Self::MOBIAUTO:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://www.mobiauto.com.br/details/'.$veiculo["uid"];
                }
            break;

            case Self::SEMINOVOSBH:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://seminovos.com.br/--'.$veiculo["uid"];
                }
            break;

            case Self::SHOPCAR:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://www.shopcar.com.br/view.php?id='.$veiculo["uid"];
                }
            break;

            /*
            case Self::OBUSCA:
                if (!empty($veiculo["uid"])) {
                    $url = 'https://obusca.com.br/veiculos/detalhes/anuncio/'.$veiculo["uid"];
                }
            break;
            */

            case Self::CARROSP:
                if (!empty($veiculo["uid"])) {
                    $url = '';
                }
            break;

            case Self::LITORALCAR:
                if (!empty($veiculo["uid"])) {
                    $cor = $veiculo["cor_nome"];
                    $combustivel = $veiculo["cor_nome"];
                    $modelo_nome = str_replace('!', '', normalize_text($veiculo["modelopai_nome"]));
                    $marca_nome = $veiculo["marca_nome"];
                    if($marca_nome == 'Citroën'){
                        $marca_nome = 'citroen';
                    }
                    if(!empty($veiculo["cor_nome"]) && !empty($veiculo["combustivel_nome"])){
                        $url = 'https://www.litoralcar.com.br/comprar/carros/' . $marca_nome . '/' . $modelo_nome . '/' .
                            $veiculo["anomodelo"] . '-' . $veiculo["cor_nome"] . '-' . $veiculo["combustivel_nome"] . '-brusque-sc/' . $veiculo["uid"];
                    }else{
                        $url = '';
                    }
                }
            break;

            /*
            case Self::COMPRE_BLINDADOS:
                if (!empty($veiculo["url"])) {
                    $url =  $veiculo["url"];
                }
            break;
            */

            case Self::SBS_CARROS:
                if (!empty($veiculo["url"])) {
                    $url =  $veiculo["url"];
                }
            break;

            case Self::SEMINOVOS_HONDA:
                if (!empty($veiculo["url"])) {
                    $url =  $veiculo["url"];
                }
            break;

            case Self::USADOSBR:
                if (!empty($veiculo["url"])) {
                    $url =  $veiculo["url"];
                }
            break;

            case Self::AUTOMOTIVO_SHOPPING:
                if (!empty($veiculo["url"])) {
                    $url =  $veiculo["url"];
                }
            break;

            case Self::VALE_AUTO_SHOPPING:
            break;

            case Self::KARVI:
            break;

            case Self::COMPRECAR:
                if (!empty($veiculo["url"])) {
                    $url =  $veiculo["url"];
                }
            break;
        }

        return $url;
    }

    // Metodo responsavel por recuperar os destaques dos anúncios.
    public static function getDestaqueAnuncio($integracao_id, $revenda_id = null, $revenda_integracao_id = null)
    {
        $destaque = [];
        switch ($integracao_id) {
            case Self::ICARROS:
                $destaqueIcarros = \App\Models\iCarros\Plano::where('revenda_integracao_id', $revenda_integracao_id )->get();
                if ($destaqueIcarros) {
                    foreach ($destaqueIcarros as $key => $value) {
                        $countDestaquePendente = Self::countDestaqueFila($value->id, $revenda_integracao_id);
                        $destaque["carros"][$key] = [
                            "descricao" => $value->nome ?? null,
                            "quantidade" => $value->quantidade ?? null,
                            "publicados" => $value->publicados ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "restante" => $value->livres ?? null,
                            "is_zerokm" => $value->zerokm ?? null,
                        ];
                    }

                }
            break;

            case Self::AUTOLINE:
                $destaque = [];
            break;

            case Self::WEBMOTORS:
                // validar destaques dinamicamente
                $destaquesCarrosWebmotors = \App\Models\WebMotors\Plano::where('revenda_integracao_id', $revenda_integracao_id)->get();
                if($destaquesCarrosWebmotors){
                    foreach ($destaquesCarrosWebmotors as $key => $destaqueCarro) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueCarro->codigoModalidade, $revenda_integracao_id);
                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueCarro->descricao ?? null,
                            "quantidade" => $destaqueCarro->quantidadeAnunciosTotal ?? null,
                            "publicados" => $destaqueCarro->quantidadeAnuncios ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "restante" => null,
                            "is_zerokm" => null,
                        ];
                    }
                }

                // validar destaques dinamicamente
                $destaquesMotosWebmotors = \App\Models\WebMotors\PlanoMoto::where('revenda_integracao_id', $revenda_integracao_id)->get();
                if($destaquesMotosWebmotors){
                    foreach ($destaquesMotosWebmotors as $key => $destaqueMoto) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueMoto->codigoModalidade, $revenda_integracao_id);
                        $destaque["motos"][$key] = [
                            "descricao" => $destaqueMoto->descricao ?? null,
                            "quantidade" => $destaqueMoto->quantidadeAnunciosTotal ?? null,
                            "publicados" => $destaqueMoto->quantidadeAnuncios ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "restante" => null,
                            "is_zerokm" => $destaqueMoto->tipoAnuncio ?? null,
                        ];
                    }
                }
            break;

            case Self::SOCARRAO:
                $destaquesSoCarrao = \App\Models\SoCarrao\Destaque::where('revenda_integracao_id', $revenda_integracao_id )->get();
                if($destaquesSoCarrao){
                    foreach ($destaquesSoCarrao as $key => $destaqueSoCarrao) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueSoCarrao->destaque_id, $revenda_integracao_id);
                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueSoCarrao->destaque_nome ?? null,
                            "quantidade" => $destaqueSoCarrao->total ?? null,
                            "publicados" => $destaqueSoCarrao->used ?? null,
                            "restante" => $destaqueSoCarrao->available ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "is_zerokm" => null,
                        ];
                    }
                }
            break;

            case Self::OLX:
                $destaqueOlx = \App\Models\Olx\Pacote::where('revenda_integracao_id', $revenda_integracao_id)->first();
                $destaque["pacote_olx"]["title"] = $destaqueOlx->name;
                $destaque["pacote_olx"]["ads"] = [
                    "quantidade" => $destaqueOlx->ads_total ?? null,
                    "publicados" => $destaqueOlx->ads_performed ?? null,
                    "restante" => $destaqueOlx->ads_available ?? null,
                ];

                $destaque["pacote_olx"]["bumps"] = [
                    "quantidade" => $destaqueOlx->bumps_plan_total ?? null,
                    "publicados" => $destaqueOlx->bumps_plan_performed ?? null,
                    "restante" => $destaqueOlx->bumps_plan_available ?? null,
                ];
                break;

            case Self::MERCADOLIVRE:
                $destaquesMercadoLivre = \App\Models\MercadoLivre\Plano::where('revenda_integracao_id', $revenda_integracao_id)->where('status_ml_id', "active" )->where('date_expires', '>=', \Carbon\Carbon::now()->toDateTimeString())->get();
                if($destaquesMercadoLivre){
                    foreach ($destaquesMercadoLivre as $key => $destaqueMercadoLivre) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueMercadoLivre->plano_ml_id, $revenda_integracao_id);
                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueMercadoLivre->nome ?? null,
                            "quantidade" => $destaqueMercadoLivre->total ?? null,
                            "publicados" => $destaqueMercadoLivre->usando ?? null,
                            "restante" => $destaqueMercadoLivre->ativo ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "is_zerokm" => null,
                        ];
                    }
                }
            break;

/*            case Self::MEUCARRONOVO:
                $destaquesMeuCarroNovo = \App\Models\MeuCarroNovo\Plano::where('revenda_integracao_id', $revenda_integracao_id)->get();
                if($destaquesMeuCarroNovo){
                    foreach ($destaquesMeuCarroNovo as $key => $destaqueMeuCarroNovo) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueMeuCarroNovo->destaque_id, $revenda_integracao_id);
                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueMeuCarroNovo->descricao ?? null,
                            "quantidade" => $destaqueMeuCarroNovo->qtd_contratada ?? null,
                            "publicados" => $destaqueMeuCarroNovo->qtd_utilizada ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "restante" => null,
                            "is_zerokm" => null,
                        ];
                    }
                }

                $pacoteAnuncioMeuCarroNovo = \App\Models\MeuCarroNovo\PacoteAnuncio::where('revenda_integracao_id', $revenda_integracao_id)->first();
                if($pacoteAnuncioMeuCarroNovo){
                    $destaque["pacote"] = [
                        "descricao" => $pacoteAnuncioMeuCarroNovo->combo ?? null,
                        "quantidade" => $pacoteAnuncioMeuCarroNovo->qtd_anuncios ?? null,
                        "publicados" => $pacoteAnuncioMeuCarroNovo->publicado ?? null,
                        "pausado" => $pacoteAnuncioMeuCarroNovo->pausado ?? null,
                        "vigencia_inicio" => $pacoteAnuncioMeuCarroNovo->vigencia_inicio ?? null,
                        "vigencia_fim" => $pacoteAnuncioMeuCarroNovo->vigencia_fim ?? null,
                    ];
                }
            break;*/

            case Self::NA_PISTA:
                $destaquesNaPista = \App\Models\NaPista\Plano::where('revenda_integracao_id', $revenda_integracao_id)->get();
                if($destaquesNaPista){
                    foreach ($destaquesNaPista as $key => $destaqueNaPista) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueNaPista->destaque_id, $revenda_integracao_id);
                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueNaPista->descricao ?? null,
                            "quantidade" => $destaqueNaPista->qtd_contratada ?? null,
                            "publicados" => $destaqueNaPista->qtd_utilizada ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "restante" => null,
                            "is_zerokm" => null,
                        ];
                    }
                }

                $pacoteAnuncioNaPista = \App\Models\NaPista\PacoteAnuncio::where('revenda_integracao_id', $revenda_integracao_id)->first();
                if($pacoteAnuncioNaPista){
                    $destaque["pacote"] = [
                        "descricao" => $pacoteAnuncioNaPista->combo ?? null,
                        "quantidade" => $pacoteAnuncioNaPista->qtd_anuncios ?? null,
                        "publicados" => $pacoteAnuncioNaPista->publicado ?? null,
                        "pausado" => $pacoteAnuncioNaPista->pausado ?? null,
                        "vigencia_inicio" => $pacoteAnuncioNaPista->vigencia_inicio ?? null,
                        "vigencia_fim" => $pacoteAnuncioNaPista->vigencia_fim ?? null,
                    ];
                }
            break;

            case Self::CHAVESNAMAO:
                $destaque = [];
            break;

            case Self::CATARINACARROS:
                $destaque = [];
            break;

            case Self::MOBIAUTO:
                $destaquesMobiauto = \App\Models\Mobiauto\Destaque::where('revenda_integracao_id', $revenda_integracao_id)->get();
                if($destaquesMobiauto){
                    foreach ($destaquesMobiauto as $key => $destaqueMobiauto) {
                        $quantity = $destaqueMobiauto->quantity ?? 0;
                        $quantity_available = $destaqueMobiauto->quantity_available ?? 0;
                        $publicados = $quantity - $quantity_available;
                        $countDestaquePendente = Self::countDestaqueFila($destaqueMobiauto->deal_plan_id, $revenda_integracao_id);

                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueMobiauto->nome ?? null,
                            "quantidade" => $destaqueMobiauto->quantity ?? null,
                            "publicados" => $publicados ?? null,
                            "restante" => $quantity_available,
                            "pendente" => $countDestaquePendente ?? null,
                            "is_zerokm" => null,
                        ];
                    }
                }
            break;

            case Self::SEMINOVOSBH:
                $destaque = [];
            break;

            case Self::SHOPCAR:
                $destaque = [];
            break;

            /*
            case Self::OBUSCA:
                $destaque = [];
            break;
            */

            case Self::CARROSP:
                $destaque = [];
            break;

            case Self::LITORALCAR:
                $destaque = [];
            break;

            /*
            case Self::COMPRE_BLINDADOS:
                $destaque = [];
            break;
            */

            case Self::SBS_CARROS:
                $destaque = [];
            break;

            case Self::USADOSBR:
                $destaquesUsadosbr = \App\Models\Usadosbr\Plano::where('revenda_id', $revenda_id )->get();
                if($destaquesUsadosbr){
                    foreach ($destaquesUsadosbr as $key => $destaqueUsadosbr) {
                        $countDestaquePendente = Self::countDestaqueFila($destaqueUsadosbr->destaque_id, $revenda_integracao_id);

                        $destaque["carros"][$key] = [
                            "descricao" => $destaqueUsadosbr->nome ?? null,
                            "quantidade" => $destaqueUsadosbr->total ?? null,
                            "publicados" => $destaqueUsadosbr->ativo ?? null,
                            "pendente" => $countDestaquePendente ?? null,
                            "restante" => null,
                            "is_zerokm" => null,
                        ];
                    }
                }
            break;

            case Self::AUTOMOTIVO_SHOPPING:
                $destaque = [];
            break;

            case Self::VALE_AUTO_SHOPPING:
                $destaque = [];
            break;

            case Self::KARVI:
                $destaque = [];
            break;

            case Self::COMPRECAR:
                $destaque = [];
            break;
        }

        return $destaque;
    }

    // Metodo responsavel por recuperar os destaques dos anúncios para ser exibido no filtro.
    public static function getDestaques($revenda_integracao_id = null): array
    {
        if(empty($revenda_integracao_id)) {
            return [];
        }

        $revendaIntegracao = RevendaIntegracao::find($revenda_integracao_id);

        $destaque = [];
        switch ($revendaIntegracao->integracao_id) {
            case Self::ICARROS:
                $destaqueIcarros = \App\Models\iCarros\Plano::where('revenda_integracao_id', $revendaIntegracao->id )->get();
                if ($destaqueIcarros) {
                    foreach ($destaqueIcarros as $key => $value) {
                        $destaque["carros"][$key] = [
                            "id" => $value->id ?? null,
                            "descricao" => $value->nome ?? null,
                            "is_zerokm" => $value->zerokm == 0 ? 0 : 1,
                        ];
                    }
                }
            break;

            case Self::WEBMOTORS:
                // validar destaques dinamicamente
                $destaquesCarrosWebmotors = \App\Models\WebMotors\Plano::where('revenda_integracao_id', $revendaIntegracao->id)->get();
                if($destaquesCarrosWebmotors){
                    foreach ($destaquesCarrosWebmotors as $key => $destaqueCarro) {
                        $destaque["carros"][$key] = [
                            "id" => $destaqueCarro->codigoModalidade ?? null,
                            "descricao" => $destaqueCarro->descricao ?? null,
                            "is_zerokm" =>  null,
                        ];
                    }
                }

                // validar destaques dinamicamente
                $destaquesMotosWebmotors = \App\Models\WebMotors\PlanoMoto::where('revenda_integracao_id', $revendaIntegracao->id)->get();
                if($destaquesMotosWebmotors){
                    foreach ($destaquesMotosWebmotors as $key => $destaqueMoto) {
                        $destaque["motos"][$key] = [
                            "id" => $destaqueMoto->codigoModalidade ?? null,
                            "descricao" => $destaqueMoto->descricao ?? null,
                            "is_zerokm" => $destaqueMoto->tipoAnuncio == "N" ? 1 : 0,
                        ];
                    }
                }
            break;

            case Self::SOCARRAO:
                $destaquesSoCarrao = \App\Models\SoCarrao\Destaque::where('revenda_integracao_id', $revendaIntegracao->id )->get();
                if($destaquesSoCarrao){
                    foreach ($destaquesSoCarrao as $key => $destaqueSoCarrao) {
                        $destaque["carros"][$key] = [
                            "id" => $destaqueSoCarrao->destaque_id ?? null,
                            "descricao" => $destaqueSoCarrao->destaque_nome ?? null,
                            "is_zerokm" => '',
                        ];
                    }
                }
            break;

            case Self::MERCADOLIVRE:
                $destaquesMercadoLivre = \App\Models\MercadoLivre\Plano::where('revenda_integracao_id', $revendaIntegracao->id)->where('status_ml_id', "active" )->where('date_expires', '>=', \Carbon\Carbon::now()->toDateTimeString())->get();
                if($destaquesMercadoLivre){
                    foreach ($destaquesMercadoLivre as $key => $destaqueMercadoLivre) {
                        $destaque["carros"][$key] = [
                            "id" => $destaqueMercadoLivre->plano_ml_id ?? null,
                            "descricao" => $destaqueMercadoLivre->nome ?? null,
                            "is_zerokm" => '',
                        ];
                    }
                }
            break;

            case Self::NA_PISTA:
                $destaquesNaPista = \App\Models\NaPista\Plano::where('revenda_integracao_id', $revendaIntegracao->id)->get();
                if($destaquesNaPista){
                    foreach ($destaquesNaPista as $key => $destaqueNaPista) {
                        $destaque["carros"][$key] = [
                            "id" => $destaqueNaPista->destaque_id ?? null,
                            "descricao" => $destaqueNaPista->descricao ?? null,
                            "is_zerokm" => '',
                        ];
                    }
                }
            break;

            case Self::MOBIAUTO:
                $destaquesMobiauto = \App\Models\Mobiauto\Destaque::where('revenda_integracao_id', $revendaIntegracao->id)->get();
                if($destaquesMobiauto){
                    foreach ($destaquesMobiauto as $key => $destaqueMobiauto) {
                        $destaque["carros"][$key] = [
                            "id" => $destaqueMobiauto->deal_plan_id ?? null,
                            "descricao" => $destaqueMobiauto->nome ?? null,
                            "is_zerokm" => isset($destaqueMobiauto->plan_zero_km) && $destaqueMobiauto->plan_zero_km == 1 ? 1 : 0,
                        ];
                    }
                }
            break;

            case Self::USADOSBR:
                $destaquesUsadosbr = \App\Models\Usadosbr\Plano::where('revenda_id', $revendaIntegracao->revenda_id )->get();
                if($destaquesUsadosbr){
                    foreach ($destaquesUsadosbr as $key => $destaqueUsadosbr) {

                        $destaque["carros"][$key] = [
                            "id" => $destaqueMobiauto->destaque_id ?? null,
                            "descricao" => $destaqueUsadosbr->nome ?? null,
                            "is_zerokm" => '',
                        ];
                    }
                }
            break;
            default:
                $destaque = [];
        }

        return $destaque;
    }

    public static function getDestaqueText($revenda_integracao_id = null, $destaque_id = null): string
    {
        if(empty($revenda_integracao_id) || empty($destaque_id)) {
            return "Não Informado";
        }

        $revendaIntegracao = RevendaIntegracao::find($revenda_integracao_id);

        $destaque = "";
        switch ($revendaIntegracao->integracao_id) {
            case Self::ICARROS:
                $destaque = \App\Models\iCarros\Plano::where('revenda_integracao_id', $revendaIntegracao->id )->where("id", $destaque_id)->first()->nome ?? "Não Informado";
                break;

            case Self::WEBMOTORS:
                // validar destaques dinamicamente
                $destaque = \App\Models\WebMotors\Plano::where('revenda_integracao_id', $revendaIntegracao->id)->where("codigoModalidade", $destaque_id)->first()->descricao ?? "Não Informado";

                if(empty($destaque)) {
                    $destaque = \App\Models\WebMotors\PlanoMoto::where('revenda_integracao_id', $revendaIntegracao->id)->where("codigoModalidade", $destaque_id)->first()->descricao ?? "Não Informado";
                }
                break;

            case Self::SOCARRAO:
                $destaque =\App\Models\SoCarrao\Destaque::where('revenda_integracao_id', $revendaIntegracao->id )->where("destaque_id", $destaque_id)->first()->destaque_nome ?? "Não Informado";
                break;

            case Self::MERCADOLIVRE:
                $destaque = \App\Models\MercadoLivre\Plano::where('revenda_integracao_id', $revendaIntegracao->id)->where("plano_ml_id", $destaque_id)->first()->nome ?? "Não Informado";
                break;

            case Self::NA_PISTA:
                $destaque = \App\Models\NaPista\Plano::where('revenda_integracao_id', $revendaIntegracao->id)->where("destaque_id", $destaque_id)->first()->descricao ?? "Não Informado";
                break;

            case Self::MOBIAUTO:
                $destaque = \App\Models\Mobiauto\Destaque::where('revenda_integracao_id', $revendaIntegracao->id)->where("deal_plan_id", $destaque_id)->first()->nome ?? "Não Informado";
                break;

            case Self::USADOSBR:
                $destaque = \App\Models\Usadosbr\Plano::where('revenda_id', $revendaIntegracao->revenda_id )->where("destaque_id", $destaque_id)->first()->nome ?? "Não Informado";
                break;

            default:
                $destaque = "Não Informado";
        }

        return $destaque;
    }

    /**
     * Metodo por validar se existe atendimento vinculado ao cliente antes de gerar um novo
     * Caso exista o metodo apenas adiciona os restante das informações no historico dos atendimentos.
     *
     * @param $revenda
     * @param $revendaIntegracao
     * @param $email
     * @param $integracao_nome
     * @param $mensagem
     * @param null $celular
     * @param null $nome
     * @param null $veiculo_id
     * @param null $audio
     * @param null $inbound_id
     * @param null $medium_id
     * @param null $source_id
     * @param null $content_id
     * @param null $lead_id
     *
     * @return bool|string
     */
    public static function getExistAtendimento($revenda, $revendaIntegracao, $email, $integracao_nome, $mensagem, $celular = null,
       $nome = null, $veiculo_id = null, $audio = null, $inbound_id = null, $origem_id = [], $lead_id = null): bool | string
    {
        if (in_array($revenda->plano_id, [Plano::PRO])){
            return false;
        }

        if (isset($celular)){
            $celular = preg_replace('/[^0-9]/', '', $celular);
        }

        $tem_atendimento = null;
        if (!empty($celular)) {
            $newPhone = Atendimento::addNonoDigito($celular);

            $tem_atendimento = Atendimento::atendimentoAtivos()->where('revenda_id', $revenda->id)
                ->where(function($query) use($celular, $newPhone){
                    $query->where('celular', $celular)
                        ->orWhere('celular', $newPhone);
                })
                ->where('email', $email)->orderBy('data', "DESC")->first();

            if (!$tem_atendimento) {
                $tem_atendimento = Atendimento::atendimentoAtivos()->where('revenda_id', $revenda->id)
                    ->where('celular', $celular)->where(function($query) use($celular, $newPhone){
                        $query->where('celular', $celular)
                            ->orWhere('celular', $newPhone);
                    })->orderBy('data', "DESC")->first();
            }

            if (!$tem_atendimento) {
                $tem_atendimento = Atendimento::atendimentoAtivos()->where('revenda_id', $revenda->id)
                    ->where(function($query) use($celular, $newPhone){
                        $query->where('telefone', $celular)
                            ->orWhere('telefone', $newPhone);
                    })->where('email', $email)->orderBy('data', "DESC")->first();
            }

            if (!$tem_atendimento) {
                $tem_atendimento = Atendimento::atendimentoAtivos()->where('revenda_id', $revenda->id)
                    ->where(function($query) use($celular, $newPhone){
                        $query->where('telefone', $celular)
                            ->orWhere('telefone', $newPhone);
                    })->orderBy('data', "DESC")->first();
            }

            if(!$tem_atendimento && !empty($email)){
                $tem_atendimento = Atendimento::atendimentoAtivos()->where('revenda_id', $revenda->id)
                    ->where('email', $email)->orderBy('data', "DESC")->first();
            }

        } else if (isset($email)) {
            $tem_atendimento = Atendimento::atendimentoAtivos()->where('revenda_id', $revenda->id)
                ->where('email', $email)->orderBy('data', "DESC")->first();
        }

        if($tem_atendimento && isset($revenda->revendaLead) && $revenda->revendaLead->consolidar_automatico == 1){
            $update_atendimento = [];
            if(!isset($tem_atendimento->email)) {
                $update_atendimento['email'] = $email;
            }

            if(!isset($tem_atendimento->celular)) {
                $update_atendimento['celular'] = $celular;
            }

            if (!empty($veiculo_id)) {
                AtendimentoVeiculo::createVeiculo($veiculo_id, $tem_atendimento->id);
            }
            /*if(!isset($tem_atendimento->veiculo_id) && isset($veiculo_id)) {
                $update_atendimento['veiculo_id'] = $veiculo_id;
            }*/

            if(!$tem_atendimento->audio && isset($audio)) {
                $update_atendimento['audio'] = $audio;
            }

            // Atualiza as informações do Atendimento caso seja necessário
            if(count($update_atendimento) > 0){
                $tem_atendimento->update($update_atendimento);
            }

            // Atualiza as informações do cliente caso seja necessário
            if($tem_atendimento->cliente_id) {
                $cliente = Cliente::where('revenda_id', $revenda->id)->where('id', $tem_atendimento->cliente_id)->first();

                $update_cliente = [];
                if(!isset($cliente->email) && isset($email)) {
                    $update_cliente['email'] = $email;
                }

                if(!isset($cliente->celular) && isset($celular)) {
                    $update_cliente['celular'] = $celular;

                }

                if(count($update_cliente) > 0){
                    $cliente->update($update_cliente);
                }
            }

            //Recuperas as informações do veículos
            $veiculo_observacao = Atendimento::getData($veiculo_id);

            $dado_cliente = "Dados do cliente <br>";
            if (isset($nome)) {
                $dado_cliente  = "Nome: " . $nome . " - ";
            }

            if (isset($email)) {
                $dado_cliente  .= "E-mail: " . $email . " - ";
            }

            if (isset($celular)) {
                $dado_cliente  .= "Celular: " . $celular;
            }

            if (isset($audio)) {
                $dado_cliente  .= '<br> Áudio: <audio controls class="mt-1 d-block"> <source src="'.$audio. '" type="audio/mpeg"> Seu navegador não suporta o elemento de áudio.</audio>';
            }

            $pre_view_mail = "";
            if (isset($inbound_id)) {
                $route = route('lead.atendimento.lead-preview-inbound', [$inbound_id, 'kanban' => 1, 'has_postmark' => true, 'integracao_id' => $revendaIntegracao->integracao_id]);
                $pre_view_mail  =  '<a href='.$route .' target="_blank" title="Ver mensagem original"><i class="fas fa-expand-arrows-alt"></i> </a>';
            }

            $observacao = "Portal: " . $integracao_nome . " ". $pre_view_mail . " <br> " .  $dado_cliente . " <br> " . $veiculo_observacao . " <br> Mensagem: " . $mensagem;

            $has_historico = HistoricoAtendimento::where('atendimento_id', $tem_atendimento->id)
                ->where('usuario_id', Usuario::USUARIO_SUPORTE)
                ->where('titulo', 'Atendimento Consolidado')
                ->where('observacao', $observacao)
                ->count();

            if ($has_historico > 0) {
                $slack = new \App\Models\Util\Slack();
                $slack->sendTesting('['. $integracao_nome .'] - '.$revenda->nome.' - Duplicado: '. $observacao);

                return in_array($integracao_nome, ["Easy Channel", "Lifty"]) ? (int) $tem_atendimento->id :  true;
            }

            if ($observacao == "Portal: Mobiauto <br> Veiculo não informado <br> Mensagem: ") {
                $slack = new \App\Models\Util\Slack();
                $slack->sendTesting('['. $integracao_nome .'] - '.$revenda->nome. ' #'. $tem_atendimento->id .' - Não é possivel consolidar esse atendimento: '. $observacao);
                return true;
            }

            HistoricoAtendimento::create([
                'titulo' => 'Atendimento Consolidado',
                'observacao' => remove_emoji($observacao),
                'atendimento_id' => $tem_atendimento->id,
                'usuario_id' => Usuario::USUARIO_SUPORTE,
                'data' => Carbon::now()
            ]);

            $integracao = Integracao::where('nome', $integracao_nome)->first();

            $integracao_id = $revendaIntegracao->integracao_id ?? $integracao->id;
            if(empty($origem_id) || count($origem_id) == 0) {
                $integracao_id = [$revendaIntegracao->sourceId($revendaIntegracao->integracao_id)] ?? [];
            }

            LeadConsolidado::storeLeads($tem_atendimento, $origem_id, $revendaIntegracao->integracao_id);

            $slack = new \App\Models\Util\Slack();
            $slack->sendLeads('['. $integracao_nome .'] - '.$revenda->nome. ' - #' . $tem_atendimento->id . ' - Atendimento Consolidado - Observação: '. $observacao);
            // envia notificação
            SendNotificacao::dispatch(Notificacao::SEND_CONSOLIDACAO, [
                'atendimento_id' => $tem_atendimento->id,
                'mensagem' => "O cliente enviou uma nova mensagem através do portal " . $integracao_nome
            ])->afterCommit();

            return in_array($integracao_nome, ["Easy Channel", "Lifty"]) ? (int) $tem_atendimento->id :  true;
        }
        //dd($revenda->revendaLead);
        return false;
    }

    /**
     * @param $destaque_id
     * @param $revenda_integracao_id
     * metodo responsavel por mostrar quantide de anúncios estão na fila com destaque.
     * @return int|null
     */
    public static function countDestaqueFila($destaque_id, $revenda_integracao_id): int | null
    {
        $count_anuncios = Anuncio::where('destaque', $destaque_id)->where('revenda_integracao_id', $revenda_integracao_id)
            ->where('status_id', Status::ANUNCIONAFILA)->count();

        return $count_anuncios;
    }

    public static function sendNewLeadIntegracao($atendimento, $revenda)
    {
        if (!App::environment('production')) {
            return;
        }

        $revendaIntegracaoFollowize = RevendaIntegracao::where('revenda_id', $revenda->id)->where('integracao_id', Integracao::FOLLOWIZE)->where('status_id', Status::ATIVO)->first();
        if ($revendaIntegracaoFollowize) {
            Integracao::senNewLeadFollowize($atendimento, $revendaIntegracaoFollowize);
        }

        $revendaIntegracaoLeadsOk = RevendaIntegracao::where('revenda_id', $revenda->id)->where('integracao_id', Integracao::LEADSOK)->where('status_id', Status::ATIVO)->first();
        if ($revendaIntegracaoLeadsOk) {
            Integracao::senNewLeadLeadsOk($atendimento, $revendaIntegracaoLeadsOk);
        }

        if (App::environment('production')) {
            Webhook::dispatchWebhookCrm($atendimento->id, $atendimento->revenda_id, Webhook::NEW_LEAD);
        }

    }
    public static function senNewLeadFollowize($atendimento, $revendaIntegracaoFollowize)
    {
        if ($revendaIntegracaoFollowize) {
            $apiClient = new ApiClient($revendaIntegracaoFollowize);
            $response = $apiClient->sendNewLead($atendimento);
        }

        return;
    }

    public static function senNewLeadLeadsOk($atendimento, $revendaIntegracaoLeadsOk)
    {
       // dd($revendaIntegracaoLeadsOk);
        if ($revendaIntegracaoLeadsOk) {
            $apiClient = new \App\Models\LeadsOk\ApiClient($revendaIntegracaoLeadsOk);
            $response = $apiClient->sendNewLead($atendimento);
        }

        return;
    }

    public static function syncIntegracaoAds(RevendaIntegracao $revendaIntegracao)
    {
        if($revendaIntegracao->integracao_id == Self::CHAVESNAMAO) {
            \App\Jobs\Ads\Sync\ChavesNaMao\SyncEstoque::dispatch($revendaIntegracao->id, auth()->user()->id);
        }elseif($revendaIntegracao->integracao_id == Self::SOCARRAO) {
            \App\Jobs\Ads\Sync\SoCarrao\SyncEstoque::dispatch($revendaIntegracao->id, auth()->user()->id);
        }elseif($revendaIntegracao->integracao_id == Self::MOBIAUTO) {
            \App\Jobs\Ads\Sync\Mobiauto\SyncEstoque::dispatch($revendaIntegracao->id, auth()->user()->id);
        }elseif($revendaIntegracao->integracao_id == Self::MERCADOLIVRE) {
            \App\Jobs\Ads\Sync\MercadoLivre\SyncEstoque::dispatch($revendaIntegracao->id, auth()->user()->id);
        }

        return;
    }
}
