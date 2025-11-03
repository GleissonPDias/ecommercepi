// Espera o documento carregar antes de executar o script
document.addEventListener("DOMContentLoaded", () => {
    // 1. Encontrar todos os botões da barra de navegação
    // ATUALIZADO: O seletor agora é '.profile-nav .nav-button'
    const navButtons = document.querySelectorAll(".profile-nav .nav-button");

    // 2. Encontrar todos os painéis de conteúdo
    // ATUALIZADO: O seletor agora é '.account-content-wrapper .account-panel'
    const contentPanels = document.querySelectorAll(
        ".account-content-wrapper .account-panel"
    );

    // 3. Adicionar um "ouvinte" de clique em cada botão
    navButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            // Previne a ação padrão do link (de subir a página)
            event.preventDefault();

            // Pega o ID do alvo do atributo 'data-target' (ex: "minha-conta")
            const targetId = button.getAttribute("data-target");

            // --- Lógica para os Botões ---

            // Remove a classe 'active' de TODOS os botões
            navButtons.forEach((btn) => {
                btn.classList.remove("active");
            });

            // Adiciona a classe 'active' APENAS ao botão que foi clicado
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
});
