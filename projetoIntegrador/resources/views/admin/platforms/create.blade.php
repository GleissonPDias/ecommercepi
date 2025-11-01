<form action="{{ route('admin.platforms.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" required />

        <button type="submit">Criar</button> 
        
    </div>