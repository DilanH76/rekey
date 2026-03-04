<?php
$pageTitle = 'ReKey - Accueil';
ob_start();
?>

<section class="home-temp-container">
    <h1 class="home-temp-title">
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