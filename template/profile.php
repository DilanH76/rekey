<?php
$pageTitle = 'ReKey - Terminal de Bord';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 10%; left: -5%;"></div>
<div class="ambient-glow glow-rose" style="bottom: 10%; right: -5%;"></div>

<section class="dashboard-page container">

    <a href="javascript:history.back()" class="back-link">
        <span class="text-cyan">←</span> Retour
    </a>

    <div class="dashboard-header">
        <h1>Mon Profil</h1>
        <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Bienvenue dans votre espace personnel, <?= htmlspecialchars($user->getPseudo()) ?>.</p>
    </div>

    <div class="identity-card">
        <img src="<?= $user->getAvatarBase64() ?>" alt="Avatar" class="identity-avatar">
        
        <div class="identity-info">
            <h2><?= htmlspecialchars($user->getPseudo()) ?></h2>
            <p>Nom : <span style="color: var(--text-main);"><?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?></span></p>
            <p>Email : <span class="highlight"><?= htmlspecialchars($user->getEmail()) ?></span></p>
            <p>Membre depuis le <?= $user->getCreatedAt()->format('d/m/Y') ?></p>
        </div>

        <div class="identity-actions">
            <a href="/Profile/edit" class="btn btn-outline">Paramètres du compte</a>
        </div>
    </div>

    <div class="dashboard-actions-grid">
        
        <a href="/Order/myPurchases" class="action-card">
            <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                </svg>
            </div>
            <div>
                <h3>Mes Achats</h3>
                <p>Récupérez vos clés d'activation</p>
            </div>
        </a>

        <a href="/Ad/mine" class="action-card rose">
            <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </div>
            <div>
                <h3>Mes Annonces</h3>
                <p>Gérez vos jeux en vente</p>
            </div>
        </a>

        <a href="/Ad/add" class="action-card">
            <div class="action-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </div>
            <div>
                <h3>Vendre un jeu</h3>
                <p>Mettez une nouvelle clé en vente</p>
            </div>
        </a>

        <?php if ($user->getIsAdmin()): ?>
        <a href="/Admin/dashboard" class="action-card" style="border-color: rgba(255, 170, 0, 0.3);">
            <div class="action-icon" style="color: #ffaa00; border-color: #ffaa00; background: rgba(255, 170, 0, 0.1);">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>
            <div>
                <h3 style="color: #ffaa00;">Administration</h3>
                <p>Accéder au panneau de contrôle</p>
            </div>
        </a>
        <?php endif; ?>

    </div>

</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>