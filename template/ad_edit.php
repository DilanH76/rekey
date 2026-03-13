<?php
$pageTitle = 'Modifier mon annonce';
ob_start();
?>

<div class="form-container">
    <h2>Modifier l'annonce</h2>

    <form action="/Ad/update/<?= $ad->getIdAds() ?>" method="POST" class="neon-form">

        <div class="form-group">
            <label>Titre du jeu *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($ad->getTitle()) ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5"><?= htmlspecialchars($ad->getDescription()) ?></textarea>
        </div>

        <div class="form-group">
            <label>Prix (€) *</label>
            <input type="number" name="price" step="0.01" min="0" value="<?= $ad->getPrice() ?>" required>
        </div>

        <div class="form-group">
            <label>Catégorie *</label>
            <select name="id_category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->getIdCategory() ?>" <?= ($cat->getIdCategory() === $ad->getCategory()->getIdCategory()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->getLabel()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Plateforme *</label>
            <select name="id_platform" required>
                <?php foreach ($platforms as $plat): ?>
                    <option value="<?= $plat->getIdPlatform() ?>" <?= ($plat->getIdPlatform() === $ad->getPlatform()->getIdPlatform()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($plat->getLabel()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-neon mt-3">Enregistrer les modifications</button>
        <a href="/Ad/show/<?= $ad->getIdAds() ?>" style="color:#aaa; margin-left:1rem; text-decoration:none;">Annuler</a>


    </form>

</div>
<!-- TODO : Nettoyer CSS en ligne -->
<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>