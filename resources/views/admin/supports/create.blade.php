<h1>Nova dúvida do suporte</h1>

@if ($errors->any())
    @foreach($errors->all() as $error)
        {{ $error }}
    @endforeach

@endif

<form action="{{ route('supports.store') }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    <input type="text" placeholder="Assunto" name="subject" value="{{ old('subject') }}"><br>
    <textarea name="body" cols="30" rows="5" placeholder="Descrição">{{ old('body') }}</textarea><br>
    <button type="submit">Enviar</button>
</form>

