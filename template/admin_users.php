<?php
$pageTitle = 'ReKey - Gestion des Utilisateurs';
$currentAdminTab = 'users'; // On allume l'onglet "Gestion des Utilisateurs"
ob_start();
?>

<h1 style="margin-top: 0;">Gestion des Utilisateurs</h1>
<p style="color: #aaa; margin-bottom: 2rem;">Consultez ou supprimez les membres de la plateforme.</p>

<div style="background: rgba(255,255,255,0.05); border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.02);">
                <th style="padding: 1rem;">Avatar</th>
                <th style="padding: 1rem;">Pseudo</th>
                <th style="padding: 1rem;">Email</th>
                <th style="padding: 1rem;">Inscription</th>
                <th style="padding: 1rem;">Rôle</th>
                <th style="padding: 1rem; text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 1rem;">
                        <img src="<?= $u->getAvatarBase64() ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    </td>
                    <td style="padding: 1rem; font-weight: bold;"><?= htmlspecialchars($u->getPseudo()) ?></td>
                    <td style="padding: 1rem; color: #aaa;"><?= htmlspecialchars($u->getEmail()) ?></td>
                    <td style="padding: 1rem; color: #aaa;"><?= $u->getCreatedAt()->format('d/m/Y') ?></td>
                    <td style="padding: 1rem;">
                        <?php if ($u->getIsAdmin()): ?>
                            <span style="background: rgba(0, 255, 204, 0.2); color: #00ffcc; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Admin</span>
                        <?php else: ?>
                            <span style="background: rgba(255, 255, 255, 0.1); color: #ccc; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">Membre</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <?php if ($u->getIdUser() !== $_SESSION['user_id']): ?>
                            <form action="/Admin/deleteUser/<?= $u->getIdUser() ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir bannir définitivement ce membre ?');">
                                <button type="submit" style="background: rgba(255, 0, 85, 0.2); color: #ff0055; border: 1px solid #ff0055; padding: 0.4rem 0.8rem; border-radius: 4px; cursor: pointer; transition: 0.3s;">Bannir</button>
                            </form>
                        <?php else: ?>
                            <span style="color: #666; font-size: 0.9rem;">C'est vous</span>
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