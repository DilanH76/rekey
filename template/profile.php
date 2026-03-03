<?php
$pageTitle = 'ReKey - Mon Profil';
ob_start();
?>

<section class="profile-container">
    
    <div class="profile-card">
        
        <h1 class="profile-title">Mon Profil</h1>
        
        <div class="profile-header">
            
            <div class="profile-avatar-wrapper">
                <?php if ($user->getAvatar()): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($user->getAvatar()) ?>" 
                         alt="Avatar de <?= htmlspecialchars($user->getPseudo()) ?>" 
                         class="profile-avatar-img" />
                <?php else: ?>
                    <div class="profile-avatar-default">
                        <img src="/assets/icons/user.svg" alt="Avatar par défaut" />
                    </div>
                <?php endif; ?>
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

</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>