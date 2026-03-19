<?php
$pageTitle = 'ReKey - Mes Achats';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 10%; left: -10%;"></div>
<div class="ambient-glow glow-rose" style="bottom: 10%; right: -10%;"></div>

<section class="purchases-page container">

    <a href="javascript:history.back()" class="back-link">
        <span class="text-cyan">←</span> Retour
    </a>

    <div class="purchases-header">
        <h1>Mes Achats</h1>
        <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Retrouvez ici toutes vos clés d'activation achetées sur ReKey.</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-state" style="background: rgba(255,255,255,0.05); padding: 4rem; text-align: center; border-radius: var(--radius-md); border: 1px solid var(--border-light); backdrop-filter: var(--glass-blur);">
            <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 2rem;">Vous n'avez encore acheté aucun jeu.</p>
            <a href="/Home#annonces" class="btn btn-neon">Explorer le catalogue</a>
        </div>
    <?php else: ?>
        <div class="purchases-list">
            <?php foreach ($orders as $order): ?>
                
                <article class="purchase-card">
                    
                    <img src="<?= $order->getAd()->getCoverImageBase64() ?>" alt="Cover de <?= htmlspecialchars($order->getAd()->getTitle()) ?>" class="purchase-cover">
                    
                    <div class="purchase-info">
                        <h2><?= htmlspecialchars($order->getAd()->getTitle()) ?></h2>
                        
                        <div class="purchase-meta">
                            <span class="purchase-platform">
                                <img src="<?= htmlspecialchars($order->getAd()->getPlatform()->getIconSvg()) ?>" alt="Platform">
                                <?= htmlspecialchars($order->getAd()->getPlatform()->getLabel()) ?>
                            </span>
                            <span class="bullet">•</span>
                            <span>Acheté le <?= $order->getDateOrder()->format('d/m/Y') ?></span>
                            <span class="bullet">•</span>
                            <span>Réf : <?= htmlspecialchars($order->getReference()) ?></span>
                        </div>
                    </div>

                    <div class="purchase-key-box">
                        <p class="key-label">Clé d'activation</p>
                        
                        <div class="key-container" id="key-container-<?= $order->getIdOrder() ?>">
                            
                            <button class="btn btn-outline btn-reveal" onclick="revealKey(<?= $order->getIdOrder() ?>)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Révéler la clé
                            </button>
                            
                            <div id="key-value-<?= $order->getIdOrder() ?>" style="display: none;">
                                <div class="key-value-code">
                                    <?= htmlspecialchars($order->getAd()->getGameKey()) ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </article>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
function revealKey(orderId) {
    const container = document.getElementById('key-container-' + orderId);
    // Cache le bouton
    container.querySelector('button').style.display = 'none';
    // Affiche la clé avec son animation CSS
    document.getElementById('key-value-' + orderId).style.display = 'block';
}
</script>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>