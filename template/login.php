<?php
$pageTitle = 'ReKey - Connexion';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 0; left: 0;"></div>
<div class="ambient-glow glow-rose" style="bottom: 0; right: 0;"></div>

<section class="auth-page">
    <div class="auth-card">
        <h2>Bon retour !</h2>
        <p class="auth-subtitle">Connecte-toi pour accéder à tes clés et au marché.</p>
 
        <form action="/Auth/processLogin" method="POST" class="auth-form">

            <div class="form-group">
                <label for="login" class="form-label">Email ou Pseudo</label>
                <input 
                    type="text" 
                    id="login" 
                    name="login" 
                    class="form-control"
                    placeholder="exemple@gmail.com ou Gamer123" 
                    required 
                />
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control"
                    placeholder="••••••••" 
                    required 
                />
            </div>

            <div class="form-options">
                <a href="#" class="forgot-pass">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn btn-neon">
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