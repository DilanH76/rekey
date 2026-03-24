<?php
$pageTitle = 'ReKey - Inscription';
ob_start();

// Récupération des anciennes saisies puis nettoyage
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<div class="ambient-glow glow-rose" style="top: 0; left: 0;"></div>
<div class="ambient-glow glow-cyan" style="bottom: 0; right: 0;"></div>

<section class="auth-page">
    <div class="auth-card register-card">
        <h2>Rejoins le réseau</h2>
        <p class="auth-subtitle">Crée ton compte pour acheter et vendre tes clés instantanément.</p>

        <form action="/Auth/processRegister" method="POST" class="auth-form">

            <div class="form-group">
                <label for="pseudo" class="form-label">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= htmlspecialchars($old['pseudo'] ?? '') ?>" placeholder="Votre pseudo" required />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="last_name" class="form-label">Nom</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" placeholder="Votre nom" required />
                </div>

                <div class="form-group">
                    <label for="first_name" class="form-label">Prénom</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" placeholder="Votre prénom" required />
                </div>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="exemple@gmail.com" required />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                            title="8 caractères minimum, avec au moins une majuscule, une minuscule, un chiffre et un caractère spécial."
                            placeholder="••••••••" required />
                        <button type="button" class="toggle-password" tabindex="-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm-password" class="form-label">Confirmer</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm-password" name="password_confirm" class="form-control" placeholder="••••••••" required />
                        <button type="button" class="toggle-password" tabindex="-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="cgu" name="cgu" required />
                <label for="cgu">
                    J'accepte les <a href="/Legal/cgv" target="_blank">conditions d'utilisation</a>
                </label>
            </div>

            <button type="submit" class="btn btn-neon">
                S'inscrire
            </button>
        </form>

        <div class="auth-footer">
            <p>Déjà un compte ? <a href="/Auth/login">Se connecter</a></p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>