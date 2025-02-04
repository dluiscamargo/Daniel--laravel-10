<h1>Novo fornecedor</h1>

<x-alert/>

<form action="{{ route('fornecedores.store') }}" method="POST">
    @include('admin.fornecedores.partials.form')
</form>

