<?php
$pageTitle = 'ReKey - Vendre un jeu';
ob_start();
?>

<div class="ambient-glow glow-cyan" style="top: 0; left: -10%;"></div>
<div class="ambient-glow glow-rose" style="bottom: 0; right: -10%;"></div>

<section class="ad-form-page">
    <div class="ad-form-card">
        
        <div class="ad-form-header">
            <h1>Vendre un jeu</h1>
            <p class="auth-subtitle" style="text-align: left; margin-bottom: 0;">Renseigne les informations de ta clé pour la mettre en vente sur le réseau.</p>
        </div>

        <form action="/Ad/store" method="POST" enctype="multipart/form-data" class="ad-form">
            
            <div class="form-group">
                <label for="title" class="form-label">Titre du jeu <span class="text-cyan">*</span></label>
                <input type="text" id="title" name="title" class="form-control" required placeholder="Ex: Cyberpunk 2077">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_category" class="form-label">Catégorie <span class="text-cyan">*</span></label>
                    <select id="id_category" name="id_category" class="form-control" required>
                        <option value="">-- Choisir une catégorie --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getIdCategory() ?>">
                                <?= htmlspecialchars($cat->getLabel()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_platform" class="form-label">Plateforme <span class="text-cyan">*</span></label>
                    <select id="id_platform" name="id_platform" class="form-control" required>
                        <option value="">-- Choisir une plateforme --</option>
                        <?php foreach ($platforms as $plat): ?>
                            <option value="<?= $plat->getIdPlatform() ?>">
                                <?= htmlspecialchars($plat->getLabel()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description courte</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="État de la clé, langue du jeu, édition spéciale..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price" class="form-label">Prix (€) <span class="text-cyan">*</span></label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required placeholder="Ex: 19.99">
                </div>

                <div class="form-group">
                    <label for="game_key" class="form-label">Clé du jeu (CD Key) <span class="text-cyan">*</span></label>
                    <input type="text" id="game_key" name="game_key" class="form-control" required placeholder="XXXXX-XXXXX-XXXXX">
                    <small class="form-help">La clé sera cryptée et révélée uniquement à l'acheteur.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="cover_image" class="form-label">Image de couverture (Optionnel)</label>
                <input type="file" id="cover_image" name="cover_image" class="form-control file-input" accept=".jpg, .jpeg, .png, .webp">
                <small class="form-help">Format JPG, PNG ou WEBP. Max 2 Mo.</small>
            </div>

            <hr class="form-divider">

            <div class="form-actions">
                <button type="submit" class="btn btn-neon w-100" style="padding: 1.2rem; font-size: 1.1rem;">
                    Mettre en vente
                </button>
            </div>

        </form>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>