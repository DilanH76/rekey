<?php
$pageTitle = 'ReKey - Mes Annonces';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 10%; left: -10%;"></div>
<div class="ambient-glow glow-rose" style="bottom: 10%; right: -10%;"></div>

<section class="my-ads-page container">

    <a href="javascript:history.back()" class="back-link">
        <span class="text-cyan">←</span> Retour
    </a>

    <div class="my-ads-header">
        <div>
            <h1>Mes Annonces <span style="color: var(--cyan);">[<?= count($userAds) ?>]</span></h1>
            <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Gérez les jeux que vous avez mis en vente.</p>
        </div>
        
        <a href="/Ad/add" class="btn btn-neon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Vendre un jeu
        </a>
    </div>

    <?php if (empty($userAds)): ?>
        <div class="empty-state" style="background: var(--bg-card); padding: 4rem; text-align: center; border-radius: var(--radius-md); border: 1px solid var(--border-light); backdrop-filter: var(--glass-blur);">
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2rem;">Vous n'avez aucune annonce en ligne pour le moment.</p>
            <a href="/Ad/add" class="btn btn-outline">Mettre en vente mon premier jeu</a>
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
                                <h2 class="ad-title"><?= htmlspecialchars($ad->getTitle()) ?></h2>
                                <div class="ad-platform-icon" title="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                                    <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                                </div>
                            </div>

                            <div class="ad-card-meta">
                                <span class="ad-category"><?= htmlspecialchars($ad->getCategory()->getLabel()) ?></span>
                            </div>

                            <div class="ad-card-footer">
                                <span class="ad-date"><?= $ad->getCreatedAt()->format('d/m/Y') ?></span>
                                
                                <?php if ($ad->getStatus() === 'vendu'): ?>
                                    <span style="color: var(--danger); font-weight: 700; text-transform: uppercase; font-size: 0.85rem;">Vendu</span>
                                <?php else: ?>
                                    <span style="color: var(--success); font-weight: 700; text-transform: uppercase; font-size: 0.85rem;">En ligne</span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </article>
                </a>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>