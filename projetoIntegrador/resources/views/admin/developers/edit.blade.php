<form action="{{ route('admin.developers.update', $developer )}}" method="POST">
    @method('PUT')
    @csrf
    <h1>Editar Desenvolvedora: {{ $developer->name }} </h1>
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$developer->name}}" />
    <div>
        <button type="submit">Atualizar</button>
</form> 