<h1>Novo fornecedor</h1>

@if ($errors->any())
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach

@endif

<form action="{{ route('fornecedores.store') }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    <input type="text" placeholder="Nome" name="name" value="{{ old('name') }}"><br>
    <input type="text" placeholder="Razão Social" name="razao_social" value="{{ old('razao_social') }}"><br>
    <input type="text" placeholder="CNPJ" name="cnpj" value="{{ old('cnpj') }}"><br>
    <input type="text" placeholder="Email" name="email" value="{{ old('email') }}"><br>
    <input type="text" placeholder="Telefone" name="telefone" value="{{ old('telefone') }}"><br>
    <input type="text" placeholder="Endereço" name="endereco" value="{{ old('endereco') }}"><br>
    <input type="text" placeholder="Numero" name="numero" value="{{ old('numero') }}"><br>
    <input type="text" placeholder="Complemento" name="complemento" value="{{ old('complemento') }}"><br>
    <input type="text" placeholder="Cidadde" name="cidade" value="{{ old('cidade') }}"><br>
    <input type="text" placeholder="Estado/UF" name="uf" value="{{ old('uf') }}">

    <button type="submit">Enviar</button>
</form>

