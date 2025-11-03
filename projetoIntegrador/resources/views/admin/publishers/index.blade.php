<h1>Lista de Publishers</h1>
<div style="display:flex; justify-content:end; align-items:center">
    <a href="{{route('admin.publishers.create')}}">Adicionar nova Publisher</a>
</div>

<table border="1px">
    <tr>
        <th>Id</th>
        <th>Nome</th>
        <th>Qtd de jogos<th>
        <th>Ações</th>
</tr>
<tbody>
    @foreach ($publishers as $publisher)
    <tr>
        <td>{{$publisher->id}}</td>
        <td>{{$publisher->name}}</td>
        <td>{{$publisher->games->count()}}</td>
        <td><a href="{{route('admin.publishers.edit', $publisher)}}">Editar</a></td>
        <td>
            <form action="{{route('admin.publishers.destroy', $publisher)}}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit">Deletar</button>
            </form>
        </td>

</tr>
@endforeach
</tbody>
</table> 