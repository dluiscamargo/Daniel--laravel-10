<h1>Novo renave</h1>

<x-alert/>

<form action="{{ route('renaves.store') }}" method="POST">
    @include('admin.renaves.partials.form')
</form>

