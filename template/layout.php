<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/svg+xml" href="/assets/ico/Rekey2.webp" />
    
    <title><?= $pageTitle ?? 'ReKey' ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'ReKey est la plateforme nouvelle génération pour acheter et vendre vos clés de jeux vidéo (PC, PlayStation, Xbox, Nintendo) au meilleur prix.') ?>" />

    <?php 
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $canonicalUrl = "https://www.rekey.fr" . $currentPath;
    ?>
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>" />

    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= $pageTitle ?? 'ReKey' ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($pageDesc ?? 'ReKey est la plateforme nouvelle génération pour acheter et vendre vos clés de jeux vidéo au meilleur prix.') ?>" />
    <meta property="og:url" content="https://www.rekey.fr<?= $_SERVER['REQUEST_URI'] ?? '' ?>" />
    <meta property="og:image" content="<?= $pageImage ?? 'https://www.rekey.fr/assets/ico/Rekey2.webp' ?>" />
</head>
<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-message flash-<?= $_SESSION['flash']['type'] ?>">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <main>
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="/assets/js/script.js"></script>
</body>
</html>