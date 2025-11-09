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

document.addEventListener("DOMContentLoaded", () => {
    // 1. Encontra TODOS os formulários de favoritar na página
    const favoriteForms = document.querySelectorAll(".form-favorite-toggle");

    // 2. Adiciona um "ouvinte" para CADA formulário
    favoriteForms.forEach((form) => {
        // Dispara quando o formulário for enviado (ex: clique no botão)
        form.addEventListener("submit", function (event) {
            // 3. O SEU COMANDO: Impede o envio do formulário e o reload!
            event.preventDefault();

            // 4. Envia os dados do formulário "nos bastidores" (AJAX)
            fetch(form.action, {
                method: "POST",
                body: new FormData(form), // Envia os dados (incluindo o _token)
                headers: {
                    // Diz ao Laravel que esta é uma requisição AJAX
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
                .then((response) => {
                    // 1. Se a resposta for OK (status 200),
                    // o usuário está logado e a ação funcionou.
                    if (response.ok) {
                        toggleFavoriteIcon(form); // Troca o ícone
                        return; // Termina a execução
                    }

                    // 2. Se a resposta for 401 (Não Autorizado)
                    // O middleware 'auth' envia isso para pedidos AJAX de visitantes.
                    if (response.status === 401) {
                        // Redireciona o navegador do usuário para a página de login
                        window.location.href = "/login";
                        return;
                    }

                    // 3. Se for qualquer outro erro (ex: 404, 500)
                    // Lança um erro para ser pego pelo .catch()
                    throw new Error("Falha na resposta do servidor.");
                })
                .catch((error) => {
                    console.error("Erro ao favoritar:", error);
                });
        });
    });

    /**
     * Esta função "troca" o ícone do coração
     * (de vazio para cheio, ou de cheio para vazio)
     */
    function toggleFavoriteIcon(form) {
        // Encontra o ícone DENTRO do formulário que foi clicado
        const icon = form.querySelector("i"); // Acha a tag <i>

        if (icon) {
            // Verifica qual classe o ícone tem e faz a troca
            if (icon.classList.contains("far")) {
                // 'far' é o coração VAZIO
                icon.classList.remove("far", "fa-heart");
                icon.classList.add("fas", "fa-heart"); // 'fas' é o coração CHEIO
                icon.style.color = "red";
            } else {
                // Se ele é 'fas' (CHEIO)
                icon.classList.remove("fas", "fa-heart");
                icon.classList.add("far", "fa-heart"); // Troca para VAZIO
                icon.style.color = "white"; // (ou a cor padrão que você usa)
            }
        }

        // BÔNUS: Se estivermos na página da Wishlist, o card deve sumir
        const wishlistCard = form.closest(".wishlist-card");
        if (wishlistCard) {
            wishlistCard.style.display = "none"; // Faz o card sumir
        }
    }
});

//carrinho

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
});
