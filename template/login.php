<?php
$pageTitle = 'ReKey - Connexion';
ob_start();

// AJOUT PRG : Récupération du login puis nettoyage
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
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
                    value="<?= htmlspecialchars($old['login'] ?? '') ?>"
                    placeholder="exemple@gmail.com ou Gamer123"
                    required 
                />
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="••••••••"
                        required 
                    />
                    <button type="button" class="toggle-password" aria-label="Afficher/Masquer le mot de passe">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
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