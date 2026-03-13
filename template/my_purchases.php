<?php
$pageTitle = 'ReKey - Mes Achats';
ob_start();
?>

<section class="purchases-section container" style="margin-top: 3rem; margin-bottom: 3rem;">
    <h1 style="margin-bottom: 2rem;">Mes achats</h1>

    <?php if (empty($orders)): ?>
        <div class="empty-state" style="background: rgba(255,255,255,0.05); padding: 3rem; text-align: center; border-radius: 8px;">
            <p>Vous n'avez encore acheté aucun jeu.</p>
            <a href="/Home" class="btn-neon" style="margin-top: 1rem; display: inline-block;">Explorer le catalogue</a>
        </div>
    <?php else: ?>
        <div class="purchases-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
            <?php foreach ($orders as $order): ?>
                
                <article class="purchase-card" style="display: flex; gap: 1.5rem; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); align-items: center; flex-wrap: wrap;">
                    
                    <img src="<?= $order->getAd()->getCoverImageBase64() ?>" alt="Cover" style="width: 100px; height: 130px; object-fit: cover; border-radius: 4px;">
                    
                    <div class="purchase-info" style="flex: 1; min-width: 250px;">
                        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.4rem;"><?= htmlspecialchars($order->getAd()->getTitle()) ?></h2>
                        
                        <div style="display: flex; gap: 1rem; color: #aaa; font-size: 0.9rem; margin-bottom: 1rem;">
                            <span style="display: flex; align-items: center; gap: 0.4rem;">
                                <img src="<?= htmlspecialchars($order->getAd()->getPlatform()->getIconSvg()) ?>" alt="Platform" width="16" height="16">
                                <?= htmlspecialchars($order->getAd()->getPlatform()->getLabel()) ?>
                            </span>
                            <span>•</span>
                            <span>Acheté le <?= $order->getDateOrder()->format('d/m/Y') ?></span>
                            <span>•</span>
                            <span>Réf : <?= htmlspecialchars($order->getReference()) ?></span>
                        </div>
                    </div>

                    <div class="purchase-key" style="background: rgba(0,0,0,0.5); padding: 1.5rem; border-radius: 8px; border: 1px dashed #00ffcc; text-align: center; min-width: 280px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        <p style="margin: 0 0 0.5rem 0; color: #00ffcc; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Clé d'activation</p>
                        
                        <div class="key-container" id="key-container-<?= $order->getIdOrder() ?>">
                            
                            <button class="btn-outline" onclick="revealKey(<?= $order->getIdOrder() ?>)" style="margin: 0; padding: 0.5rem 1rem; font-size: 0.9rem;">
                                👁️ Révéler la clé
                            </button>
                            
                            <code id="key-value-<?= $order->getIdOrder() ?>" style="display: none; font-size: 1.2rem; font-family: monospace; font-weight: bold; user-select: all; background: #111; padding: 0.5rem 1rem; border-radius: 4px;">
                                <?= htmlspecialchars($order->getAd()->getGameKey()) ?>
                            </code>
                            
                        </div>
                    </div>

                </article>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
function revealKey(orderId) {
    // On trouve le conteneur spécifique à cette commande
    const container = document.getElementById('key-container-' + orderId);
    
    // On cache le bouton (qui est le premier enfant)
    container.querySelector('button').style.display = 'none';
    
    // On affiche la balise code (qui est le deuxième enfant)
    document.getElementById('key-value-' + orderId).style.display = 'block';
}
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>