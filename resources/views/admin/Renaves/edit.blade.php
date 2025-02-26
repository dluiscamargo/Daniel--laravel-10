<h1>Editar dados do renave {{ $renave->id }}</h1>

<x-alert/>

<form action="{{ route('renaves.update', $renave->id) }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    {{-- <input type="text" value="PUT" name="_method"> --}}
    @method('PUT')
    @include('admin.renaves.partials.form', [
        'renave' => $renave
    ])
</form>

