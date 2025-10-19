<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    {{-- O Vite vai injetar os estilos do seu resources/css/app.css aqui --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="container">
      <div class="content">
        <img class="logo" src="{{ asset('images/gettstore.png') }}" alt="gettstore" />


        <form class="form" method="POST" action="{{ route('login') }}">

          @csrf

          {{-- Campo de Email --}}
          <input
            class="input"
            type="email"
            id="email"
            name="email" 
            placeholder="email"
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
            placeholder="*******"
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

          <button class="button" type="submit">Entrar</button>


          <p>Não tem conta? <a href="{{ route('register') }}">Criar conta</a></p>

          {{-- A funcionalidade de login social precisa ser implementada à parte (ex: com Laravel Socialite) --}}
          <button type="button" class="btn-Login-options">
            <img src="{{ asset('images/iconGoogle.svg') }}" /><span>Entrar com Google</span>
          </button>
          <button type="button" class="btn-Login-options">
            <img src="{{ asset('images/iconFacebook.svg') }}" /><span>Entrar com Facebook</span>
          </button>
        </form>
      </div>
    </div>
  </body>
</html>