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
            <a href="/tela-inicial/home.html"><img src="/images/GettStore(1).png" alt="logo" class="logo"></a>
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
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <span class="info-pill">
                    <i class="fas fa-user"></i>
                    sapeca123
                </span>
                <span class="info-pill">
                    <i class="fas fa-envelope"></i>
                    sapequinha@gmail.com
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

                <form class="info-form">
                    <div class="form-group">
                        <label for="login">Nome de usuário:</label>
                        <input type="text" id="usuário" name="usuário">
                    </div>
                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome">
                        </div>
                        <div class="form-group half-width">
                            <label for="sobrenome">Sobrenome</label>
                            <input type="text" id="sobrenome" name="sobrenome">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group half-width">
                            <label for="cpf">CPF</label>
                            <input type="text" id="cpf" name="cpf">
                        </div>
                        <div class="form-group half-width">
                            <label for="nascimento">Data de Nascimento</label>
                            <input type="text" id="nascimento" name="nascimento">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="save-button">Salvar Alterações</button>
                    </div>
                </form>
            </div>

            <div id="alterar-login" class="account-panel">
                <h2>Alterar Senha</h2>
                <p class="subtitle">Defina uma nova senha para sua conta.</p>
                
                <form class="info-form">
                     <div class="form-group">
                        <label for="new_password">Nova Senha</label>
                        <input type="password" id="new_password" name="new_password" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Nova Senha</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="save-button">Atualizar Senha</button>
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