<h1>Nova dúvida do suporte</h1>

<form action="{{ route('supports.store') }}" method="POST">
    {{-- <input type="hidden" value="{{ csrf_token() }}" name="_token"> --}}
    @csrf()
    <input type="text" placeholder="Assunto" name="subject"><br>
    <textarea name="body" cols="30" rows="5" placeholder="Descrição"></textarea><br>
    <button type="submit">Enviar</button>
</form>

