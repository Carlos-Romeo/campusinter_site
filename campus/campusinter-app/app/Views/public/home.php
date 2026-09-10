<!-- Hero Section -->
<section class="ci-hero">
    <div class="ci-container">
        <h1 class="ci-hero__title">Trouvez votre formation</h1>
        <p class="ci-hero__subtitle">
            Explorez les formations disponibles et commencez votre préinscription en quelques clics.
        </p>
    </div>
</section>

<!-- Search Section -->
<section class="ci-section">
    <div class="ci-container">
        <div class="ci-search">
            <h2 class="ci-search__title">Rechercher une formation</h2>
            
            <input type="hidden" id="ci-year-id" value="<?= $activeYear['id'] ?? 0 ?>">

            <div class="ci-search__filters">
                <!-- Niveau -->
                <div class="ci-form-group">
                    <label for="ci-level">Niveau</label>
                    <select id="ci-level" aria-label="Niveau de formation">
                        <option value="">Chargement...</option>
                    </select>
                </div>

                <!-- Domaine -->
                <div class="ci-form-group">
                    <label for="ci-domain">Domaine</label>
                    <select id="ci-domain" disabled aria-label="Domaine de formation">
                        <option value="">Sélectionnez d'abord un niveau</option>
                    </select>
                </div>

                <!-- Spécialité -->
                <div class="ci-form-group">
                    <label for="ci-specialty">Spécialité</label>
                    <select id="ci-specialty" disabled aria-label="Spécialité">
                        <option value="">Sélectionnez d'abord un domaine</option>
                    </select>
                </div>

                <!-- Ville -->
                <div class="ci-form-group">
                    <label for="ci-city">Ville</label>
                    <select id="ci-city" disabled aria-label="Ville">
                        <option value="">Sélectionnez d'abord une spécialité</option>
                    </select>
                </div>

                <!-- Bouton -->
                <div class="ci-search__btn">
                    <button type="button" id="ci-search-btn" class="ci-btn ci-btn--secondary ci-btn--full ci-btn--lg">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Rechercher
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="ci-loading" class="ci-loading" style="display: none;">
            <div class="ci-loading__spinner"></div>
        </div>

        <!-- Results -->
        <div id="ci-results" class="ci-results" style="display: none;">
            <div class="ci-results__header">
                <p id="ci-results-count" class="ci-results__count"></p>
            </div>
            <div id="ci-results-grid" class="ci-results__grid ci-stagger"></div>
            <div id="ci-pagination" class="ci-pagination"></div>
        </div>
    </div>
</section>

<script src="/assets/js/search.js"></script>
