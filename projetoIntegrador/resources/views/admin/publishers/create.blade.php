<form action="{{ route('admin.publishers.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" id="name" required />

        <button type="submit">Criar</button> 
        
    </div>