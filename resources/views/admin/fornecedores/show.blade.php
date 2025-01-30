<h1>Detalhes do fornecedor {{ $fornecedor->id }}</h1>

<ul>
    <li>Nome: {{ $fornecedor->name }}</li>
    <li>Razão Social: {{ $fornecedor->razao_social }}</li>
    <li>CNPJ: {{ $fornecedor->cnpj }}</li>
    <li>EMAIL: {{ $fornecedor->email }}</li>
    <li>TELEFONE: {{ $fornecedor->telefone }}</li>
    <li>ENDEREÇO: {{ $fornecedor->endereco }}</li>
    <li>NUMERO: {{ $fornecedor->numero }}</li>
    <li>COMPLEMENTO: {{ $fornecedor->complemento }}</li>
    <li>CIDADE: {{ $fornecedor->cidade }}</li>
    <li>UF: {{ $fornecedor->uf }}</li>
</ul>

<form action="{{ route('fornecedores.destroy', $fornecedor->id) }}" method="post">
    @csrf()
    @method('DELETE')
    <button type="submit">deletar</button>
</form>
