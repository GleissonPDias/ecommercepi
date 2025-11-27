// Espera o documento carregar antes de executar o script
document.addEventListener("DOMContentLoaded", () => {
    
    // ============================================================
    // 1. LÓGICA DE NAVEGAÇÃO POR ABAS (Seu código original)
    // ============================================================
    
    // Encontrar todos os botões da barra de navegação
    const navButtons = document.querySelectorAll(".profile-nav .nav-button");

    // Encontrar todos os painéis de conteúdo
    const contentPanels = document.querySelectorAll(".account-content-wrapper .account-panel");

    // Adicionar um "ouvinte" de clique em cada botão
    navButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            // Previne a ação padrão do link
            event.preventDefault();

            // Pega o ID do alvo do atributo 'data-target'
            const targetId = button.getAttribute("data-target");

            // --- Lógica para os Botões ---
            // Remove a classe 'active' de TODOS os botões
            navButtons.forEach((btn) => {
                btn.classList.remove("active");
            });

            // Adiciona a classe 'active' APENAS ao botão clicado
            button.classList.add("active");

            // --- Lógica para os Painéis de Conteúdo ---
            // Esconde TODOS os painéis
            contentPanels.forEach((panel) => {
                panel.classList.remove("active");
            });

            // Mostra APENAS o painel correspondente
            const targetPanel = document.getElementById(targetId);
            if (targetPanel) {
                targetPanel.classList.add("active");
            }
        });
    });

    // ============================================================
    // 2. LÓGICA DA MÁSCARA DE CPF (Novo)
    // ============================================================
    
    const cpfInput = document.getElementById('cpf');

    // Verifica se o campo CPF existe na página antes de tentar adicionar o evento
    if (cpfInput) {
        cpfInput.addEventListener('input', function() {
            let v = this.value;

            // Remove tudo o que não é dígito
            v = v.replace(/\D/g, "");

            // Limita a 11 dígitos numéricos (para evitar bugs de formatação)
            if (v.length > 11) v = v.slice(0, 11);

            // Aplica a formatação ponto e traço
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

            this.value = v;
        });
    }
});