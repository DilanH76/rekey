<?php
$pageTitle = 'ReKey - Vendre un jeu';
ob_start();
?>

<section class="form-container">
    <div class="form-card">
        
        <h1 class="form-title">Vendre un jeu</h1>
        <p class="form-subtitle">Remplissez les informations ci-dessous pour publier votre annonce.</p>

        <form action="/Ad/store" method="POST" enctype="multipart/form-data" class="custom-form">
            
            <div class="form-group">
                <label for="title">Titre du jeu *</label>
                <input type="text" id="title" name="title" required placeholder="Ex: Cyberpunk 2077">
            </div>

            <div class="form-row">
                <div class="form-group half-width">
                    <label for="id_category">Catégorie *</label>
                    <select id="id_category" name="id_category" required>
                        <option value="">-- Choisir une catégorie --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->getIdCategory() ?>">
                                <?= htmlspecialchars($cat->getLabel()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group half-width">
                    <label for="id_platform">Plateforme *</label>
                    <select id="id_platform" name="id_platform" required>
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
                <label for="description">Description courte</label>
                <textarea id="description" name="description" rows="4" placeholder="État de la clé, langue du jeu, édition spéciale..."></textarea>
            </div>

            <div class="form-row">
                <div class="form-group half-width">
                    <label for="price">Prix (€) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required placeholder="Ex: 19.99">
                </div>

                <div class="form-group half-width">
                    <label for="game_key">Clé du jeu (CD Key) *</label>
                    <input type="text" id="game_key" name="game_key" required placeholder="XXXXX-XXXXX-XXXXX">
                    <small class="form-help">La clé sera cachée et ne sera révélée qu'à l'acheteur.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="cover_image">Image de couverture (Optionnel)</label>
                <input type="file" id="cover_image" name="cover_image" accept=".jpg, .jpeg, .png, .webp">
                <small class="form-help">Format JPG, PNG ou WEBP. Max 2 Mo.</small>
            </div>

            <hr class="form-divider">

            <div class="form-actions">
                <button type="submit" class="btn-neon">Mettre en vente</button>
            </div>

        </form>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
require __DIR__ . '/layout.php'; 
?>