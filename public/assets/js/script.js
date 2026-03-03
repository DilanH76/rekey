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