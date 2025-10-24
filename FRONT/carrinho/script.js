// Espera o documento carregar completamente
document.addEventListener('DOMContentLoaded', () => {

    // Seleciona os elementos do DOM
    const openMenuButton = document.querySelector('.btn-menu');
    const closeMenuButton = document.querySelector('.btn-close');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.overlay');

    // Função para abrir o menu
    const openMenu = () => {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-open');
    };

    // Função para fechar o menu
    const closeMenu = () => {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-open');
    };

    // Adiciona os "escutadores" de eventos
    if (openMenuButton && sidebar && overlay) {
        openMenuButton.addEventListener('click', openMenu);
    }
    
    if (closeMenuButton && sidebar && overlay) {
        closeMenuButton.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu); // Fecha ao clicar no overlay
    }
    
    // Opcional: Fecha o menu ao pressionar a tecla "Escape"
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && sidebar.classList.contains('is-open')) {
            closeMenu();
        }
    });
});
