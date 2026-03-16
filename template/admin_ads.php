<?php
$pageTitle = 'ReKey - Modération des Annonces';
$currentAdminTab = 'ads'; // Allume l'onglet "Modération des Annonces"
ob_start();
?>

<h1 style="margin-top: 0;">Modération des Annonces</h1>
<p style="color: #aaa; margin-bottom: 2rem;">Consultez et modérez les jeux mis en vente sur la plateforme.</p>

<div style="background: rgba(255,255,255,0.05); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02);">
                <th style="padding: 1rem;">Cover</th>
                <th style="padding: 1rem;">Titre</th>
                <th style="padding: 1rem;">Prix</th>
                <th style="padding: 1rem;">Vendeur</th>
                <th style="padding: 1rem;">Statut</th>
                <th style="padding: 1rem;">Date</th>
                <th style="padding: 1rem; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ads as $ad): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    
                    <td style="padding: 1rem;">
                        <img src="<?= $ad->getCoverImageBase64() ?>" alt="Cover" style="width: 60px; height: 35px; border-radius: 4px; object-fit: cover;">
                    </td>
                    
                    <td style="padding: 1rem; font-weight: bold;">
                        <a href="/Ad/show/<?= $ad->getIdAds() ?>" target="_blank" style="color: #00ffcc; text-decoration: none;">
                            <?= htmlspecialchars($ad->getTitle()) ?>
                        </a>
                    </td>
                    
                    <td style="padding: 1rem;"><?= number_format($ad->getPrice(), 2, ',', ' ') ?> €</td>
                    
                    <td style="padding: 1rem; color: #aaa;">
                        <?= htmlspecialchars($ad->getUser()->getPseudo()) ?>
                    </td>
                    
                    <td style="padding: 1rem;">
                        <?php if ($ad->getStatus() === 'disponible'): ?>
                            <span style="background: rgba(0, 255, 204, 0.2); color: #00ffcc; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">En vente</span>
                        <?php else: ?>
                            <span style="background: rgba(255, 0, 85, 0.2); color: #ff0055; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Vendu</span>
                        <?php endif; ?>
                    </td>
                    
                    <td style="padding: 1rem; color: #aaa; font-size: 0.9rem;">
                        <?= $ad->getCreatedAt()->format('d/m/Y') ?>
                    </td>
                    
                    <td style="padding: 1rem; text-align: right;">
                        <form action="/Admin/deleteAd/<?= $ad->getIdAds() ?>" method="POST" onsubmit="return confirm('Attention : Cette action est irréversible. Confirmer la suppression ?');">
                            <button type="submit" style="background: rgba(255, 0, 85, 0.2); color: #ff0055; border: 1px solid #ff0055; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; transition: 0.3s;">
                                Supprimer
                            </button>
                        </form>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
$adminContent = ob_get_clean(); 
require __DIR__ . '/admin_layout.php'; 
?>