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


        <form class="form" method="POST" action="{{ route('password.store') }}">

          @csrf
        <input type="hidden" name="token" value="{{$request->token}}"/>          {{-- Campo de Email --}}
          <input
            class="input"
            type="email"
            id="email"
            name="email" 
            placeholder="email"
            value="{{ old('email', $request->email) }}" 
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
            autocomplete="new-password"
          />
        <input
            class="input"
            type="password"
            id="password_confirmation"
            name="password_confirmation" 
            placeholder="*******"
            required
            autocomplete="new-password"
          />
          {{-- Bloco para exibir erro de validação da senha --}}
          @error('password')
            <p class="error-message">{{ $message }}</p>
          @enderror
          


          <button class="button" type="submit">Entrar</button>

        </form>
      </div>
    </div>
  </body>
</html>



