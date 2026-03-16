<?php
$pageTitle = 'ReKey - Administration';
ob_start();
?>

<div class="admin-layout" style="display: flex; gap: 2rem; max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
    
    <aside class="admin-sidebar" style="width: 250px; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); height: fit-content;">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #00ffcc;">Menu Admin</h3>
        <nav style="display: flex; flex-direction: column; gap: 1rem;">
            <a href="/Admin/dashboard" style="color: white; text-decoration: none; padding: 0.5rem; background: rgba(0, 255, 204, 0.1); border-left: 3px solid #00ffcc;">Tableau de bord</a>
            <a href="/Admin/users" style="color: #aaa; text-decoration: none; padding: 0.5rem; transition: 0.3s;">Gestion des Utilisateurs</a>
            <a href="/Admin/ads" style="color: #aaa; text-decoration: none; padding: 0.5rem; transition: 0.3s;">Modération des Annonces</a>
            <a href="/Admin/categories" style="color: #aaa; text-decoration: none; padding: 0.5rem; transition: 0.3s;">Catégories & Plateformes</a>
        </nav>
    </aside>

    <main class="admin-content" style="flex: 1;">
        <h1 style="margin-top: 0;">Tableau de Bord</h1>
        <p style="color: #aaa; margin-bottom: 2rem;">Bienvenue dans l'espace d'administration, <?= htmlspecialchars($_SESSION['user_pseudo'] ?? 'Admin') ?>.</p>

        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            
            <div class="stat-card" style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); text-align: center;">
                <div style="font-size: 2.5rem; font-weight: bold; color: #00ffcc;">--</div>
                <div style="color: #aaa; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-top: 0.5rem;">Utilisateurs Inscrits</div>
            </div>

            <div class="stat-card" style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); text-align: center;">
                <div style="font-size: 2.5rem; font-weight: bold; color: #00ffcc;">--</div>
                <div style="color: #aaa; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-top: 0.5rem;">Annonces en Ligne</div>
            </div>

            <div class="stat-card" style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); text-align: center;">
                <div style="font-size: 2.5rem; font-weight: bold; color: #00ffcc;">--</div>
                <div style="color: #aaa; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-top: 0.5rem;">Ventes Réalisées</div>
            </div>

        </div>
    </main>

</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>