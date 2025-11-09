

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Document</title>
  </head>
  <body>
    <div class="container">
      <div class="content">
        <img
          class="logo logo-criacao"
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
            type="text"
            id="last_name"
            name="last_name"
            placeholder="Sobrenome"
            title="Sobrenome"
          />
          
          <input
            class="input"
            type="number"
            id="cpf"
            name="cpf"
            placeholder="CPF"
            title="CPF"
          />
          <input
            class="input"
            type="number"
            id="phone_number"
            name="phone_number"
            placeholder="Telefone"
            title="Telefone"
          />
          <input
            class="input"
            type="date"
            id="birth_date"
            name="birth_date"
            title="Data de Nascimento"
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
          <label for="profile_photo" class="file-label">Foto de Perfil (Opcional)</label>
          <input 
            class="input-file" {{-- (Use sua própria classe CSS) --}}
            type="file" 
            id="profile_photo" 
            name="profile_photo" 
          />
          @error('profile_photo')
          <p class="error-message">{{ $message }}</p>
          @enderror

          <div class="container-checkbox">
            <div class="checkbox-option">
              <input id="spam" name="spam" type="checkbox" value="0" />
              <label for="spam">Quero receber promoções por e-mail</label>
            </div>

            <div class="checkbox-option">
              <input id="termos" name="termos" type="checkbox" value="0" />
              <label for="termos">Aceito os termos de uso</label>
            </div>
          </div>

          <button class="button button-criacao" type="submit">Criar</button>
          <p>Já possui uma conta? <a href="{{route('login')}}">Login</a></p>
          <p class="criar-termos">
            Ao se cadastrar, você aceita os Termos de Uso e a Política de
            Privacidade
          </p>
        </form>
      </div>
    </div>
  </body>
</html>



