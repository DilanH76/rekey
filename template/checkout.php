<?php
$pageTitle = 'ReKey - Paiement sécurisé';
ob_start();
?>

<section class="checkout-section container" style="margin-top: 3rem; margin-bottom: 3rem;">
    <h1 style="text-align: center; margin-bottom: 2rem;">Finaliser votre commande</h1>

    <div class="checkout-grid" style="display: flex; gap: 2rem; flex-wrap: wrap; align-items: flex-start;">
        
        <div class="checkout-summary" style="flex: 1; min-width: 300px; background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
            <h2>Résumé de la commande</h2>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">
            
            <div class="summary-item" style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
                <img src="<?= $ad->getCoverImageBase64() ?>" alt="Cover" style="width: 80px; height: 100px; object-fit: cover; border-radius: 4px;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0;"><?= htmlspecialchars($ad->getTitle()) ?></h3>
                    <div style="display: flex; align-items: center; gap: 0.5rem; opacity: 0.8; font-size: 0.9rem;">
                        <img src="<?= htmlspecialchars($ad->getPlatform()->getIconSvg()) ?>" alt="Platform" width="16" height="16">
                        <?= htmlspecialchars($ad->getPlatform()->getLabel()) ?>
                    </div>
                </div>
            </div>

            <div class="summary-price" style="display: flex; justify-content: space-between; align-items: center; font-size: 1.2rem; font-weight: bold;">
                <span>Total à payer :</span>
                <span style="color: #00ffcc;"><?= number_format($ad->getPrice(), 2, ',', ' ') ?> €</span>
            </div>
        </div>

        <div class="checkout-payment" style="flex: 1.5; min-width: 300px; background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
            <h2>Paiement sécurisé</h2>
            <p style="color: #ffaa00; font-size: 0.9rem; margin-bottom: 1.5rem;">
                 <strong>Simulation :</strong> Ne saisissez pas de véritables coordonnées bancaires. Vous pouvez taper n'importe quels chiffres pour valider cette étape.
            </p>

            <form action="/Order/process/<?= $ad->getIdAds() ?>" method="POST">
                
                <div class="form-group">
                    <label for="card_name">Nom sur la carte</label>
                    <input type="text" id="card_name" placeholder="JEAN DUPONT" required>
                </div>

                <div class="form-group">
                    <label for="card_number">Numéro de carte</label>
                    <input type="text" id="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label for="card_exp">Date d'expiration</label>
                        <input type="text" id="card_exp" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="card_cvv">Cryptogramme (CVV)</label>
                        <input type="text" id="card_cvv" placeholder="123" maxlength="3" required>
                    </div>
                </div>

                <button type="submit" class="btn-neon" style="width: 100%; margin-top: 1.5rem; justify-content: center;">
                    Valider et payer <?= number_format($ad->getPrice(), 2, ',', ' ') ?> €
                </button>
            </form>
        </div>

    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>