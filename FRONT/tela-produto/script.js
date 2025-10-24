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

    // --- CÓDIGO DO CARROSSEL DE IMAGENS DO PRODUTO (COM SETAS) ---
    
    const mainImage = document.querySelector('.main-banner img');
    const thumbnails = document.querySelectorAll('.thumbnail-gallery img');
    const prevButton = document.querySelector('.carousel-arrow.prev');
    const nextButton = document.querySelector('.carousel-arrow.next');

    if (mainImage && thumbnails.length > 0) {
        let currentImageIndex = 0; // Começa na primeira imagem (índice 0)

        // Função para atualizar a imagem principal e o estado das miniaturas
        const updateCarousel = (index) => {
            if (index < 0) { // Se for menor que 0, vai para a última imagem
                index = thumbnails.length - 1;
            } else if (index >= thumbnails.length) { // Se for maior que a última, vai para a primeira
                index = 0;
            }
            
            mainImage.src = thumbnails[index].src;

            // Remove a classe 'active-thumb' de todas e adiciona à atual
            thumbnails.forEach(t => t.classList.remove('active-thumb'));
            thumbnails[index].classList.add('active-thumb');

            currentImageIndex = index; // Atualiza o índice da imagem atual
        };

        // Event listeners para as miniaturas
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', () => {
                updateCarousel(index);
            });
        });

        // Event listeners para as setas (se existirem)
        if (prevButton && nextButton) {
            prevButton.addEventListener('click', () => {
                updateCarousel(currentImageIndex - 1);
            });

            nextButton.addEventListener('click', () => {
                updateCarousel(currentImageIndex + 1);
            });
        }

        // Garante que a primeira miniatura esteja ativa ao carregar a página
        updateCarousel(0); 
    }

});