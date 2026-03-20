<?php
$pageTitle = 'ReKey - Politique de Confidentialité';
ob_start();
?>


<div class="settings-page">
    <a href="/Home" class="back-link">
        <span class="text-cyan">←</span> Retour
    </a>

    <div class="settings-header" style="text-align: center;">
        <h1 style="background: linear-gradient(to right, #ffffff, var(--cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Politique de Confidentialité (RGPD)</h1>
        <p style="color: var(--text-muted); margin-top: 1rem;">La protection de vos données est notre priorité.</p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">1.</span> Données collectées</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Lors de votre inscription sur ReKey, nous collectons les informations suivantes : Nom, Prénom, Pseudo, Adresse email, et Mot de passe (haché de manière sécurisée). Nous pouvons également stocker l'image que vous choisissez d'utiliser comme avatar.
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">2.</span> Utilisation des données</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Vos données sont utilisées exclusivement pour :<br>
            - La gestion de votre compte et de votre authentification.<br>
            - La sécurisation des transactions de clés entre utilisateurs.<br>
            - L'affichage de votre profil (Pseudo et Avatar) sur vos annonces publiques.
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">3.</span> Vos droits (Loi Informatique et Libertés)</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification et de suppression de vos données. 
            <br><br>
            <strong>Comment l'exercer ?</strong> Vous pouvez modifier vos informations à tout moment via l'onglet "Mon Profil", ou supprimer définitivement votre compte et toutes les données associées en utilisant le bouton "Supprimer mon compte" situé dans cette même section.
        </p>
    </div>

    <div class="settings-card danger-zone">
        <h2>Durée de conservation</h2>
        <p class="danger-text">
            Ce site étant un projet éducatif à but non lucratif, toutes les données stockées dans notre base de données seront intégralement détruites à l'issue de la période d'évaluation par le jury de la formation DWWM.
        </p>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>