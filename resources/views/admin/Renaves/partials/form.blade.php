@csrf()
    <input type="text" placeholder="Nome" name="name" value="{{ $renave->name ?? old('name') }}"><br>
    <input type="text" placeholder="Razão Social" name="razao_social" value="{{ $renave->razao_social ?? old('razao_social') }}"><br>
    <input type="text" placeholder="CNPJ" name="cnpj" value="{{ $renave->cnpj ?? old('cnpj') }}"><br>
    <input type="text" placeholder="Email" name="email" value="{{ $renave->email ?? old('email') }}"><br>
    <input type="text" placeholder="Telefone" name="telefone" value="{{ $renave->telefone ?? old('telefone') }}"><br>
    <input type="text" placeholder="Endereço" name="endereco" value="{{ $renave->endereco ?? old('endereco') }}"><br>
    <input type="text" placeholder="Numero" name="numero" value="{{ $renave->numero ?? old('numero') }}"><br>
    <input type="text" placeholder="Complemento" name="complemento" value="{{ $renave->complemento ?? old('complemento') }}"><br>
    <input type="text" placeholder="Cidadde" name="cidade" value="{{ $renave->cidade ?? old('cidade') }}"><br>
    <input type="text" placeholder="Estado/UF" name="uf" value="{{ $renave->uf ?? old('uf') }}">

    <button type="submit">Enviar</button>
