<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Document</title>
  </head>
  <body>
    <div class="container">
      <div class="content content-redefinir">
        <img class="logo-login" src="./images/GettStore Branco s fundo.png" alt="gettstore" />
        <form class="form form-redefinir" method="POST" action="{{ route('password.email') }}">
        @csrf
          <h2 class="redefinir">Redefinir senha</h2>

        @if (session('status'))
            <div class="alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                        {{ session('status') }}
            </div>
        @endif

            <div class="form-group">
                    <label for="email" style="color: white; margin-bottom: 5px; display: block;">Email Cadastrado:</label>
                    
                    {{-- 
                      3. O 'name="email"' foi adicionado
                    --}}
                    <input class="input" 
                           type="email" 
                           id="email" 
                           name="email" {{-- 👈 OBRIGATÓRIO --}}
                           placeholder="Email cadastrado" 
                           title="email" 
                           value="{{ old('email') }}" {{-- (Boa prática) --}}
                           required>

                    {{-- 
                      4. MOSTRAR MENSAGEM DE ERRO
                         (Ex: "Não encontrámos um utilizador com esse e-mail.")
                    --}}
                    @error('email')
                        <span class="error-message" style="color: #f8d7da; margin-top: 5px; display: block;">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            <button class="button btn-entrar" type="submit">Enviar código</button>
        </form>
      </div>
    </div>
  </body>
</html>






