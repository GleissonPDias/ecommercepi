<form action="{{ route('admin.categories.update', $category )}}" method="POST">
    @method('PUT')
    @csrf
    <h1>Editar Categoria: {{ $category->name }} </h1>
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{category->name}}" />
    <div>
        <button type="submit">Atualizar</button>
</form> 