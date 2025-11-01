import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Espera o documento carregar completamente
document.addEventListener("DOMContentLoaded", () => {
    // Seleciona os elementos do DOM
    const openMenuButton = document.querySelector(".btn-menu");
    const closeMenuButton = document.querySelector(".btn-close");
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".overlay");

    // Função para abrir o menu
    const openMenu = () => {
        sidebar.classList.add("is-open");
        overlay.classList.add("is-open");
    };

    // Função para fechar o menu
    const closeMenu = () => {
        sidebar.classList.remove("is-open");
        overlay.classList.remove("is-open");
    };

    // Adiciona os "escutadores" de eventos
    if (openMenuButton && sidebar && overlay) {
        openMenuButton.addEventListener("click", openMenu);
    }

    if (closeMenuButton && sidebar && overlay) {
        closeMenuButton.addEventListener("click", closeMenu);
        overlay.addEventListener("click", closeMenu); // Fecha ao clicar no overlay
    }

    // Opcional: Fecha o menu ao pressionar a tecla "Escape"
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && sidebar.classList.contains("is-open")) {
            closeMenu();
        }
    });

    // --- NOVO CÓDIGO DO CARROSSEL ---
    const dots = document.querySelectorAll(".pagination-dots .dot");
    const slides = document.querySelectorAll(".featured-games-slide");
    const prevButton = document.querySelector(".carousel-arrow.prev");
    const nextButton = document.querySelector(".carousel-arrow.next");

    // Verifica se os elementos do carrossel existem nesta página
    if (dots.length && slides.length) {
        let currentSlideIndex = 0;
        const totalSlides = slides.length;

        // 1. Função principal para trocar de slide
        const goToSlide = (index) => {
            // Remove 'active' de todos os slides e dots
            slides.forEach((s) => s.classList.remove("active"));
            dots.forEach((d) => d.classList.remove("active"));

            // Adiciona 'active' ao novo slide e dot
            slides[index].classList.add("active");
            dots[index].classList.add("active");

            // Atualiza o índice atual
            currentSlideIndex = index;
        };

        // 2. Adiciona evento aos dots
        dots.forEach((dot) => {
            dot.addEventListener("click", () => {
                // Pega o índice do 'data-slide' e converte para número
                const slideIndex = parseInt(dot.getAttribute("data-slide"));
                goToSlide(slideIndex);
            });
        });

        // 3. Adiciona evento às setas (se existirem)
        if (prevButton && nextButton) {
            // Clique na seta "Próximo"
            nextButton.addEventListener("click", () => {
                let newIndex = currentSlideIndex + 1;

                // Se passar do último, volta para o primeiro (loop)
                if (newIndex >= totalSlides) {
                    newIndex = 0;
                }
                goToSlide(newIndex);
            });

            // Clique na seta "Anterior"
            prevButton.addEventListener("click", () => {
                let newIndex = currentSlideIndex - 1;

                // Se for menor que o primeiro, vai para o último (loop)
                if (newIndex < 0) {
                    newIndex = totalSlides - 1;
                }
                goToSlide(newIndex);
            });
        }
    }

    // Verifica se os elementos (dots e slides) existem nesta página
    if (dots.length && slides.length) {
        dots.forEach((dot) => {
            dot.addEventListener("click", () => {
                // Pega o índice do slide pelo atributo 'data-slide'
                const slideIndex = dot.getAttribute("data-slide");

                // 1. Remove 'active' de todos os dots e slides
                dots.forEach((d) => d.classList.remove("active"));
                slides.forEach((s) => s.classList.remove("active"));

                // 2. Adiciona 'active' ao dot clicado
                dot.classList.add("active");

                // 3. Adiciona 'active' ao slide correspondente
                // (slides[slideIndex] funciona porque 'data-slide' é 0, 1, 2...)
                slides[slideIndex].classList.add("active");
            });
        });
    }
    // --- FIM DO CÓDIGO DO CARROSSEL ---
});
