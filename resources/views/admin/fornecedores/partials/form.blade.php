@csrf()
    <input type="text" placeholder="Nome" name="name" value="{{ $fornecedor->name ?? old('name') }}"><br>
    <input type="text" placeholder="Razão Social" name="razao_social" value="{{ $fornecedor->razao_social ?? old('razao_social') }}"><br>
    <input type="text" placeholder="CNPJ" name="cnpj" value="{{ $fornecedor->cnpj ?? old('cnpj') }}"><br>
    <input type="text" placeholder="Email" name="email" value="{{ $fornecedor->email ?? old('email') }}"><br>
    <input type="text" placeholder="Telefone" name="telefone" value="{{ $fornecedor->telefone ?? old('telefone') }}"><br>
    <input type="text" placeholder="Endereço" name="endereco" value="{{ $fornecedor->endereco ?? old('endereco') }}"><br>
    <input type="text" placeholder="Numero" name="numero" value="{{ $fornecedor->numero ?? old('numero') }}"><br>
    <input type="text" placeholder="Complemento" name="complemento" value="{{ $fornecedor->complemento ?? old('complemento') }}"><br>
    <input type="text" placeholder="Cidadde" name="cidade" value="{{ $fornecedor->cidade ?? old('cidade') }}"><br>
    <input type="text" placeholder="Estado/UF" name="uf" value="{{ $fornecedor->uf ?? old('uf') }}">

    <button type="submit">Enviar</button>
