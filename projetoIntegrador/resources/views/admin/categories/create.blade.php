<form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Nome</label>
        <input type="text" name="name" required />

        <button type="submit">Criar</button> 
        
    </div>