<form action="{{ route('admin.publishers.update', $publisher )}}" method="POST">
    @method('PUT')
    @csrf
    <h1>Editar Publisher: {{ $publisher->name }} </h1>
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$publisher->name}}" />
    <div>
        <button type="submit">Atualizar</button>
</form> 