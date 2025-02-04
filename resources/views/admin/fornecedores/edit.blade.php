<h1>Editar dados do fornecedor {{ $fornecedor->id }}</h1>

<x-alert/>

<form action="{{ route('fornecedores.update', $fornecedor->id) }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    {{-- <input type="text" value="PUT" name="_method"> --}}
    @method('PUT')
    @include('admin.fornecedores.partials.form', [
        'fornecedor' => $fornecedor
    ])
</form>

