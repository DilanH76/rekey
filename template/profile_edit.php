<?php
$pageTitle= 'ReKey - Modifier mon Profil';
ob_start();
?>

<section class="profile-container">
    
    <div class="profile-card">
        
        <h1 class="profile-title">Modifier mes informations</h1>

        <form action="/Profile/update" method="POST" class="profile-form">
            
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($user->getPseudo()) ?>" required />
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="last_name">Nom</label>
                    <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user->getLastName()) ?>" required />
                </div>
                
                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user->getFirstName()) ?>" required />
                </div>
            </div>

            <div class="profile-actions-row">
                <a href="/Profile" class="btn-outline">Annuler</a>
                <button type="submit" class="btn-neon">Sauvegarder</button>
            </div>
        </form>

    </div>

</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>