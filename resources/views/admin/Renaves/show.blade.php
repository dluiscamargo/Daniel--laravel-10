<h1>Detalhes do renave {{ $renave->id }}</h1>

<ul>
    <li>Nome: {{ $renave->name }}</li>
    <li>Razão Social: {{ $renave->razao_social }}</li>
    <li>CNPJ: {{ $renave->cnpj }}</li>
    <li>EMAIL: {{ $renave->email }}</li>
    <li>TELEFONE: {{ $renave->telefone }}</li>
    <li>ENDEREÇO: {{ $renave->endereco }}</li>
    <li>NUMERO: {{ $renave->numero }}</li>
    <li>COMPLEMENTO: {{ $renave->complemento }}</li>
    <li>CIDADE: {{ $renave->cidade }}</li>
    <li>UF: {{ $renave->uf }}</li>
</ul>

<form action="{{ route('renaves.destroy', $renave->id) }}" method="post">
    @csrf()
    @method('DELETE')
    <button type="submit">deletar</button>
</form>
