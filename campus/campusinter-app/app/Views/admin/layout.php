<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \CampusInter\Helpers\Csrf::generate() ?>">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> — Campus Inter</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="ci-admin">
        <!-- Sidebar Overlay (mobile) -->
        <div id="ci-sidebar-overlay" class="ci-admin__sidebar-overlay"></div>

        <!-- Sidebar -->
        <aside id="ci-sidebar" class="ci-admin__sidebar">
            <div class="ci-admin__sidebar-header">
                <a href="/admin" class="ci-admin__sidebar-logo">
                    <img src="/assets/img/icon.svg" alt="" width="38" height="38" class="ci-header__logo-icon" style="background:none;box-shadow:none;">
                    <span>Campus Inter</span>
                </a>
            </div>

            <nav class="ci-admin__sidebar-nav">
                <div class="ci-admin__nav-group">
                    <a href="/admin" class="ci-admin__nav-link <?= $title === 'Dashboard' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                <div class="ci-admin__nav-group">
                    <div class="ci-admin__nav-group-title">Données</div>
                    <a href="/admin/academic-years" class="ci-admin__nav-link <?= $title === 'Années académiques' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Années académiques
                    </a>
                    <a href="/admin/domains" class="ci-admin__nav-link <?= $title === 'Domaines' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Domaines
                    </a>
                    <a href="/admin/specialties" class="ci-admin__nav-link <?= $title === 'Spécialités' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Spécialités
                    </a>
                    <a href="/admin/cities" class="ci-admin__nav-link <?= $title === 'Villes' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Villes
                    </a>
                    <a href="/admin/institutions" class="ci-admin__nav-link <?= $title === 'Établissements' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Établissements
                    </a>
                    <a href="/admin/campuses" class="ci-admin__nav-link <?= $title === 'Campus' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        Campus
                    </a>
                </div>

                <div class="ci-admin__nav-group">
                    <div class="ci-admin__nav-group-title">Formations</div>
                    <a href="/admin/programs" class="ci-admin__nav-link <?= $title === 'Formations' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Formations
                    </a>
                    <a href="/admin/csv-import" class="ci-admin__nav-link <?= $title === 'Import des formations' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Import CSV
                    </a>
                </div>

                <div class="ci-admin__nav-group">
                    <div class="ci-admin__nav-group-title">Candidatures</div>
                    <a href="/admin/applications" class="ci-admin__nav-link <?= $title === 'Candidatures' ? 'ci-admin__nav-link--active' : '' ?>">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Candidatures
                    </a>
                </div>
            </nav>

            <div class="ci-admin__sidebar-footer">
                <a href="/logout" class="ci-admin__nav-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Déconnexion
                </a>
            </div>
        </aside>

        <!-- Main -->
        <div class="ci-admin__main">
            <!-- Topbar -->
            <header class="ci-admin__topbar">
                <div style="display: flex; align-items: center; gap: var(--ci-space-md);">
                    <button id="ci-sidebar-toggle" class="ci-admin__menu-toggle" aria-label="Menu">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="ci-admin__topbar-title"><?= htmlspecialchars($title ?? '') ?></h1>
                </div>
                <div class="ci-admin__topbar-actions">
                    <div class="ci-admin__user">
                        <div class="ci-admin__user-avatar">
                            <?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <span><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="ci-admin__content">
                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/assets/js/api.js"></script>
    <script src="/assets/js/admin.js"></script>
    <?= $scripts ?? '' ?>
</body>
</html>
