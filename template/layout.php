<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&display=swap" rel="stylesheet" />
    
    <title><?= $pageTitle ?? 'ReKey' ?></title>
</head>
<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <main>
        <?= $content ?>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="/script/script.js"></script>
</body>
</html>