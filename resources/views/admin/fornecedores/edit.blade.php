<h1>Editar dados do fornecedor {{ $fornecedor->id }}</h1>
{{-- @dd($fornecedor) --}}
@if ($errors->any())
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach

@endif

<form action="{{ route('fornecedores.update', $fornecedor->id) }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    {{-- <input type="text" value="PUT" name="_method"> --}}
    @method('put')
    <input type="text" placeholder="Nome" name="name" value="{{ $fornecedor->name }}"><br>
    <input type="text" placeholder="Razão Social" name="razao_social" value="{{ $fornecedor->razao_social }}"><br>
    <input type="text" placeholder="CNPJ" name="cnpj" value="{{ $fornecedor->cnpj }}"><br>
    <input type="text" placeholder="Email" name="email" value="{{ $fornecedor->email }}"><br>
    <input type="text" placeholder="Telefone" name="telefone" value="{{ $fornecedor->telefone }}"><br>
    <input type="text" placeholder="Endereço" name="endereco" value="{{ $fornecedor->endereco }}"><br>
    <input type="text" placeholder="Numero" name="numero" value="{{ $fornecedor->numero }}"><br>
    <input type="text" placeholder="Complemento" name="complemento" value="{{ $fornecedor->complemento }}"><br>
    <input type="text" placeholder="Cidadde" name="cidade" value="{{ $fornecedor->cidade }}"><br>
    <input type="text" placeholder="Estado/uf" name="uf" value="{{ $fornecedor->uf }}">

    {{-- <input type="text" placeholder="Razão Social" name="razao_social" value="{{ $fornecedor->razao_social }}"><br>
    <textarea name="body" cols="30" rows="5" placeholder="Descrição">{{ $fornecedor->body }}</textarea><br> --}}
    <button type="submit">Enviar</button>
</form>

