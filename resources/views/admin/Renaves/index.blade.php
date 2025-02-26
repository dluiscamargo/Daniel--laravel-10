<h1>Listagem dos renaves</h1>
<a href="{{ route('renaves.create') }}">Criar Renave</a>

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
        @foreach ($renaves->item() as $renave)
            <tr>
                <td>{{ $renave->name }}</td>
                <td>{{ $renave->razao_social }}</td>
                <td>{{ $renave->cnpj }}</td>
                <td>{{ $renave->email }}</td>
                <td>{{ $renave->telefone }}</td>
                <td>{{ $renave->endereco }}</td>
                <td>{{ $renave->numero }}</td>
                <td>{{ $renave->complemento }}</td>
                <td>{{ $renave->cidade }}</td>
                <td>{{ $renave->uf }}</td>
                <td>
                    @csrf()
                    <a href="{{ route('renaves.show', $renave->id) }}">ir</a>
                </td>
                <td>
                    @csrf()
                    <a href="{{ route('renaves.edit', $renave->id) }}">Editar</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<x-pagination :paginator="$renaves"
              :appends="$filters" />



