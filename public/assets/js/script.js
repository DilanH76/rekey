document.addEventListener('DOMContentLoaded', () => {
    // --- GESTION DES MESSAGES FLASH ---
    const flashMessage = document.querySelector('.flash-message');
    
    // Si un message flash existe sur la page
    if (flashMessage) {
        // On lance un minuteur de 4 secondes (4000 millisecondes)
        setTimeout(() => {
            // On lance une transition douce pour le faire disparaître et remonter
            flashMessage.style.transition = 'opacity 0.5s ease, top 0.5s ease';
            flashMessage.style.opacity = '0';
            flashMessage.style.top = '4rem';
            
            // On attend 0.5s (le temps de l'animation) puis on le supprime complètement du HTML
            setTimeout(() => {
                flashMessage.remove();
            }, 500);
            
        }, 4000);
    }
});

document.addEventListener("DOMContentLoaded", () => {
  const headerElement = document.querySelector("header");

  function handleScroll() {
    if (window.scrollY > 50) {
      headerElement.classList.add("scrolled");
    } else {
      headerElement.classList.remove("scrolled");
    }
  }

  window.addEventListener("scroll", handleScroll);
});

// recherche

const searchBtn = document.getElementById("toggle-search"); // loupe
const closeBtn = document.querySelector(".close-search"); // croix
const capsule = document.querySelector(".nav-center-capsule"); // conteneur
const searchInput = document.getElementById("search-input"); // champ texte

// 1 clique sur la LOUPE
searchBtn.addEventListener("click", () => {
  capsule.classList.add("search-active");
  setTimeout(() => searchInput.focus(), 100);
});

// 2clique sur la CROIX
closeBtn.addEventListener("click", () => {
  capsule.classList.remove("search-active");
});

// 3 Fermer si on clique ailleurs sur la page
document.addEventListener("click", (e) => {
  if (
    !capsule.contains(e.target) &&
    capsule.classList.contains("search-active")
  ) {
    capsule.classList.remove("search-active");
  }
});
