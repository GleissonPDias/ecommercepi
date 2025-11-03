import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Espera o documento carregar completamente
document.addEventListener("DOMContentLoaded", () => {
    // =======================================================
    // == LÓGICA DO SIDEBAR (MENU)
    // =======================================================
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

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && sidebar.classList.contains("is-open")) {
            closeMenu();
        }
    });

    // =======================================================
    // == LÓGICA DO CARROSSEL DA HOME PAGE (SLIDES COM DOTS)
    // =======================================================
    const dots = document.querySelectorAll(".pagination-dots .dot");
    const slides = document.querySelectorAll(".featured-games-slide");
    // Seletores mais específicos para evitar conflito com o outro carrossel
    const homePrevButton = document.querySelector(
        ".featured-carousel-container .carousel-arrow.prev"
    );
    const homeNextButton = document.querySelector(
        ".featured-carousel-container .carousel-arrow.next"
    );

    // Verifica se os elementos do carrossel da HOME existem
    if (dots.length > 0 && slides.length > 0) {
        let currentSlideIndex = 0;
        const totalSlides = slides.length;

        // 1. Função principal para trocar de slide
        const goToSlide = (index) => {
            slides.forEach((s) => s.classList.remove("active"));
            dots.forEach((d) => d.classList.remove("active"));
            slides[index].classList.add("active");
            dots[index].classList.add("active");
            currentSlideIndex = index;
        };

        // 2. Adiciona evento aos dots
        dots.forEach((dot) => {
            dot.addEventListener("click", () => {
                const slideIndex = parseInt(dot.getAttribute("data-slide"));
                goToSlide(slideIndex);
            });
        });

        // 3. Adiciona evento às setas
        if (homePrevButton && homeNextButton) {
            homeNextButton.addEventListener("click", () => {
                let newIndex = currentSlideIndex + 1;
                if (newIndex >= totalSlides) {
                    newIndex = 0;
                }
                goToSlide(newIndex);
            });

            homePrevButton.addEventListener("click", () => {
                let newIndex = currentSlideIndex - 1;
                if (newIndex < 0) {
                    newIndex = totalSlides - 1;
                }
                goToSlide(newIndex);
            });
        }
    }

    // ===============================================================
    // == LÓGICA DA GALERIA DA PÁGINA DE PRODUTO (THUMBNAILS)
    // ===============================================================

    // 1. Encontre os elementos (só existem na página do produto)
    const mainImage = document.getElementById("mainProductImage");
    const thumbnails = document.querySelectorAll(".thumbnail-gallery img");
    // Seletores mais específicos
    const productPrevButton = document.querySelector(
        ".main-banner .carousel-arrow.prev"
    );
    const productNextButton = document.querySelector(
        ".main-banner .carousel-arrow.next"
    );

    // 2. Verifica se estamos na página de produto
    if (
        mainImage &&
        thumbnails.length > 0 &&
        productPrevButton &&
        productNextButton
    ) {
        let currentIndex = 0; // Guarda a posição da imagem ativa

        // 3. Função para Mudar a Imagem
        const setActiveImage = (index) => {
            const activeThumb = thumbnails[index];
            const newImageSrc = activeThumb.dataset.largeSrc;
            mainImage.src = newImageSrc;
            thumbnails.forEach((thumb) =>
                thumb.classList.remove("active-thumb")
            );
            activeThumb.classList.add("active-thumb");
            currentIndex = index;
        };

        // 4. Lógica para as MINIATURAS (Thumbnails)
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener("click", () => {
                setActiveImage(index);
            });
        });

        // 5. Lógica para o BOTÃO "PRÓXIMO"
        productNextButton.addEventListener("click", () => {
            let nextIndex = (currentIndex + 1) % thumbnails.length;
            setActiveImage(nextIndex);
        });

        // 6. Lógica para o BOTÃO "ANTERIOR"
        productPrevButton.addEventListener("click", () => {
            let prevIndex =
                (currentIndex - 1 + thumbnails.length) % thumbnails.length;
            setActiveImage(prevIndex);
        });
    }

    // O CÓDIGO DUPLICADO FOI REMOVIDO DAQUI
});
