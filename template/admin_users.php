<?php
$pageTitle = 'ReKey - Gestion des Utilisateurs';
$currentAdminTab = 'users';
ob_start();
?>

<div class="admin-header" style="margin-bottom: 2rem;">
    <h1 style="font-size: 2.2rem; text-transform: uppercase; margin-bottom: 0.5rem;">Gestion des Utilisateurs</h1>
    <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Consultez ou supprimez les membres de la plateforme.</p>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Pseudo</th>
                <th>Email</th>
                <th>Inscription</th>
                <th>Rôle</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td>
                        <img src="<?= $u->getAvatarBase64() ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);">
                    </td>
                    <td style="font-weight: bold;"><?= htmlspecialchars($u->getPseudo()) ?></td>
                    <td style="color: var(--text-muted);"><?= htmlspecialchars($u->getEmail()) ?></td>
                    <td style="color: var(--text-muted);"><?= $u->getCreatedAt()->format('d/m/Y') ?></td>
                    <td>
                        <?php if ($u->getIsAdmin()): ?>
                            <span class="badge-admin">Admin</span>
                        <?php else: ?>
                            <span class="badge-member">Membre</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php if ($u->getIdUser() !== $_SESSION['user_id']): ?>
                            <form action="/Admin/deleteUser/<?= $u->getIdUser() ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir bannir définitivement ce membre ?');" style="display:inline;">
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Bannir</button>
                            </form>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">(Vous)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
$adminContent = ob_get_clean(); 
require __DIR__ . '/admin_layout.php'; 
?>