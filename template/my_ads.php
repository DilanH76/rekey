<?php
$pageTitle = 'ReKey - Mes Annonces';
ob_start();
?>

<section class="profile-container">
    <div class="profile-card" style="max-width: 1000px;"> <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 class="profile-title">Mes Annonces en vente (<?= count($userAds) ?>)</h1>
            <a href="/Ad/add" class="btn-neon" style="padding: 0.5rem 1rem; font-size: 0.9rem;">+ Vendre un jeu</a>
        </div>
        
        <hr class="profile-divider">

        <?php if (empty($userAds)): ?>
            <div class="empty-state" style="text-align: center; margin-top: 2rem;">
                <p>Vous n'avez aucune annonce en ligne pour le moment.</p>
            </div>
        <?php else: ?>

            <div class="ad-grid">
                <?php foreach ($userAds as $ad): ?>
                    <a href="/Ad/show/<?= $ad->getIdAds() ?>" class="ad-card-link">
                        <article class="ad-card">
                            <div class="ad-card-image">
                                <img src="<?= $ad->getCoverImageBase64() ?>" alt="Cover de <?= htmlspecialchars($ad->getTitle()) ?>">
                                <span class="ad-price"><?= number_format($ad->getPrice(), 2, ',', ' ') ?> €</span>
                            </div>

                            <div class="ad-card-content">
                                <div class="ad-card-header">
                                    <h2 class="ad-card-title"><?= htmlspecialchars($ad->getTitle()) ?></h2>
                                    <div class="ad-platform-icon" title="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                                        <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>" width="24" height="24">
                                    </div>
                                </div>

                                <div class="ad-card-meta">
                                    <span class="ad-category"> <?= htmlspecialchars($ad->getCategory()->getLabel()) ?></span>
                                </div>

                                <div class="ad-card-footer">
                                    <span class="ad-date">Mis en ligne le <?= $ad->getCreatedAt()->format('d/m/Y') ?></span>
                                </div>
                            </div>
                        </article>
                    </a>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
        
        <div class="profile-actions" style="margin-top: 2rem;">
            <a href="/Profile" class="btn-outline">Retour au profil</a>
        </div>

    </div>
</section>
<!-- TODO Nettoyer CSS -->
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>