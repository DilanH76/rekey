<?php
$pageTitle = 'ReKey - Catégories & Plateformes';
$currentAdminTab = 'categories';
ob_start();
?>

<h1 style="margin-top: 0;">Catégories & Plateformes</h1>
<p style="color: #aaa; margin-bottom: 2rem;">Gérez les genres de jeux et les consoles disponibles sur la plateforme.</p>

<div style="display: flex; gap: 2rem; flex-wrap: wrap;">

    <div style="flex: 1; min-width: 300px; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
        <h2 style="color: #00ffcc; margin-top: 0;">Genres de jeux</h2>
        
        <form action="/Admin/addCategory" method="POST" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="text" name="label" placeholder="Nouvelle catégorie (ex: RPG)" required style="flex: 1; padding: 0.5rem; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 4px;">
            <button type="submit" style="background: rgba(0, 255, 204, 0.2); color: #00ffcc; border: 1px solid #00ffcc; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Ajouter</button>
        </form>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($categories as $cat): ?>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span><?= htmlspecialchars($cat->getLabel()) ?></span>
                    
                    <form action="/Admin/deleteCategory/<?= $cat->getIdCategory() ?>" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                        <button type="submit" style="background: none; border: none; color: #ff0055; cursor: pointer; text-decoration: underline;">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div style="flex: 1; min-width: 300px; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
        <h2 style="color: #00ffcc; margin-top: 0;">Consoles & PC</h2>
        
        <form action="/Admin/addPlatform" method="POST" style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="text" name="label" placeholder="Nom (ex: PS6)" required style="padding: 0.5rem; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 4px;">
            <input type="text" name="icon_svg" placeholder="Chemin icône (ex: /assets/ico/ps6.svg)" required style="padding: 0.5rem; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 4px;">
            <button type="submit" style="background: rgba(0, 255, 204, 0.2); color: #00ffcc; border: 1px solid #00ffcc; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Ajouter la plateforme</button>
        </form>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($platforms as $plat): ?>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <img src="<?= htmlspecialchars($plat->getIconSvg()) ?>" alt="Icon" width="20" height="20" style="filter: invert(1);">
                        <span><?= htmlspecialchars($plat->getLabel()) ?></span>
                    </div>
                    
                    <form action="/Admin/deletePlatform/<?= $plat->getIdPlatform() ?>" method="POST" onsubmit="return confirm('Supprimer cette plateforme ?');">
                        <button type="submit" style="background: none; border: none; color: #ff0055; cursor: pointer; text-decoration: underline;">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>

<?php 
$adminContent = ob_get_clean(); 
require __DIR__ . '/admin_layout.php'; 
?>