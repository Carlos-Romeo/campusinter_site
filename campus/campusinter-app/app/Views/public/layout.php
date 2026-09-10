<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \CampusInter\Helpers\Csrf::generate() ?>">
    <title><?= htmlspecialchars($title ?? 'Campus Inter') ?> — Campus Inter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="ci-app">
        <!-- Header -->
        <header class="ci-header">
            <div class="ci-header__inner">
                <a href="/" class="ci-header__logo">
                    <img src="/assets/img/logo-campus-inter.png" alt="Campus Inter" height="32" style="width:auto;object-fit:contain;display:block;">
                </a>
                <button id="ci-menu-toggle" class="ci-header__menu-toggle" aria-label="Menu" aria-expanded="false">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <nav id="ci-header-nav" class="ci-header__nav">
                    <a href="https://it.dyk08.com/campusinter/index.php/fr" target="_blank" class="ci-header__link">Site</a>
                    <a href="/" class="ci-header__link ci-header__link--active">Formations</a>
                    <a href="/admin" class="ci-header__link">Administration</a>
                </nav>
            </div>
            <div id="ci-header-overlay" class="ci-header__overlay"></div>
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
