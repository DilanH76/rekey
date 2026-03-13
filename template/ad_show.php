<?php
$pageTitle = 'Rekey - ' . htmlspecialchars($ad->getTitle());
ob_start();
?>


<div class="ad-show-container">
    <div class="ad-show-card">
        <div class="ad-show-image">
            <img src="<?= $ad->getCoverImageBase64() ?>" alt="Image de <?= htmlspecialchars($ad->getTitle()) ?>">
        </div>

        <div class="ad-show-details">
            <div class="ad-show-header">
                <h1><?= htmlspecialchars($ad->getTitle()) ?></h1>
                <div class="ad-platform-badge">
                    <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>" width="20" height="20">
                    <span><?= htmlspecialchars($ad->getPlatform()->getLabel()) ?></span>
                </div>
            </div>

            <p class="ad-category-tag"><?= htmlspecialchars($ad->getCategory()->getLabel()) ?></p>

            <div class="ad-price-block">
                <span class="price-label">Prix de vente :</span>
                <span class="price-value"><?= number_format($ad->getPrice(), 2, ',', ' ') ?> €</span>
            </div>

            <div class="ad-description">
                <h3>Notes du vendeur</h3>
                <p>
                    <?php if (!empty($ad->getDescription())): ?>
                        <?= nl2br(htmlspecialchars($ad->getDescription())) ?>
                    <?php else: ?>
                        <span class="empty-desc">Aucune note supplémentaire.</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="ad-seller-card">
                <img src="<?= $ad->getUser()->getAvatarBase64() ?>" alt="Avatar">
                <div class="seller-info">
                    <span class="seller-name">Vendu par <strong><?= htmlspecialchars($ad->getUser()->getPseudo()) ?></strong></span>
                    <span class="ad-date">Mis en ligne le <?= $ad->getCreatedAt()->format('d/m/Y') ?></span>
                </div>
            </div>

            <div class="ad-actions" style="margin-top: 2rem;">
                <?php if ($ad->getStatus() === 'vendu'): ?>
                    <button class="btn-outline" disabled style="opacity: 0.5; cursor: not-allowed; width: 100%;">
                        Ce jeu a déjà été vendu
                    </button>

                <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $ad->getUser()->getIdUser()): ?>
                    <button class="btn-outline" disabled style="opacity: 0.5; cursor: not-allowed; width: 100%;">
                        Ceci est votre annonce
                    </button>

                <?php else: ?>
                    <a href="/Order/checkout/<?= $ad->getIdAds() ?>" class="btn-neon" style="display: block; text-align: center; width: 100%;">
                        Acheter immédiatement
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
<!--  TODO Nettoyer CSS en ligne -->
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>