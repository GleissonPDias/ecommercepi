<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifique seu E-mail</title>

    {{-- O Vite pode ser mantido se você tiver um CSS/JS global, ou removido se este for um arquivo independente --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Estilos Gerais */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            background-color: #f3f4f6;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #374151;
        }

        /* Card Principal */
        .card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-width: 500px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        .logo {
            max-width: 150px;
            margin: 0 auto 24px;
        }
        
        /* Textos de Informação */
        .info-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.5;
            margin-bottom: 16px;
        }
        
        /* Mensagem de Sucesso (quando o link é reenviado) */
        .success-message {
            font-size: 14px;
            font-weight: 500;
            color: #166534; /* Verde escuro */
            background-color: #dcfce7; /* Fundo verde claro */
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        /* Container para os botões */
        .actions-container {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Botão Primário (Reenviar E-mail) */
        .button-primary {
            background-color: #1f2937;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }
        .button-primary:hover {
            background-color: #374151;
        }
        
        /* Botão de Logout (estilizado como link) */
        .link-button {
            background: none;
            border: none;
            padding: 0;
            font-size: 14px;
            color: #4b5563;
            text-decoration: underline;
            cursor: pointer;
        }
        .link-button:hover {
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="card">
        <div>
            <img class="logo" src="{{ asset('images/gettstore.png') }}" alt="GettStore Logo" />
        </div>

        <p class="info-text">
            Obrigado por se inscrever! Antes de começar, você poderia verificar seu endereço de e-mail clicando no link que acabamos de enviar para você? Se você não recebeu o e-mail, teremos o prazer de lhe enviar outro.
        </p>

        {{-- Este bloco só aparece se o controller enviar um status de "link enviado" --}}
        @if (session('status') == 'verification-link-sent')
            <div class="success-message">
                Um novo link de verificação foi enviado para o endereço de e-mail que você forneceu durante o registro.
            </div>
        @endif

        <div class="actions-container">
            {{-- Formulário para reenviar o e-mail de verificação --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf {{-- Token de segurança do Laravel, essencial para formulários POST --}}
                <button type="submit" class="button-primary">
                    Reenviar E-mail de Verificação
                </button>
            </form>

            {{-- Formulário para fazer logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf {{-- Token de segurança do Laravel --}}
                <button type="submit" class="link-button">
                    Sair (Log Out)
                </button>
            </form>
        </div>
    </div>
</body>
</html>