<?php
$pageTitle = 'ReKey - Modifier mon annonce';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 0; left: -10%;"></div>
<div class="ambient-glow glow-rose" style="bottom: 0; right: -10%;"></div>

<section class="ad-form-page">
    <div class="ad-form-card">
        
        <div class="ad-form-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1>Modifier l'annonce</h1>
                <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Mettez à jour les informations de votre clé.</p>
            </div>
            
            <form action="/Ad/delete/<?= $ad->getIdAds() ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ? Cette action est définitive et irréversible.');">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" class="btn btn-danger">
                    Supprimer l'annonce
                </button>
            </form>
        </div>

        <form action="/Ad/update/<?= $ad->getIdAds() ?>" method="POST" class="ad-form">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label for="title" class="form-label">Titre du jeu <span class="text-cyan">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($ad->getTitle()) ?>" required>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="price" class="form-label">Prix (€) <span class="text-cyan">*</span></label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?= $ad->getPrice() ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_category" class="form-label">Catégorie <span class="text-cyan">*</span></label>
                    <select id="id_category" name="id_category" class="form-control" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getIdCategory() ?>" <?= ($cat->getIdCategory() === $ad->getCategory()->getIdCategory()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat->getLabel()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_platform" class="form-label">Plateforme <span class="text-cyan">*</span></label>
                    <select id="id_platform" name="id_platform" class="form-control" required>
                        <?php foreach ($platforms as $plat): ?>
                            <option value="<?= $plat->getIdPlatform() ?>" <?= ($plat->getIdPlatform() === $ad->getPlatform()->getIdPlatform()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($plat->getLabel()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description courte</label>
                <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($ad->getDescription()) ?></textarea>
            </div>

            <hr class="form-divider">

            <div class="form-actions" style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-neon" style="flex: 1; padding: 1.2rem; font-size: 1.1rem;">
                    Enregistrer les modifications
                </button>
                <a href="/Ad/show/<?= $ad->getIdAds() ?>" class="btn btn-outline" style="padding: 1.2rem; font-size: 1.1rem;">
                    Annuler
                </a>
            </div>

        </form>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>