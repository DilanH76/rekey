<?php
$pageTitle = 'ReKey - Modération des Annonces';
$currentAdminTab = 'ads';
ob_start();
?>

<div class="admin-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 0.5rem;">Modération</h1>
    <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Consultez et modérez les jeux mis en vente sur la plateforme.</p>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Titre</th>
                <th>Prix</th>
                <th>Vendeur</th>
                <th>Statut</th>
                <th>Date</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ads as $ad): ?>
                <tr>
                    <td>
                        <img src="<?= $ad->getCoverImageBase64() ?>" alt="Cover" style="width: 50px; height: 70px; border-radius: 4px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);">
                    </td>
                    <td style="font-weight: bold;">
                        <a href="/Ad/show/<?= $ad->getIdAds() ?>" target="_blank" style="color: var(--cyan); text-decoration: none;">
                            <?= htmlspecialchars($ad->getTitle()) ?>
                        </a>
                    </td>
                    <td><?= number_format($ad->getPrice(), 2, ',', '&nbsp;') ?>&nbsp;€</td>
                    <td style="color: var(--text-muted);">
                        <?= htmlspecialchars($ad->getUser()->getPseudo()) ?>
                    </td>
                    <td>
                        <?php if ($ad->getStatus() === 'disponible'): ?>
                            <span class="badge-admin">En vente</span>
                        <?php else: ?>
                            <span class="badge-sold">Vendu</span>
                        <?php endif; ?>
                    </td>
                    <td style="color: var(--text-muted); font-size: 0.9rem;">
                        <?= $ad->getCreatedAt()->format('d/m/Y') ?>
                    </td>
                    <td style="text-align: right;">
                        <form action="/Admin/deleteAd/<?= $ad->getIdAds() ?>" method="POST" onsubmit="return confirm('Attention : Cette action est irréversible. Confirmer la suppression ?');" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Supprimer</button>
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