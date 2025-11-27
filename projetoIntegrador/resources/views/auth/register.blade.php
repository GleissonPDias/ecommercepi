

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Gettstore</title>
  </head>
  <body>
    <div class="container">
      <div class="content">
        <img
          class="logo-criar"
          src="{{ asset('images/gettstore.png')}}"
          alt="gettstore"
        />
        <p class="criar-p">Criar minha conta</p>
        <form class="form" method="POST" enctype="multipart/form-data" action="{{ route('register') }}">
        @csrf
          <input
            class="input"
            type="text"
            id="name"
            name="name"
            placeholder="Nome"
            title="Nome"
          />

          

          <input
            class="input"
            type="email"
            id="email"
            name="email"
            placeholder="Email"
            title="email"
          />
          
        @error('email')
            <p class="error-message">{{ $message }}</p>
          @enderror
          <input
            class="input"
            type="password"
            id="password"
            name="password"
            title="password"
            placeholder="Senha"
          />
          <input
            class="input"
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            title="confirm-password"
            placeholder="Confirmar Senha"
          />
          <div class="container-checkbox">
            <div class="checkbox-option criar">
              <input id="spam" name="spam" type="checkbox" value="0" />
              <label for="spam">Quero receber promoções por e-mail</label>
            </div>

            <div class="checkbox-option criar">
              <input id="termos" name="termos" type="checkbox" value="0" />
              <label for="termos">Aceito os termos de uso</label>
            </div>
          </div>

          <p class="execute">Já possui uma conta? <a class="forgot-password" href="{{route('login')}}">Login</a></p>
          <button class="button button-criacao" type="submit">Criar</button>
          <p class="criar-termos">
            Ao se cadastrar, você aceita os Termos de Uso e a Política de
            Privacidade
          </p>
        </form>
      </div>
    </div>
  </body>
</html>



