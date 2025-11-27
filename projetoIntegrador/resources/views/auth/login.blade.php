<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    {{-- O Vite vai injetar os estilos do seu resources/css/app.css aqui --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  </head>
  <body>
    <div class="container">
      <div class="content">
        <img class="logo-login" src="{{ asset('images/gettstore.png') }}" alt="gettstore" />


        <form class="form" method="POST" action="{{ route('login') }}">

          @csrf

          {{-- Campo de Email --}}
          <input
            class="input"
            type="email"
            id="email"
            name="email" 
            placeholder="Email"
            value="{{ old('email') }}" 
            required
            autofocus
          />
          {{-- Bloco para exibir erro de validação do email --}}
          {{-- Crie uma classe .error-message no seu CSS para estilizar (ex: color: red;) --}}
          @error('email')
            <p class="error-message">{{ $message }}</p>
          @enderror

          {{-- Campo de Senha --}}
          <input
            class="input"
            type="password"
            id="password"
            name="password" 
            placeholder="Senha"
            required
            autocomplete="current-password"
          />
          {{-- Bloco para exibir erro de validação da senha --}}
          @error('password')
            <p class="error-message">{{ $message }}</p>
          @enderror
          
          {{-- Funcionalidade "Lembrar de mim" --}}
          {{-- Você pode criar uma classe .remember-me para estilizar o container --}}
          <div class="checkbox">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Lembrar de mim</label>
          </div>

          {{-- Link "Esqueci minha senha" agora aponta para a rota correta --}}
          @if (Route::has('password.request'))
            <a class="forgot-password" href="{{ route('password.request') }}">
                Esqueci minha senha
            </a>
          @endif

          <button class="btn-entrar" type="submit">Entrar</button>


          <p class="criar">Não tem conta? <a class="criar-conta" href="{{ route('register') }}">Criar conta</a></p>

          {{-- A funcionalidade de login social precisa ser implementada à parte (ex: com Laravel Socialite) --}}
          <button type="button" class="login-options">
            <img src="{{ asset('images/iconGoogle.svg') }}" /><span>Entrar com Google</span>
          </button>
          <button type="button" class="login-options">
            <img src="{{ asset('images/iconFacebook.svg') }}" /><span>Entrar com Facebook</span>
          </button>
        </form>
      </div>
    </div>
  </body>
</html>