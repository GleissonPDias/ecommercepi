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
                <a href="../carrinho/carrinho.html"><i class="fas fa-shopping-cart"></i></a>
                <span class="cart-count">0</span>
            </div>
            <a href="conta.html"><i class="fas fa-user"></i></a>
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
                        <label for="photo">Foto de Perfil (Opcional)</label>
                        <input type="file" id="photo" name="photo" class="form-control-file">
                    @error('photo') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="username">Nome de usuário:</label>
                        <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username)}}">
                        @error('username') <span class="error-message" style="color: red;">{{ $message }}</span> @enderror
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
                <div class="content-placeholder">
                    <i class="fas fa-heart"></i>
                    <p>Sua lista de desejos está vazia.</p>
                </div>
            </div>

            <div id="meus-games" class="account-panel">
                <h2>Meus Games</h2>
                <p class="subtitle">Sua biblioteca de jogos.</p>
                 <div class="content-placeholder">
                    <i class="fas fa-gamepad"></i>
                    <p>Você ainda não possui jogos.</p>
                </div>
            </div>
            
            <div id="meus-cartoes" class="account-panel">
                <h2>Meus Cartões</h2>
                <p class="subtitle">Adicione e gerencie seus cartões.</p>
                
                <div class="card-list-placeholder">
                    <i class="far fa-credit-card"></i>
                    <p>Nenhum cartão cadastrado.</p>
                    <button type="button" class="save-button" style="margin-top: 20px;">Adicionar Novo Cartão</button>
                </div>
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