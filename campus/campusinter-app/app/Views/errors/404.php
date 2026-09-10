<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée — Campus Inter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="ci-app">
        <header class="ci-header">
            <div class="ci-header__inner">
                <a href="/" class="ci-header__logo">
                    <span class="ci-header__logo-icon">CI</span>
                    <span>Campus Inter</span>
                </a>
            </div>
        </header>

        <main>
            <section class="ci-section">
                <div class="ci-container">
                    <div class="ci-confirmation">
                        <div class="ci-confirmation__icon" style="background: var(--ci-warning-bg); color: var(--ci-warning);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h1 class="ci-confirmation__title">Page non trouvée</h1>
                        <p class="ci-confirmation__message">
                            La page que vous recherchez n'existe pas ou a été déplacée.
                        </p>
                        <a href="/" class="ci-btn ci-btn--primary">
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="ci-footer">
            <div class="ci-container">
                <p>&copy; <?= date('Y') ?> Campus Inter. Tous droits réservés.</p>
            </div>
        </footer>
    </div>
</body>
</html>
