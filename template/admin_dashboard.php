<?php
$pageTitle = 'ReKey - Administration';
$currentAdminTab = 'dashboard';
ob_start();
?>

<div class="admin-header">
    <h1 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 0.5rem;">Tableau de Bord</h1>
    <p class="auth-subtitle" style="text-align: left;">Bienvenue dans l'espace d'administration, <?= htmlspecialchars($_SESSION['user_pseudo'] ?? 'Admin') ?>.</p>
</div>

<div class="admin-stats-grid">
    
    <div class="admin-stat-card">
        <div class="admin-stat-value"><?= $totalUsers ?></div>
        <div class="admin-stat-label">Utilisateurs Inscrits</div>
    </div>

    <div class="admin-stat-card rose">
        <div class="admin-stat-value"><?= $activeAds ?></div>
        <div class="admin-stat-label">Annonces en Ligne</div>
    </div>

    <div class="admin-stat-card warning">
        <div class="admin-stat-value"><?= $totalSales ?></div>
        <div class="admin-stat-label">Ventes Réalisées</div>
    </div>

</div>

<?php 
$adminContent = ob_get_clean(); 
require __DIR__ . '/admin_layout.php'; 
?>