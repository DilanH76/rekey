<?php
$pageTitle = 'ReKey - Conditions Générales de Vente';
ob_start();
?>

<div class="settings-page">
    <div class="settings-header" style="text-align: center;">
        <h1 style="background: linear-gradient(to right, #ffffff, var(--cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Conditions Générales de Vente & d'Utilisation</h1>
        <p style="color: var(--text-muted); margin-top: 1rem;">Dernière mise à jour : Février 2026</p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">1.</span> Objet du service</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            ReKey est une plateforme de mise en relation entre joueurs permettant l'achat et la revente de clés d'activation de jeux vidéo (CD-Keys) dématérialisées. ReKey agit en tant qu'intermédiaire et hébergeur des annonces publiées par ses utilisateurs.
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">2.</span> Vente de produits dématérialisés</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Conformément aux dispositions légales relatives à la fourniture d'un contenu numérique non fourni sur un support matériel, <strong>le droit de rétractation ne peut être exercé</strong> une fois que la clé d'activation a été révélée par l'acheteur dans son espace "Mes Achats". En cliquant sur "Révéler la clé", l'utilisateur renonce expressément à son droit de rétractation.
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">3.</span> Responsabilité du vendeur</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Le vendeur garantit que la clé d'activation qu'il met en vente a été acquise légalement et correspond exactement à la description (plateforme, région) de son annonce. En cas de clé frauduleuse ou déjà utilisée, le compte du vendeur sera banni et la transaction annulée.
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">4.</span> Projet Étudiant</h2>
        <p class="alert-warning" style="margin-top: 1rem;">
            <strong>Avertissement :</strong> Ce site web est un projet réalisé dans le cadre d'une formation diplômante (Titre RNCP DWWM). Il ne s'agit pas d'un véritable site e-commerce. Aucun paiement réel n'est traité et aucune véritable clé de jeu n'est vendue.
        </p>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>