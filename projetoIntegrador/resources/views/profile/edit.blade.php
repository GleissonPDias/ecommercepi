<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GettStore - Minha Conta</title> 
    
    @vite(['resources/css/user.css', 'resources/js/user.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="header-left">
            <a href="{{ route('home') }}"><img src="{{ asset('images/GettStore.png') }}" alt="logo" class="logo"></a>
        </div>
        <div class="header-right">
            <div class="cart-icon">
                <a href="{{route('cart.index')}}"><i class="fas fa-shopping-cart"></i></a>
                <span class="cart-count">{{$cartItems->count()}}</span> 
            </div>
            <a href="{{route('profile.edit')}}"><i class="fas fa-user"></i></a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center; margin-left: 10px;">
            @csrf
            
            {{-- 
                Este é um link <a> que, ao ser clicado,
                envia o formulário "pai" mais próximo.
            --}}
            <a href="{{ route('logout') }}" 
               title="Sair"
               onclick="event.preventDefault(); this.closest('form').submit();">
                
                {{-- Use um ícone para "Sair" --}}
                <i class="fas fa-sign-out-alt" style="color: white; font-size: 1.2rem;"></i>
            </a>
        </form>
        </div>
    </header>

    <main class="account-page">
        
        <section class="profile-header">
            <div class="profile-avatar">
                @if (auth()->user()->profile_photo_path)
                <img src="{{Storage::url(auth()->user()->profile_photo_path) }}"
                alt="Foto de perfil de {{auth()->user()->name}}"
                class="avatar-image">
                @else
                <i class="fas fa-user"></i>
                @endif

            </div>
            <div class="profile-info">
                <span class="info-pill">
                    <i class="fas fa-user"></i>
                    {{ auth()->user()->name }}
                </span>
                <span class="info-pill">
                    <i class="fas fa-envelope"></i>
                    {{ auth()->user()->email }}
                </span>
            </div>
        </section>

        <nav class="profile-nav">
            <a href="#" class="nav-button active" data-target="minha-conta">Minha Conta</a>
            <a href="#" class="nav-button" data-target="alterar-login">Alterar Login</a>
            <a href="#" class="nav-button" data-target="wishlist">Wishlist</a>
            <a href="#" class="nav-button" data-target="meus-games">Meus Games</a>
            <a href="#" class="nav-button" data-target="meus-cartoes">Meus Cartões</a>
        </nav>

        <div class="account-content-wrapper">

            <div id="minha-conta" class="account-panel active">
                <h2>Informações da conta</h2>
                <p class="subtitle">Altere seus dados pessoais.</p>

                <form method="post" action="{{route('profile.update')}}" enctype="multipart/form-data" class="info-form">
                    @csrf
                    @method('patch')


                    <div class="form-group">
                        <label for="username">Nome de usuário:</label>
                        <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username)}}">
                        @error('username') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email)}}">
                        @error('email') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="name">Nome</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name)}}">
                            @error('name') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group half-width">
                            <label for="last_name">Sobrenome</label>
                            <input type="text" id="last_name" name="last_name" value="{{old ('last_name', auth()->user()->last_name)}}">
                            @error('last_name') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="cpf">CPF</label>
                            <input type="text" id="cpf" name="cpf" value="{{old('cpf', auth()->user()->cpf)}}">
                            @error('cpf') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group half-width">
                            <label for="birth_date">Data de Nascimento</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{old('birth_date', auth()->user()->birth_date) }}">
                            @error('birth_date') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Telefone</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{old('phone_number', auth()->user()->phone_number)}}">
                        @error('phone_number') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="photo">Foto de Perfil (Opcional)</label>
                        <input type="file" id="photo" name="photo" class="form-control-file">
                    @error('photo') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="save-button">Salvar Alterações</button>
                        @if (session('status')==='profile_updated')
                            <p style="color:green; margin-top: 10px;"> Salvo com sucesso!</p>
                        @endif 
                    </div>
                </form>
            </div>

            <div id="alterar-login" class="account-panel">
                <h2>Alterar Senha</h2>
                <p class="subtitle">Defina uma nova senha para sua conta.</p>
                
                <form method="post" action="{{route('password.update')}}" class="info-form">
                    @csrf
                    @method('put') 
<div class="form-group">
            <label for="current_password">Senha Atual</label>
            <input type="password" id="current_password" name="current_password" placeholder="••••••••" required>
            {{-- Mostra o erro específico para este campo --}}
            @error('current_password', 'updatePassword') 
                <span class="error-message">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Nova Senha</label>
            {{-- O nome do campo deve ser 'password' --}}
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            @error('password', 'updatePassword') 
                <span class="error-message">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Nova Senha</label>
            {{-- O nome do campo deve ser 'password_confirmation' --}}
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
            @error('password_confirmation', 'updatePassword') 
                <span class="error-message">{{ $message }}</span> 
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="save-button">Atualizar Senha</button>
            
            @if (session('status') === 'password-updated')
                <p style="color: green; margin-top: 10px;">Senha atualizada!</p>
            @endif
        </div>
    </form>
            </div>

            <div id="wishlist" class="account-panel">
    <h2>Wishlist</h2>
    <p class="subtitle">Sua lista de desejos.</p>
    
    {{-- 
      Verifica se a coleção 'favorites' (que carregamos no controller) 
      não está vazia. 
    --}}
    @if (auth()->user()->favorites->isNotEmpty())

        {{-- Você pode usar uma classe de grid da sua home page aqui --}}
        <div class="wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px;">
            
            {{-- Faz um loop em cada produto favorito --}}
            @foreach (auth()->user()->favorites as $product)
                
                <div class="wishlist-card" style="border: 1px solid #444; border-radius: 8px; overflow: hidden;">
                    <a href="{{ route('products.show', $product) }}">
                        {{-- 
                          Mostra a imagem de capa.
                          Isso só funciona rápido porque fizemos o $user->load('favorites.game') no controller.
                        --}}
                        @if($product->game && $product->game->cover_url)
                            <img src="{{ Storage::url($product->game->cover_url) }}" 
                                 alt="{{ $product->name }}" 
                                 style="width: 100%; height: 240px; object-fit: cover;">
                        @endif
                        
                        <h4 style="padding: 10px; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $product->name }}
                        </h4>
                        <p style="padding: 0 10px 10px; margin: 0; color: #00ffc3; font-weight: bold;">
                            R$ {{ number_format($product->current_price, 2, ',', '.') }}
                        </p>
                    </a>
                    
                    {{-- Botão para REMOVER (usa a mesma rota 'toggle') --}}
                    <form method="POST" action="{{ route('favorites.toggle', $product) }}">
                        @csrf
                        <button type"submit" class="save-button" style="width: 100%; background-color: #ff4d4d;">
                            Remover
                        </button>
                    </form>
                </div>

            @endforeach
        </div>

    @else
        {{-- Se a lista estiver vazia, mostramos o seu placeholder original --}}
        <div class="content-placeholder">
            <i class="fas fa-heart"></i>
            <p>Sua lista de desejos está vazia.</p>
        </div>
    @endif
</div>

            <div id="meus-games" class="account-panel">
    <h2>Meus Games</h2>
    <p class="subtitle">Sua biblioteca de jogos e chaves de ativação.</p>
    
    {{-- 
      Verifica se a coleção 'library' (que carregamos no controller) 
      não está vazia. 
    --}}
    @if (auth()->user()->library->isNotEmpty())

        {{-- (Vou usar um estilo de grid parecido com o da Wishlist) --}}
        <div class="wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            
            {{-- Faz um loop em cada CHAVE (GameKey) na biblioteca --}}
            @foreach (auth()->user()->library as $key)
                
                {{-- Verificação de segurança para garantir que os dados existem --}}
                @if($key->product && $key->product->game)
                    <div class="game-key-card" style="border: 1px solid #444; border-radius: 8px; overflow: hidden; background: #2a2a3a;">
                        
                        {{-- A Chave ($key) tem um Produto ($key->product) que tem um Jogo ($key->product->game) --}}
                        <img src="{{ Storage::url($key->product->game->cover_url) }}" 
                             alt="{{ $key->product->name }}" 
                             style="width: 100%; height: 260px; object-fit: cover;">
                        
                        <div style="padding: 15px;">
                            <h4 style="margin: 0 0 5px 0;">{{ $key->product->name }}</h4>
                            <p style="font-size: 0.9em; color: #ccc; margin: 0 0 10px 0;">
                                {{ $key->product->platform->name }}
                            </p>
                            
                            <label style="font-size: 0.8em; color: #aaa;">Sua Chave:</label>
                            
                            {{-- 
                              A 'key_value' vem do seu Model GameKey. 
                              Verifiquei o seu Model anterior e o nome da coluna é 'key_value'.
                            --}}
                            <input type="text" 
                                   value="{{ $key->key_value }}" 
                                   readonly 
                                   style="width: 100%; background: #1a1a2e; border: 1px solid #555; color: white; padding: 8px; border-radius: 4px;">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    @else
        {{-- Se a biblioteca estiver vazia, mostramos o seu placeholder original --}}
        <div class="content-placeholder">
            <i class="fas fa-gamepad"></i>
            <p>Você ainda não possui jogos.</p>
        </div>
    @endif
</div>
            
{{-- 👇 SUBSTITUA A SUA ABA "MEUS-CARTOES" POR ISTO 👇 --}}

<div id="meus-cartoes" class="account-panel">
    <h2>Meus Cartões</h2>
    <p class="subtitle">Adicione e gerencie seus cartões.</p>

    {{-- 
      Verifica se a coleção 'paymentMethods' (que carregámos no controller) 
      não está vazia.
    --}}
    {{-- (Dentro de <div id="meus-cartoes" ...>) --}}

@if (auth()->user()->paymentMethods->isNotEmpty())

    <div class="card-list">
        
        {{-- Faz um loop em cada método de pagamento salvo --}}
        @foreach (auth()->user()->paymentMethods as $method)
            
            <div class="card-item" style="display: flex; justify-content: space-between; align-items: center; background: #f0f2f5; padding: 15px; border-radius: 8px; margin-bottom: 10px; color: #333;">
                
                <div class="card-details" style="display: flex; align-items: center; gap: 15px;">
                    
                    {{-- 👇 CORREÇÃO 1: 'brand' -> 'card_brand' 👇 --}}
                    <i class="fab fa-cc-{{ $method->card_brand }}" style="font-size: 2.5rem; color: #0d47a1;"></i>
                    
                    <div>
                        <strong style="font-size: 1.1rem; letter-spacing: 1px;">
                            {{-- 👇 CORREÇÃO 2: 'last_four' -> 'last_four_digits' 👇 --}}
                            **** **** **** {{ $method->last_four_digits }}
                        </strong>
                        
                        {{-- 👇 CORREÇÃO 3: Removida a data de expiração (não existe na sua migração) 👇 --}}
                        
                    </div>
                </div>
                
                {{-- O seu formulário de 'destroy' (remover) --}}
                <form method="POST" action="{{ route('payment.destroy', $method) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="item-remove" aria-label="Remover cartão" 
                            style="background: none; border: none; color: #ff4d4d; font-size: 1.2rem; cursor: pointer;"
                            onclick="return confirm('Tem certeza que quer remover este cartão?')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        @endforeach
    </div>

@else
    {{-- Se a lista estiver vazia, mostramos o placeholder original --}}
    <div class="card-list-placeholder">
        <i class="far fa-credit-card"></i>
        <p>Nenhum cartão cadastrado.</p>
        <p style="font-size: 0.9em; color: #888;">O seu cartão será salvo automaticamente quando fizer a sua próxima compra.</p>
    </div>
@endif
</div>

        </div> </main>
    
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>Seguir GettStore</h3>
                    <div class="social-icons">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="#">Sobre</a></li>
                        <li><a href="#">Carreiras</a></li>
                        <li><a href="#">Seu jogo na Nuuvem</a></li>
                        <li><a href="#">Segurança</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Ajuda</h3>
                    <ul>
                        <li><a href="#">Suporte</a></li>
                        <li><a href="#">Termos de Uso</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                <a href="index.html"><img src="/images/GettStore Branco s fundo.png" alt="GettStore Avatar Logo" class="footer-logo"></a>
                <p class="footer-legal">
                    GettStore Ltda. – CNPJ 00.000.000/0000-00<br>
                    Rua Lauro Müller, nº 116, sala 503 - Torre do Rio Sul - Botafogo - Rio de Janeiro, RJ – 22290-160
                </p>
            </div>
        </div>
    </footer>


</body>
</html>