<?php
$pageTitle = 'ReKey - Accueil';
ob_start();
?>

<section class="hero-section">
    <h1>Dernières annonces</h1>
    <p>Trouvez votre prochain jeu au meilleur prix, vendu par la communauté.</p>
</section>

<section class="search-section" style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem;">
    <form action="/Home" method="GET" class="search-form" style="display: flex; gap: 1rem; flex-wrap: wrap; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); align-items: center;">
        
        <div class="form-group" style="flex: 1; min-width: 200px; margin: 0;">
            <input type="text" name="q" placeholder="Rechercher un jeu..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="width: 100%; margin: 0;">
        </div>

        <div class="form-group" style="min-width: 150px; margin: 0;">
            <select name="category" style="width: 100%; margin: 0;">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->getIdCategory() ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat->getIdCategory()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->getLabel()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="min-width: 150px; margin: 0;">
            <select name="platform" style="width: 100%; margin: 0;">
                <option value="">Toutes les plateformes</option>
                <?php foreach ($platforms as $plat): ?>
                    <option value="<?= $plat->getIdPlatform() ?>" <?= (isset($_GET['platform']) && $_GET['platform'] == $plat->getIdPlatform()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($plat->getLabel()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="min-width: 150px; margin: 0;">
            <select name="sort" onchange="this.form.submit()" style="width: 100%; margin: 0;">
                <option value="date_desc" <?= ($sort === 'date_desc') ? 'selected' : '' ?>>Plus récents</option>
                <option value="price_asc" <?= ($sort === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
                <option value="price_desc" <?= ($sort === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
            </select>
        </div>

        <button type="submit" class="btn-neon" style="margin: 0; padding: 0.5rem 1.5rem;">Filtrer</button>
        
        <?php if (!empty($_GET['q']) || !empty($_GET['category']) || !empty($_GET['platform']) || (isset($_GET['sort']) && $_GET['sort'] !== 'date_desc')): ?>
            <a href="/Home" class="btn-outline" style="display: flex; align-items: center; padding: 0.5rem 1rem;">Réinitialiser</a>
        <?php endif; ?>

    </form>
</section>

<section class="ads-container">
    <?php if (empty($ads)): ?>
        <div class="empty-state">
            <?php if (!empty($_GET['q']) || !empty($_GET['category']) || !empty($_GET['platform'])): ?>
                <p>Aucun résultat ne correspond à vos critères de recherche.</p>
                <a href="/Home" class="btn-neon">Retirer les filtres</a>
            <?php else: ?>
                <p>Aucune annonce n'est disponible pour le moment.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/Ad/add" class="btn-neon">Vendre le premier jeu</a>
                <?php else: ?>
                    <a href="/Auth/login" class="btn-neon">Connectez-vous pour vendre</a>
                <?php endif; ?>
            <?php endif; ?>
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
                                    <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>" width="24" height="24">
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

        <?php if ($totalPages > 1): ?>
            <div class="pagination" style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem;">
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php 
                        // array_merge garde en mémoire les filtres actuels et ajoute juste le numéro de la page
                        $queryParams = array_merge($_GET, ['page' => $i]);
                        $pageUrl = '?' . http_build_query($queryParams);
                    ?>
                    <a href="<?= htmlspecialchars($pageUrl) ?>" 
                       style="padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; transition: 0.3s;
                       <?= ($i === $currentPage) ? 'background: #00ffcc; color: #000; font-weight: bold;' : 'background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1);' ?>">
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