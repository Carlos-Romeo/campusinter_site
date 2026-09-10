<!-- Program Detail -->
<section class="ci-section">
    <div class="ci-container">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Breadcrumb -->
            <nav style="margin-bottom: var(--ci-space-lg); font-size: 0.875rem; color: var(--ci-gray-500);">
                <a href="/" style="color: var(--ci-secondary);">Formations</a>
                <span style="margin: 0 var(--ci-space-sm);">/</span>
                <span><?= htmlspecialchars($program['name']) ?></span>
            </nav>

            <!-- Header -->
            <div class="ci-card" style="margin-bottom: var(--ci-space-lg);">
                <div class="ci-card__header">
                    <h1 class="ci-card__title" style="font-size: 1.5rem;"><?= htmlspecialchars($program['name']) ?></h1>
                </div>
                <div class="ci-card__badges">
                    <span class="ci-badge ci-badge--level"><?= htmlspecialchars($program['level']) ?></span>
                    <span class="ci-badge ci-badge--domain"><?= htmlspecialchars($program['domain_name']) ?></span>
                    <?php if ($program['specialty_name']): ?>
                        <span class="ci-badge ci-badge--specialty"><?= htmlspecialchars($program['specialty_name']) ?></span>
                    <?php endif; ?>
                    <?php if ($program['duration']): ?>
                        <span class="ci-badge ci-badge--campus"><?= htmlspecialchars($program['duration']) ?></span>
                    <?php endif; ?>
                </div>
                <?php if ($program['description']): ?>
                    <p class="ci-card__body" style="margin-top: var(--ci-space-md);">
                        <?= nl2br(htmlspecialchars($program['description'])) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Établissements -->
            <div class="ci-card">
                <h2 style="font-size: 1.125rem; font-weight: 700; margin-bottom: var(--ci-space-lg); color: var(--ci-gray-900);">
                    Établissements disponibles
                </h2>

                <?php if (empty($campuses)): ?>
                    <p class="ci-card__body">Aucun campus disponible pour cette formation.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: var(--ci-space-md);">
                        <?php foreach ($campuses as $campus): ?>
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; padding: var(--ci-space-md); background: var(--ci-gray-50); border-radius: var(--ci-radius-md); flex-wrap: wrap; gap: var(--ci-space-md);">
                                <div>
                                    <div style="font-weight: 600; color: var(--ci-gray-900); margin-bottom: var(--ci-space-xs);">
                                        <?= htmlspecialchars($campus['institution_name']) ?>
                                    </div>
                                    <div style="font-size: 0.875rem; color: var(--ci-gray-600);">
                                        <?= htmlspecialchars($campus['name']) ?>
                                    </div>
                                    <div style="font-size: 0.8125rem; color: var(--ci-gray-500); margin-top: 2px;">
                                        <?= htmlspecialchars($campus['city_name']) ?>
                                        <?php if ($campus['address']): ?>
                                            — <?= htmlspecialchars($campus['address']) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <a href="/preinscription?program_id=<?= $program['id'] ?>&campus_id=<?= $campus['id'] ?>" 
                                   class="ci-btn ci-btn--accent ci-btn--sm">
                                    Je souhaite me préinscrire
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
