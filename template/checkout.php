<?php
$pageTitle = 'ReKey - Paiement sécurisé';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: -5%; left: -5%;"></div>
<div class="ambient-glow glow-rose" style="bottom: -5%; right: -5%;"></div>

<section class="checkout-page container">

    <a href="javascript:history.back()" class="back-link">
        <span class="text-cyan">←</span> Retour à l'annonce
    </a>

    <div class="checkout-header">
        <h1>Validation de commande</h1>
        <p class="auth-subtitle">Finalisez votre achat pour débloquer votre clé d'activation.</p>
    </div>

    <div class="checkout-grid">
        
        <aside class="checkout-card">
            <h2 style="font-size: 1.1rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem;">Résumé de l'achat</h2>
            
            <div class="checkout-summary-item">
                <img src="<?= $ad->getCoverImageBase64() ?>" alt="Cover de <?= htmlspecialchars($ad->getTitle()) ?>" class="checkout-cover">
                <div class="checkout-details">
                    <h3><?= htmlspecialchars($ad->getTitle()) ?></h3>
                    
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.8rem;">
                        <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="<?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>" style="width: 20px; filter: brightness(0) invert(0.8);">
                        <span style="color: var(--text-muted); font-size: 0.9rem;"><?= htmlspecialchars($ad->getPlatform()->getLabel()) ?></span>
                    </div>
                    
                    <span class="badge-tech" style="padding: 0.2rem 0.6rem; font-size: 0.75rem; border: none; background: rgba(255,255,255,0.05);"><?= htmlspecialchars($ad->getCategory()->getLabel()) ?></span>
                </div>
            </div>

            <div class="checkout-total">
                <span style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total à payer</span>
                <span class="price"><?= number_format($ad->getPrice(), 2, ',', ' ') ?> €</span>
            </div>
        </aside>

        <div class="checkout-card">
            <h2 style="font-size: 1.5rem; text-transform: uppercase; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.8rem;">
                <span class="text-cyan">/</span> Paiement sécurisé
            </h2>
            
            <div class="alert-warning">
                <strong>MODE SIMULATION :</strong> Ceci est un environnement de test. Ne saisissez <u>aucune</u> véritable coordonnée bancaire. Des données factices suffisent pour valider la transaction.
            </div>

            <form action="/Order/process/<?= $ad->getIdAds() ?>" method="POST" class="ad-form">
                
                <div class="form-group">
                    <label for="card_name" class="form-label">Nom sur la carte</label>
                    <input type="text" id="card_name" class="form-control" placeholder="JEAN DUPONT" required>
                </div>

                <div class="form-group">
                    <label for="card_number" class="form-label">Numéro de carte</label>
                    <input type="text" id="card_number" class="form-control" placeholder="0000 0000 0000 0000" maxlength="19" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="card_exp" class="form-label">Date d'expiration</label>
                        <input type="text" id="card_exp" class="form-control" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    <div class="form-group">
                        <label for="card_cvv" class="form-label">Cryptogramme (CVV)</label>
                        <input type="text" id="card_cvv" class="form-control" placeholder="123" maxlength="3" required>
                    </div>
                </div>

                <hr class="form-divider" style="margin: 2rem 0;">

                <button type="submit" class="btn btn-neon w-100" style="padding: 1.2rem; font-size: 1.1rem;">
                    Payer <?= number_format($ad->getPrice(), 2, ',', ' ') ?> € et révéler la clé
                </button>
                <p style="text-align: center; margin-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">
                    Transaction chiffrée de bout en bout.
                </p>
            </form>
        </div>

    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>