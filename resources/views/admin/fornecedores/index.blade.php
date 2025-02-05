<h1>Listagem dos fornecedores</h1>
<a href="{{ route('fornecedores.create') }}">Criar Fornecedor</a>

<table>
    <thead>
        <th>name</th>
        <th>razao_social</th>
        <th>cnpj</th>
        <th>email</th>
        <th>telefone</th>
        <th>endereco</th>
        <th>numero</th>
        <th>cidade</th>
        <th>uf</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($fornecedores->item() as $fornecedor)
            <tr>
                <td>{{ $fornecedor->name }}</td>
                <td>{{ $fornecedor->razao_social }}</td>
                <td>{{ $fornecedor->cnpj }}</td>
                <td>{{ $fornecedor->email }}</td>
                <td>{{ $fornecedor->telefone }}</td>
                <td>{{ $fornecedor->endereco }}</td>
                <td>{{ $fornecedor->numero }}</td>
                <td>{{ $fornecedor->complemento }}</td>
                <td>{{ $fornecedor->cidade }}</td>
                <td>{{ $fornecedor->uf }}</td>
                <td>
                    @csrf()
                    <a href="{{ route('fornecedores.show', $fornecedor->id) }}">ir</a>
                </td>
                <td>
                    @csrf()
                    <a href="{{ route('fornecedores.edit', $fornecedor->id) }}">Editar</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<x-pagination :paginator="$fornecedores"
              :appends="$filters" />



