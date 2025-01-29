<h1>dúvida para o suporte {{ $support->id }}</h1>

@if ($errors->any())
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach

@endif

<form action="{{ route('supports.update', $support->id) }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    {{-- <input type="text" value="PUT" name="_method"> --}}
    @method('put')
    <input type="text" placeholder="Assunto" name="subject" value="{{ $support->subject }}"><br>
    <textarea name="body" cols="30" rows="5" placeholder="Descrição">{{ $support->body }}</textarea><br>
    <button type="submit">Enviar</button>
</form>

