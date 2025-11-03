<h1>Lista de Categorias</h1>
<div style="display:flex; justify-content:end; align-items:center">
    <a href="{{route('admin.categories.create')}}">Adicionar nova categoria</a>
</div>

<table border="1px">
    <tr>
        <th>Id</th>
        <th>Nome</th>
        <th>Slug</th>
        <th>Qtd de jogos</th>
        <th>Ações</th>
</tr>
<tbody>
    @foreach ($categories as $category)
    <tr>
        <td>{{$category->id}}</td>
        <td>{{$category->name}}</td>
        <td>{{$category->slug}}</td>
        <td>{{$category->games->count()}}</td>
        <td><a href="{{route('admin.categories.edit', $category)}}">Editar</a></td>
        <td>
            <form action="{{route('admin.categories.destroy', $category)}}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit">Deletar</button>
            </form>
        </td>

</tr>
@endforeach
</tbody>
</table> 