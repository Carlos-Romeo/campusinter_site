<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès interdit — Campus Inter</title>
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
                        <div class="ci-confirmation__icon" style="background: var(--ci-error-bg); color: var(--ci-error);">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-6V9a4 4 0 00-8 0v2" />
                            </svg>
                        </div>
                        <h1 class="ci-confirmation__title">Accès interdit</h1>
                        <p class="ci-confirmation__message">
                            Vous n'avez pas les droits nécessaires pour accéder à cette page.
                        </p>
                        <a href="/login" class="ci-btn ci-btn--primary">
                            Se connecter
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
