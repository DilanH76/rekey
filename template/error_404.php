<?php
$pageTitle = 'ReKey - 404';
ob_start();
?>

<div class="ambient-glow glow-rose" style="top: 20%; left: 50%; transform: translateX(-50%); width: 60vw; height: 60vw;"></div>

<section class="error-page container">
    <div class="error-content">
        
        <div class="error-code">404</div>
        <h1 class="error-title">Page Introuvable</h1>
        
        <p class="error-description">
            <strong>La page que vous chercher n'existent pas.</strong><br><br>
            Il semblerait que cette page n'ait pas encore été crée ou qu'elle ait été supprimée.
        </p>

        <a href="/Home" class="btn btn-neon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Retourner à l'accueil
        </a>

    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>