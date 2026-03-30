<?php
$pageTitle = 'ReKey - ' . htmlspecialchars($ad->getTitle());
$pageDesc = 'Achetez la clé numérique pour ' . $ad->getTitle() . ' sur ' . $ad->getPlatform()->getLabel() . ' à seulement ' . number_format($ad->getPrice(), 2, ',', ' ') . ' €. Paiement sécurisé et livraison immédiate.';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 0; left: 10%;"></div>

<div class="container ad-show-page">
    
    <div class="ad-show-main">

        <a href="javascript:history.back()" class="back-link">
            <span class="text-cyan">←</span> Retour aux résultats
        </a>

        <div class="ad-show-cover">
            <img src="<?= $ad->getCoverImageBase64() ?>" alt="Image de <?= htmlspecialchars($ad->getTitle()) ?>">
        </div>

        <div class="ad-show-info">
            <div class="ad-show-header">
                <h1><?= htmlspecialchars($ad->getTitle()) ?></h1>
                <div class="ad-platform-badge" title="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                    <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                </div>
            </div>

            <div class="ad-tags">
                <span class="badge-tech"><?= htmlspecialchars($ad->getCategory()->getLabel()) ?></span>
            </div>

            <div class="ad-description-box">
                <h3><span class="text-cyan">/</span> Description</h3>
                <div class="ad-description-content">
                    <?php if (!empty($ad->getDescription())): ?>
                        <p><?= nl2br(htmlspecialchars($ad->getDescription())) ?></p>
                    <?php else: ?>
                        <p class="empty-desc">Aucune description supplémentaire fournie par le vendeur.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <aside class="ad-show-sidebar">
        <div class="transaction-card">
            
            <div class="ad-price-block">
                <span class="price-label">Prix de vente</span>
                <span class="price-value"><?= number_format($ad->getPrice(), 2, ',', ' ') ?> €</span>
            </div>

            <div class="ad-actions">
                <?php if ($ad->getStatus() === 'vendu'): ?>
                    <button class="btn btn-outline disabled-btn">
                        Ce jeu a déjà été vendu
                    </button>
                <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] === $ad->getUser()->getIdUser()): ?>
                    <a href="/Ad/edit/<?= $ad->getIdAds() ?>" class="btn btn-neon buy-btn">
                        Modifier mon annonce
                    </a>
                <?php else: ?>
                    <a href="/Order/checkout/<?= $ad->getIdAds() ?>" class="btn btn-neon buy-btn">
                        Acheter immédiatement
                    </a>
                <?php endif; ?>
            </div>

            <div class="seller-card">
                <div class="seller-header">
                    <img src="<?= $ad->getUser()->getAvatarBase64() ?>" alt="Avatar de <?= htmlspecialchars($ad->getUser()->getPseudo()) ?>" class="seller-avatar">
                    <div class="seller-identity">
                        <span class="seller-label">Vendu par</span>
                        <span class="seller-name"><?= htmlspecialchars($ad->getUser()->getPseudo()) ?></span>
                    </div>
                </div>
                <div class="seller-footer">
                    <span class="ad-date">Mis en ligne le <?= $ad->getCreatedAt()->format('d/m/Y') ?></span>
                </div>
            </div>

        </div>
    </aside>

</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>