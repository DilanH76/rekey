<?php
$pageTitle = 'ReKey - Accueil';
ob_start();
?>

<section class="hero-section">
    <h1>Dernières annonces</h1>
    <p>trouvez votre prochain jeu au meilleur prix, vendu par la communauté.</p>
</section>

<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']?>">
        <?= $_SESSION['flash']['message'] ?>
    </div>
    <?php unset($_SESSION['flash']);?>
<?php endif; ?>

<section class="ads-container">
    <?php if (empty($ads)): ?>
        <div class="empty-state">
            <p>Aucune annonce n'est disponible pour le moment. Soyez le premier à vendre un jeu !</p>
            <a href="/Ad/add" class="btn-neon">Vendre un jeu</a>
        </div>
    <?php else: ?>

        <div class="ad-grid">
            <?php foreach ($ads as $ad): ?>

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
                                    <?= $ad->getPlatform()->getIconSvg() ?>
                                </div>
                            </div>

                            <div class="ad-card-meta">
                                <span class="ad-category"> <?= htmlspecialchars($ad->getCategory()->getLabel()) ?></span>
                            </div>

                            <div class="ad-card-footer">
                                <div class="ad-seller">
                                    <img src="<?= $ad->getUser()->getAvatarBase64() ?>" alt="Avatar" class="seller-avatar">
                                    <span><?= htmlspecialchars($ad->getUser()->getPseudo()) ?></span>
                                </div>
                                <span class="ad-date"><?= $ad->getCreatedAt()->format('d/m/Y') ?></span>
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