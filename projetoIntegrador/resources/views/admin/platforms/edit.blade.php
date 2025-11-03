<form action="{{ route('admin.platforms.update', $platform )}}" method="POST">
    @method('PUT')
    @csrf
    <h1>Editar Plataforma: {{ $platform->name }} </h1>
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$platform->name}}" />
    <div>
        <button type="submit">Atualizar</button>
</form> 