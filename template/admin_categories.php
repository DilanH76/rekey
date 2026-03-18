<?php
$pageTitle = 'ReKey - Catégories & Plateformes';
$currentAdminTab = 'categories';
ob_start();
?>

<div class="admin-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 0.5rem;">Catégories & Consoles</h1>
    <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Gérez les genres de jeux et les consoles disponibles sur la plateforme.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">

    <div class="admin-table-wrapper" style="padding: 2rem;">
        <h2 style="color: var(--cyan); margin-bottom: 1.5rem; text-transform: uppercase; font-size: 1.2rem;">Genres de jeux</h2>
        
        <form action="/Admin/addCategory" method="POST" style="display: flex; gap: 0.5rem; margin-bottom: 2rem;">
            <input type="text" name="label" placeholder="Ex: RPG" class="form-control" required style="padding: 0.6rem 1rem;">
            <button type="submit" class="btn btn-neon" style="padding: 0.6rem 1.2rem;">Ajouter</button>
        </form>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($categories as $cat): ?>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-light);">
                    <span style="font-weight: 600;"><?= htmlspecialchars($cat->getLabel()) ?></span>
                    
                    <form action="/Admin/deleteCategory/<?= $cat->getIdCategory() ?>" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                        <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; text-decoration: underline; font-weight: 600;">Supprimer</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="admin-table-wrapper" style="padding: 2rem;">
        <h2 style="color: var(--cyan); margin-bottom: 1.5rem; text-transform: uppercase; font-size: 1.2rem;">Consoles & PC</h2>
        
        <form action="/Admin/addPlatform" method="POST" style="display: flex; flex-direction: column; gap: 0.8rem; margin-bottom: 2rem;">
            <input type="text" name="label" placeholder="Nom (ex: PS6)" class="form-control" required style="padding: 0.6rem 1rem;">
            <input type="text" name="icon_svg" placeholder="Chemin icône (ex: /assets/ico/ps6.svg)" class="form-control" required style="padding: 0.6rem 1rem;">
            <button type="submit" class="btn btn-neon" style="padding: 0.6rem 1.2rem; justify-content: center;">Ajouter la plateforme</button>
        </form>

        <ul style="list-style: none; padding: 0; margin: 0;">
            <?php foreach ($platforms as $plat): ?>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-light);">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <img src="<?= htmlspecialchars($plat->getIconSvg()) ?>" alt="Icon" width="24" height="24" style="filter: invert(1);">
                        <span style="font-weight: 600;"><?= htmlspecialchars($plat->getLabel()) ?></span>
                    </div>
                    
                    <form action="/Admin/deletePlatform/<?= $plat->getIdPlatform() ?>" method="POST" onsubmit="return confirm('Supprimer cette plateforme ?');">
                        <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; text-decoration: underline; font-weight: 600;">Supprimer</button>
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