<?php
$pageTitle = 'ReKey - Mentions Légales';
ob_start();
?>

<div class="settings-page">
    <a href="/Home" class="back-link">
        <span class="text-cyan">←</span> Retour
    </a>

    <div class="settings-header" style="text-align: center;">
        <h1 style="background: linear-gradient(to right, #ffffff, var(--cyan)); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Mentions Légales</h1>
        <p style="color: var(--text-muted); margin-top: 1rem;">Conformément aux dispositions de la loi pour la confiance dans l'économie numérique (LCEN).</p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">1.</span> Éditeur du site</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            <strong>Raison sociale :</strong> ReKey SAS (Société Fictive)<br>
            <strong>Capital social :</strong> 10 000 €<br>
            <strong>Siège social :</strong> 123 Avenue du Code, 75000 Paris, France<br>
            <strong>SIRET :</strong> 123 456 789 00012 (Fictif)<br>
            <strong>Contact :</strong> contact@rekey-app.fr
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">2.</span> Directeur de la publication</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Le Directeur de la publication est <strong>l'équipe ReKey</strong>, dans le cadre d'un projet de fin d'études pour le Titre RNCP Développeur Web et Web Mobile.
        </p>
    </div>

    <div class="settings-card">
        <h2><span class="text-cyan">3.</span> Hébergement</h2>
        <p style="color: var(--text-muted); line-height: 1.8;">
            Ce site est actuellement hébergé en environnement de développement local.<br>
            <strong>Hébergeur (Fictif pour la production) :</strong> CodeHost Cloud Services<br>
            <strong>Adresse :</strong> 404 Rue des Serveurs, 69000 Lyon, France
        </p>
    </div>

    <div class="settings-card danger-zone">
        <h2>Avertissement Légal</h2>
        <p class="danger-text">
            Ce site a été réalisé à des fins strictement pédagogiques. Les entreprises, numéros d'immatriculation et informations légales mentionnés sur cette page sont fictifs. Aucune activité commerciale n'est réellement exercée.
        </p>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>