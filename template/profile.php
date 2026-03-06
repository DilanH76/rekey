<?php
$pageTitle = 'ReKey - Mon Profil';
ob_start();
?>

<section class="profile-container">
    
    <div class="profile-card">
        
        <h1 class="profile-title">Mon Profil</h1>
        
        <div class="profile-header">
            
            <div class="profile-avatar-wrapper">
                <img src="<?= $user->getAvatarBase64() ?>" 
                     alt="Avatar de <?= htmlspecialchars($user->getPseudo()) ?>" 
                     class="profile-avatar-img" 
                     style="background: rgba(255, 255, 255, 0.05);" />
            </div>

            <div class="profile-info">
                <p><strong>Pseudo :</strong> <span class="profile-highlight"><?= htmlspecialchars($user->getPseudo()) ?></span></p>
                <p><strong>Nom complet :</strong> <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
                <p class="profile-date">Inscrit le <?= $user->getCreatedAt()->format('d/m/Y') ?></p>
            </div>
            
        </div>

        <hr class="profile-divider">

        <div class="profile-actions">
            <a href="/Profile/edit" class="btn-neon">
                Éditer le profil
            </a>
        </div>

    </div>

    <div class="profile-ads-section" style="margin-top: 4rem;">
        
        <h2 class="profile-title">Mes Annonces en vente (<?= count($userAds) ?>)</h2>
        <hr class="profile-divider">

        <?php if (empty($userAds)): ?>
            <div class="empty-state" style="text-align: center; margin-top: 2rem;">
                <p>Vous n'avez aucune annonce en ligne pour le moment.</p>
                <br>
                <a href="/Ad/add" class="btn-neon">Vendre mon premier jeu</a>
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
                                        <?= $ad->getPlatform()->getIconSvg() ?>
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

    </div>

</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>