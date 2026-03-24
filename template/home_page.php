<?php
$pageTitle = 'ReKey - Accueil';
ob_start();
?>

<div class="ambient-glow glow-cyan"></div>
<div class="ambient-glow glow-rose"></div>

<section class="hero-tech">
    <div class="hero-tech-content">
        <h1 class="glitch-title">Jouez plus.<br>Dépensez moins.</h1>
        <p class="hero-tech-info">Trouvez votre prochaine aventure au meilleur prix. Vendez les clés que vous n'utilisez plus en quelques secondes.</p>
        
        <div class="hero-tech-actions" style="display: flex; gap: 1.5rem; justify-content: center; margin-top: 2rem;">
            <a href="#annonces" class="btn btn-neon">Découvrir les annonces</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/Ad/add" class="btn btn-outline">Vendre une clé</a>
            <?php else: ?>
                <a href="/Auth/register" class="btn btn-outline">Rejoindre le réseau</a>
            <?php endif; ?>
        </div>
    </div>

</section>

<section id="annonces" class="ads-container container" style="position: relative; z-index: 10;">

<!-- NOUVEAU WRAPPER AVEC FLÈCHES -->
    <div class="category-badges-wrapper">
        <button class="scroll-arrow scroll-left" aria-label="Défiler à gauche">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>

        <div class="category-badges">
            <a href="/Home#annonces" class="badge-tech <?= empty($_GET['category']) ? 'active' : '' ?>">Toutes les clés</a>
            <?php foreach ($categories as $cat): ?>
                <a href="/Home?category=<?= $cat->getIdCategory() ?>#annonces" 
                   class="badge-tech <?= (isset($_GET['category']) && $_GET['category'] == $cat->getIdCategory()) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat->getLabel()) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <button class="scroll-arrow scroll-right" aria-label="Défiler à droite">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
    </div>
    <!-- FIN DU WRAPPER -->

    <div class="sort-container">
        <form action="/Home#annonces" method="GET" class="sort-form">
            <?php if (!empty($_GET['q'])): ?>
                <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q']) ?>">
            <?php endif; ?>
            <?php if (!empty($_GET['category'])): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
            <?php endif; ?>
            <?php if (!empty($_GET['platform'])): ?>
                <input type="hidden" name="platform" value="<?= htmlspecialchars($_GET['platform']) ?>">
            <?php endif; ?>

            <label for="sort" class="sort-label">Trier par :</label>
            <select name="sort" id="sort" class="form-control sort-select" onchange="this.form.submit()">
                <option value="date_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'date_desc') ? 'selected' : '' ?>>Plus récentes</option>
                <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
                <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
            </select>
        </form>
    </div>
    
    <?php if (empty($ads)): ?>
        <div class="empty-state" style="text-align: center; padding: 4rem 0;">
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2rem;">Aucune clé ne correspond à vos critères dans la base de données.</p>
            <a href="/Home#annonces" class="btn btn-outline">Réinitialiser les filtres</a>
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
                                <h2 class="ad-title"><?= htmlspecialchars($ad->getTitle()) ?></h2>
                                <div class="ad-platform-icon" title="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                                    <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>">
                                </div>
                            </div>
                            <div class="ad-card-meta">
                                <span class="ad-category"><?= htmlspecialchars($ad->getCategory()->getLabel()) ?></span>
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

        <?php if ($totalPages > 1): ?>
            <div class="pagination" style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 4rem;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php 
                        $queryParams = array_merge($_GET, ['page' => $i]);
                        $pageUrl = '?' . http_build_query($queryParams) . '#annonces';
                    ?>
                    <a href="<?= htmlspecialchars($pageUrl) ?>" class="<?= ($i === $currentPage) ? 'btn btn-neon' : 'btn btn-outline' ?>" style="padding: 0.5rem 1rem;">
                       <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>