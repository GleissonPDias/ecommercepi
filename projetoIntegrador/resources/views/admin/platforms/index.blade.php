<h1>Plataformas</h1>
<div style="display:flex; justify-content:end; align-items:center">
    <a href="{{route('admin.platforms.create')}}">Adicionar nova Plataforma</a>
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
    @foreach ($platforms as $platform)
    <tr>
        <td>{{$platform->id}}</td>
        <td>{{$platform->name}}</td>
        <td>{{$platform->slug}}</td>
        <td>{{$platform->products->count()}}</td>
        <td><a href="{{route('admin.platforms.edit', $platform)}}">Editar</a></td>
        <td>
            <form action="{{route('admin.platforms.destroy', $platform)}}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit">Deletar</button>
            </form>
        </td>

</tr>
@endforeach
</tbody>
</table> 