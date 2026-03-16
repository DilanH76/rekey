<?php
$pageTitle = 'ReKey - Administration';
$currentAdminTab = 'dashboard'; // On indique au layout quel menu allumer
ob_start();
?>

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

<?php 
// On passe le relais au layout admin !
$adminContent = ob_get_clean(); 
require __DIR__ . '/admin_layout.php'; 
?>