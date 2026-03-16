<?php
// On sécurise l'onglet actif au cas où on oublierait de le définir
$currentAdminTab = $currentAdminTab ?? 'dashboard';

// On commence à capturer le bloc complet de l'administration
ob_start();
?>

<div class="admin-layout" style="display: flex; gap: 2rem; max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
    
    <aside class="admin-sidebar" style="width: 250px; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); height: fit-content;">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #00ffcc;">Menu Admin</h3>
        <nav style="display: flex; flex-direction: column; gap: 1rem;">
            
            <a href="/Admin/dashboard" style="text-decoration: none; padding: 0.5rem; <?= $currentAdminTab === 'dashboard' ? 'color: white; background: rgba(0, 255, 204, 0.1); border-left: 3px solid #00ffcc;' : 'color: #aaa; transition: 0.3s;' ?>">Tableau de bord</a>
            
            <a href="/Admin/users" style="text-decoration: none; padding: 0.5rem; <?= $currentAdminTab === 'users' ? 'color: white; background: rgba(0, 255, 204, 0.1); border-left: 3px solid #00ffcc;' : 'color: #aaa; transition: 0.3s;' ?>">Gestion des Utilisateurs</a>
            
            <a href="/Admin/ads" style="text-decoration: none; padding: 0.5rem; <?= $currentAdminTab === 'ads' ? 'color: white; background: rgba(0, 255, 204, 0.1); border-left: 3px solid #00ffcc;' : 'color: #aaa; transition: 0.3s;' ?>">Modération des Annonces</a>
            
            <a href="/Admin/categories" style="text-decoration: none; padding: 0.5rem; <?= $currentAdminTab === 'categories' ? 'color: white; background: rgba(0, 255, 204, 0.1); border-left: 3px solid #00ffcc;' : 'color: #aaa; transition: 0.3s;' ?>">Catégories & Plateformes</a>
            
        </nav>
    </aside>

    <main class="admin-content" style="flex: 1;">
        <?= $adminContent ?>
    </main>

</div>

<?php 
// On emballe tout ce qu'on vient de faire (Sidebar + Contenu) pour le donner au layout principal
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>