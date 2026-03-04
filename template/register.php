<?php
// On définit le titre de l'onglet du navigateur
$pageTitle = 'ReKey - Inscription' ;
// On ouvre le "robinet" pour capturer tout le HTML qui suit :  
ob_start();
?>

<section class="auth-container">
    <div class="auth-card">
        <h2>Rejoins nous !</h2>
        <p class="auth-subtitle">Crée ton compte pour acheter et vendre.</p>


        <form action="/Auth/processRegister" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : '' ?>"  placeholder="Gamer123" required />
            </div>

            <div class="form-group">
                <label for="last_name">Nom</label>
                <input type="text" id="last_name" name="last_name"  value="<?= isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '' ?>"  placeholder="Dupont" required />
            </div>

            <div class="form-group">
                <label for="first_name">Prénom</label>
                <input type="text" id="first_name" name="first_name"  value="<?= isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '' ?>" placeholder="Jean" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"  value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" placeholder="exemple@gmail.com" required />
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}" 
                        title="8 caractères minimum, avec au moins une majuscule, une minuscule, un chiffre et un caractère spécial." 
                        placeholder="••••••••" 
                        required 
                />
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirmer le mot de passe</label>
                <input type="password" id="confirm-password" name="password_confirm" placeholder="••••••••" required />
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="cgu" name="cgu" required />
                <label for="cgu">
                    J'accepte les <a href="#">conditions d'utilisation</a>
                </label>
            </div>

            <button type="submit" class="btn-neon btn-full btn-submit">
                S'inscrire
            </button>
        </form>

        <div class="auth-footer">
            <p>Déjà un compte ? <a href="/Auth/login">Se connecter</a></p>
        </div>
    </div>
</section>

<?php 
// On ferme le robinet et on stocke tout le HTML ci-dessus dans la variable $content
$content = ob_get_clean(); 

// On appelle le grand gabarit qui va afficher le header, ce $content, et le footer
require __DIR__ . '/layout.php'; 
?>