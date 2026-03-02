<?php
$pageTitle = 'ReKey - Connexion';

ob_start();
?>

<section class="auth-container">
    <div class="auth-card">
        <h2>Bon retour parmi nous !</h2>
        <p class="auth-subtitle">Connecte-toi pour découvrir de nouvelles offres.</p>
        <?php if (isset($errorMessage) && $errorMessage): ?>
            <div class="error-message">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>


        <form action="/Auth/processLogin" method="POST" class="auth-form">

            <div class="form-group">
                <label for="login">Email ou Pseudo</label>
                <input 
                    type="text" 
                    id="login" 
                    name="login" 
                    placeholder="exemple@gmail.com ou Gamer123" 
                    required 
                />
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required 
                />
            </div>

            <div class="form-options">
                <a href="#" class="forgot-pass">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-neon btn-full btn-submit">
                Se connecter
            </button>
        </form>

        <div class="auth-footer">
            <p>Pas encore de compte ? <a href="/Auth/register">S'inscrire</a></p>
        </div>

    </div>
</section>
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>