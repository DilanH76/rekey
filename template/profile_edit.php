<?php
$pageTitle = 'ReKey - Modifier mon Profil';
ob_start();
?>

<section class="profile-container">

    <div class="profile-card">

        <h1 class="profile-title">Modifier mes informations</h1>

        <form action="/Profile/update" method="POST" enctype="multipart/form-data" class="profile-form">

            <div class="form-group">
                <label for="avatar">Photo de profil (Optionnel)</label>
                <div class="file-upload-wrapper avatar-upload-wrapper">
                    <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/webp" class="file-input avatar-file-input" />
                </div>
                <small class="form-help-text">Formats acceptés : JPG, PNG, WEBP. Taille max : 2Mo.</small>
            </div>

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

        <hr class="profile-divider security-divider">

        <h2 class="profile-title title-security">Sécurité</h2>

        <form action="/Profile/updatePassword" method="POST" class="profile-form">

            <div class="form-group">
                <label for="old_password" class="label-danger">Mot de passe actuel</label>
                <input type="password" id="old_password" name="old_password" required />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="new_password" class="label-danger">Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" required />
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="label-danger">Confirmer le nouveau</label>
                    <input type="password" id="confirm_password" name="confirm_password" required />
                </div>
            </div>

            <div class="profile-actions-row">
                <button type="submit" class="btn-neon btn-danger">
                    Changer le mot de passe
                </button>
            </div>
        </form>

        <hr class="profile-divider security-divider">

        <h2 class="profile-title title-delete">SUPPRESSION</h2>

        <div class="delete-warning-box">
            <p class="delete-warning-text">
                Attention, la suppression de votre compte est définitive et irréversible. Toutes vos données seront effacées.
            </p>
            <form action="/Profile/deleteAccount" method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ReKey ? Cette action est définitive.');">
                <button type="submit" class="btn-neon btn-delete">
                    Supprimer définitivement mon compte
                </button>
            </form>
        </div>
    </div>

</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>