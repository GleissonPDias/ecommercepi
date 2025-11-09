<h1>Desenvolvedoras</h1>
<div style="display:flex; justify-content:end; align-items:center">
    <a href="{{route('admin.developers.create')}}">Adicionar nova Plataforma</a>
</div>

<table border="1px">
    <tr>
        <th>Id</th>
        <th>Nome</th>
        <th>Qtd de jogos</th>
        <th>Ações</th>
</tr>
<tbody>
    @foreach ($developers as $developer)
    <tr>
        <td>{{$developer->id}}</td>
        <td>{{$developer->name}}</td>
        <td>{{$developer->games->count()}}</td>
        <td><a href="{{route('admin.developers.edit', $developer)}}">Editar</a></td>
        <td>
            <form action="{{route('admin.developers.destroy', $developer)}}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit">Deletar</button>
            </form>
        </td>

</tr>
@endforeach
</tbody>
</table> 