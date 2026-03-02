<div id="auth-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    
    <div style="background: var(--bg-dark); border: 2px solid <?= $success ? '#28a745' : '#ff6b6b' ?>; border-radius: 1.5rem; padding: 2.5rem; text-align: center; max-width: 400px; width: 90%; box-shadow: 0 10px 30px <?= $success ? 'rgba(40,167,69,0.3)' : 'rgba(255,107,107,0.3)' ?>;">
        
        <h3 style="color: <?= $success ? '#28a745' : '#ff6b6b' ?>; margin-bottom: 1rem; font-size: 1.8rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <?php if($success): ?>
                ✅ Succès
            <?php else: ?>
                ⚠️ Oups !
            <?php endif; ?>
        </h3>
        
        <p style="color: white; margin-bottom: 2rem; font-size: 1.1rem; line-height: 1.5;">
            <?= htmlspecialchars($message) ?>
        </p>
        
        <?php if($success): ?>
            <a href="<?= htmlspecialchars($url) ?>" style="display: inline-block; background-color: #28a745; color: white; padding: 0.75rem 2rem; border-radius: 3rem; font-weight: 600; text-transform: uppercase; text-decoration: none; transition: transform 0.3s ease;">
                Continuer
            </a>
        <?php else: ?>
            <button onclick="document.getElementById('auth-modal').style.display='none'" style="cursor: pointer; display: inline-block; border: none; background-color: #ff6b6b; color: white; padding: 0.75rem 2rem; border-radius: 3rem; font-weight: 600; text-transform: uppercase; transition: transform 0.3s ease;">
                Réessayer
            </button>
        <?php endif; ?>

    </div>
</div>

<!-- TODO: Basculer le CSS dans style.css -->