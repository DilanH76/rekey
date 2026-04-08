<?php
$pageTitle = 'ReKey - Paramètres du compte';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 10%; left: 0;"></div>
<div class="ambient-glow glow-rose" style="bottom: 10%; right: 0;"></div>

<section class="settings-page container">

    <a href="/Profile" class="back-link">
        <span class="text-cyan">←</span> Retour
    </a>

    <div class="settings-header">
        <h1>Paramètres du compte</h1>
        <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Gérez vos informations personnelles et la sécurité de votre compte.</p>
    </div>

    <div class="settings-card">
        <h2>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Informations Générales
        </h2>

        <form action="/Profile/update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="form-group">
                <label for="avatar" class="form-label">Photo de profil (Optionnel)</label>
                <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/webp" class="form-control file-input" />
                <small class="form-help">Formats acceptés : JPG, PNG, WEBP. Taille max : 2Mo.</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="pseudo" class="form-label">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= htmlspecialchars($user->getPseudo()) ?>" required />
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user->getEmail()) ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">Prénom</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?= htmlspecialchars($user->getFirstName()) ?>" required />
                </div>
                <div class="form-group">
                    <label for="last_name" class="form-label">Nom</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?= htmlspecialchars($user->getLastName()) ?>" required />
                </div>
            </div>

            <div class="form-actions-right">
                <button type="submit" class="btn btn-neon">Sauvegarder les modifications</button>
            </div>
        </form>
    </div>

    <div class="settings-card">
        <h2>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            Sécurité
        </h2>

        <form action="/Profile/updatePassword" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="form-group">
                <label for="old_password" class="form-label">Mot de passe actuel</label>
                <input type="password" id="old_password" name="old_password" class="form-control" required />
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required />
                </div>
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmer le nouveau</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required />
                </div>
            </div>

            <div class="form-actions-right">
                <button type="submit" class="btn btn-outline">Mettre à jour le mot de passe</button>
            </div>
        </form>
    </div>

    <div class="settings-card danger-zone">
        <h2>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            Supprimer mon compte
        </h2>
        
        <p class="danger-text">
            Attention, la suppression de votre compte est <strong>définitive et irréversible</strong>. Toutes vos données, y compris vos annonces en cours et votre historique d'achats, seront effacées de nos serveurs de manière permanente.
        </p>

        <form action="/Profile/deleteAccount" method="POST" onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ReKey ? Cette action est définitive.');">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-danger">
                Supprimer définitivement mon compte
            </button>
        </form>
    </div>

</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>