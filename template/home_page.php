<?php
$pageTitle = 'ReKey - Accueil';
ob_start();
?>

<section class="container" style="text-align: center; padding: 150px 20px; min-height: 60vh;">
    
    <h1 style="color: var(--cyan); font-size: 3rem; margin-bottom: 1.5rem;">
        <?php if (isset($_SESSION['user_pseudo'])): ?>
            Bienvenue, <?= htmlspecialchars($_SESSION['user_pseudo']) ?> ! 
        <?php else: ?>
            Bienvenue sur ReKey !
        <?php endif; ?>
    </h1>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>