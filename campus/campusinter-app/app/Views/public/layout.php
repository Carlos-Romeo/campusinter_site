<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \CampusInter\Helpers\Csrf::generate() ?>">
    <title><?= htmlspecialchars($title ?? 'Campus Inter') ?> — Campus Inter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="ci-app">
        <!-- Header -->
        <header class="ci-header">
            <div class="ci-header__inner">
                <a href="/" class="ci-header__logo">
                    <span class="ci-header__logo-icon">CI</span>
                    <span>Campus Inter</span>
                </a>
                <nav class="ci-header__nav">
                    <a href="/" class="ci-header__link ci-header__link--active">Formations</a>
                    <a href="/admin" class="ci-header__link">Administration</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <?= $content ?>
        </main>

        <!-- Footer -->
        <footer class="ci-footer">
            <div class="ci-container">
                <p>&copy; <?= date('Y') ?> Campus Inter. Tous droits réservés.</p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/api.js"></script>
    <script src="/assets/js/app.js"></script>
    <?= $scripts ?? '' ?>
</body>
</html>
