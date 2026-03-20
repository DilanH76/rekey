document.addEventListener('DOMContentLoaded', () => {
    // --- GESTION DES MESSAGES FLASH ---
    const flashMessage = document.querySelector('.flash-message');
    
    // Si un message flash existe sur la page
    if (flashMessage) {
        // On lance un minuteur de 4 secondes (4000 millisecondes) avant de le cacher
        setTimeout(() => {
            // LIGNE IMPORTANTE : On désactive l'animation d'entrée pour que la transition puisse prendre le relais
            flashMessage.style.animation = 'none'; 
            
            // On applique la transition de sortie (fondu et remontée)
            flashMessage.style.transition = 'opacity 0.5s ease, top 0.5s ease';
            
            // On déclenche le mouvement (opacité à 0 et on le fait remonter à 4rem, plus haut que le header)
            flashMessage.style.opacity = '0';
            flashMessage.style.top = '6rem';
            
            // On attend la fin de la transition (500ms) pour supprimer l'élément du HTML
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

// --- SCROLL HORIZONTAL (FLÈCHES + DRAG) ---
const slider = document.querySelector('.category-badges');
const btnLeft = document.querySelector('.scroll-left');
const btnRight = document.querySelector('.scroll-right');

if (slider) {
    let isDown = false;
    let startX;
    let scrollLeft;
    let isDragging = false; // Pour savoir si on est en train de glisser ou de cliquer

    // --- LOGIQUE DES FLÈCHES ---
    if (btnLeft && btnRight) {
        btnLeft.addEventListener('click', () => {
            slider.scrollBy({ left: -200, behavior: 'smooth' });
        });
        btnRight.addEventListener('click', () => {
            slider.scrollBy({ left: 200, behavior: 'smooth' });
        });
    }

    // --- LOGIQUE DU DRAG A LA SOURIS ---
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        isDragging = false; // On reset l'état de glissement
        slider.style.cursor = 'grabbing';
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.style.cursor = 'grab';
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.style.cursor = 'grab';
        // On enlève le flag de drag après un court délai pour permettre au clic de passer
        setTimeout(() => { isDragging = false; }, 50);
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2;
        
        // Si on a bougé de plus de 5 pixels, on considère que c'est un drag
        if (Math.abs(walk) > 5) {
            isDragging = true; 
        }
        
        slider.scrollLeft = scrollLeft - walk;
    });

    // --- EMPÊCHER LE CLIC SI ON A GLISSÉ ---
    const badges = slider.querySelectorAll('.badge-tech');
    badges.forEach(badge => {
        badge.addEventListener('click', (e) => {
            if (isDragging) {
                // Si l'utilisateur était en train de glisser, on annule l'ouverture du lien
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
}

// --- SMOOTH SCROLL POUR LES ANCRES ---
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            // Offset pour la Navbar : 4.5rem (soit environ 72px) + une petite marge (10px) = 82px
            const headerOffset = 0; 
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
  
            window.scrollTo({
                 top: offsetPosition,
                 behavior: "smooth"
            });
        }
    });
});

// --- GESTION DU MENU BURGER MOBILE ---
const hamburgerBtn = document.querySelector('.hamburger-btn');
const mobileOverlay = document.querySelector('.mobile-overlay');
const closeMobileBtn = document.querySelector('.close-mobile-menu');

if (hamburgerBtn && mobileOverlay) {
    // Ouvrir le menu
    hamburgerBtn.addEventListener('click', () => {
        mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Empêche de scroller la page derrière le menu
    });

    // Fermer le menu via la croix
    closeMobileBtn.addEventListener('click', () => {
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Réactive le scroll
    });

    // Fermer le menu si on clique sur un lien (pour aller vers PC, PS, etc.)
    const mobileLinks = mobileOverlay.querySelectorAll('.mobile-nav-link');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
}

// --- TOGGLE MOT DE PASSE (OEIL) ---
const togglePasswordBtns = document.querySelectorAll('.toggle-password');

togglePasswordBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        // Sélectionne l'input juste avant le bouton
        const input = this.previousElementSibling;
        const icon = this.querySelector('svg');

        if (input.type === 'password') {
            // Afficher le texte
            input.type = 'text';
            this.classList.add('active');
            // Change l'icône pour l'œil barré
            icon.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            `;
        } else {
            // Masquer le texte
            input.type = 'password';
            this.classList.remove('active');
            // Remet l'icône normale
            icon.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            `;
        }
    });
});