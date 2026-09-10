<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Campus Inter Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="ci-login">
        <div class="ci-login__card">
            <div class="ci-login__header">
                <div class="ci-login__logo">
                    <span class="ci-header__logo-icon">CI</span>
                    <span>Campus Inter</span>
                </div>
                <p class="ci-login__title">Administration</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="ci-login__error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['expired'])): ?>
                <div class="ci-login__error">
                    Votre session a expiré. Veuillez vous reconnecter.
                </div>
            <?php endif; ?>

            <form class="ci-login__form" method="post" action="/login">
                <?= \CampusInter\Helpers\Csrf::field() ?>

                <div class="ci-form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email" placeholder="admin@campusinter.com" autofocus>
                </div>

                <div class="ci-form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>

                <button type="submit" class="ci-btn ci-btn--primary ci-btn--full ci-btn--lg">
                    Se connecter
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--ci-space-lg);">
                <a href="/" style="font-size: 0.875rem; color: var(--ci-gray-500);">← Retour au site</a>
            </div>
        </div>
    </div>
</body>
</html>
