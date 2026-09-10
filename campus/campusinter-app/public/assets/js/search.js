/**
 * CAMPUS INTER - Recherche dynamique
 * Gestion des filtres et résultats
 */

const CISearch = {
    state: {
        level: '',
        domainId: '',
        specialtyId: '',
        cityId: '',
        yearId: 0,
        page: 1,
        totalPages: 0,
    },

    elements: {},

    init() {
        this.elements = {
            levelSelect: document.getElementById('ci-level'),
            domainSelect: document.getElementById('ci-domain'),
            specialtySelect: document.getElementById('ci-specialty'),
            citySelect: document.getElementById('ci-city'),
            searchBtn: document.getElementById('ci-search-btn'),
            resultsContainer: document.getElementById('ci-results'),
            resultsCount: document.getElementById('ci-results-count'),
            resultsGrid: document.getElementById('ci-results-grid'),
            pagination: document.getElementById('ci-pagination'),
            loading: document.getElementById('ci-loading'),
        };

        if (!this.elements.levelSelect) return;

        // Récupérer l'année active
        const yearInput = document.getElementById('ci-year-id');
        if (yearInput) {
            this.state.yearId = parseInt(yearInput.value) || 0;
        }

        this.bindEvents();
        this.loadLevels();
    },

    bindEvents() {
        this.elements.levelSelect.addEventListener('change', (e) => {
            this.state.level = e.target.value;
            this.state.domainId = '';
            this.state.specialtyId = '';
            this.state.cityId = '';
            this.state.page = 1;
            this.resetSelects(['domain', 'specialty', 'city']);
            if (this.state.level) {
                this.loadDomains();
            }
        });

        this.elements.domainSelect.addEventListener('change', (e) => {
            this.state.domainId = e.target.value;
            this.state.specialtyId = '';
            this.state.cityId = '';
            this.state.page = 1;
            this.resetSelects(['specialty', 'city']);
            if (this.state.domainId) {
                this.loadSpecialties();
            }
        });

        this.elements.specialtySelect.addEventListener('change', (e) => {
            this.state.specialtyId = e.target.value;
            this.state.cityId = '';
            this.state.page = 1;
            this.resetSelects(['city']);
            this.loadCities();
        });

        this.elements.citySelect.addEventListener('change', (e) => {
            this.state.cityId = e.target.value;
            this.state.page = 1;
            this.search();
        });

        this.elements.searchBtn.addEventListener('click', () => {
            this.state.page = 1;
            this.search();
        });
    },

    resetSelects(selects) {
        selects.forEach(name => {
            const el = this.elements[name + 'Select'];
            if (el) {
                el.innerHTML = '<option value="">Chargement...</option>';
                el.disabled = true;
            }
        });
    },

    async loadLevels() {
        try {
            const data = await CIApi.get(`/api/levels?year_id=${this.state.yearId}`);
            this.populateSelect(this.elements.levelSelect, data.data, 'Choisir un niveau');
            this.elements.levelSelect.disabled = false;
        } catch (error) {
            console.error('Erreur chargement niveaux:', error);
            this.elements.levelSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    },

    async loadDomains() {
        try {
            const data = await CIApi.get(`/api/domains?level=${this.state.level}&year_id=${this.state.yearId}`);
            this.populateSelect(this.elements.domainSelect, data.data, 'Choisir un domaine');
            this.elements.domainSelect.disabled = false;
        } catch (error) {
            console.error('Erreur chargement domaines:', error);
            this.elements.domainSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    },

    async loadSpecialties() {
        try {
            const data = await CIApi.get(`/api/specialties?domain_id=${this.state.domainId}&level=${this.state.level}&year_id=${this.state.yearId}`);
            this.populateSelect(this.elements.specialtySelect, data.data, 'Choisir une spécialité');
            this.elements.specialtySelect.disabled = false;
        } catch (error) {
            console.error('Erreur chargement spécialités:', error);
            this.elements.specialtySelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    },

    async loadCities() {
        try {
            const params = new URLSearchParams({
                level: this.state.level,
                domain_id: this.state.domainId,
                specialty_id: this.state.specialtyId,
                year_id: this.state.yearId,
            });
            const data = await CIApi.get(`/api/cities?${params}`);
            this.populateSelect(this.elements.citySelect, data.data, 'Choisir une ville');
            this.elements.citySelect.disabled = false;
        } catch (error) {
            console.error('Erreur chargement villes:', error);
            this.elements.citySelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    },

    async search() {
        this.showLoading();

        try {
            const params = new URLSearchParams({
                level: this.state.level,
                domain_id: this.state.domainId,
                specialty_id: this.state.specialtyId,
                city_id: this.state.cityId,
                year_id: this.state.yearId,
                page: this.state.page,
            });

            const data = await CIApi.get(`/api/programs/search?${params}`);
            
            this.state.totalPages = data.totalPages;
            this.renderResults(data.data, data.total);
            this.renderPagination(data.page, data.totalPages);
        } catch (error) {
            console.error('Erreur recherche:', error);
            this.showError('Une erreur est survenue lors de la recherche.');
        }
    },

    populateSelect(select, items, placeholder) {
        let html = `<option value="">${placeholder}</option>`;
        items.forEach(item => {
            html += `<option value="${item.id}">${this.escapeHtml(item.name || item)}</option>`;
        });
        select.innerHTML = html;
    },

    renderResults(programs, total) {
        this.elements.resultsContainer.style.display = 'block';
        this.elements.resultsCount.innerHTML = `<strong>${total}</strong> formation${total > 1 ? 's' : ''} trouvée${total > 1 ? 's' : ''}`;

        if (programs.length === 0) {
            this.elements.resultsGrid.innerHTML = `
                <div class="ci-empty" style="grid-column: 1 / -1;">
                    <svg class="ci-empty__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="ci-empty__title">Aucune formation trouvée</h3>
                    <p class="ci-empty__text">Essayez de modifier vos critères de recherche.</p>
                </div>
            `;
            this.elements.pagination.innerHTML = '';
            return;
        }

        let html = '';
        programs.forEach((program, index) => {
            html += this.renderCard(program, index);
        });
        this.elements.resultsGrid.innerHTML = html;
    },

    renderCard(program, index) {
        const campusesHtml = program.campuses.map(c => `
            <div class="ci-campus">
                <svg class="ci-campus__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                    <div class="ci-campus__name">${this.escapeHtml(c.institution_name)}</div>
                    <div class="ci-campus__details">${this.escapeHtml(c.campus_name)} — ${this.escapeHtml(c.city_name)}</div>
                </div>
            </div>
        `).join('');

        return `
            <div class="ci-card ci-fade-in" style="animation-delay: ${index * 50}ms;">
                <div class="ci-card__header">
                    <h3 class="ci-card__title">${this.escapeHtml(program.name)}</h3>
                </div>
                <div class="ci-card__badges">
                    <span class="ci-badge ci-badge--level">${this.escapeHtml(program.level)}</span>
                    <span class="ci-badge ci-badge--domain">${this.escapeHtml(program.domain_name)}</span>
                    ${program.specialty_name ? `<span class="ci-badge ci-badge--specialty">${this.escapeHtml(program.specialty_name)}</span>` : ''}
                    <span class="ci-badge ci-badge--campus">${program.campus_count} campus</span>
                </div>
                ${program.description ? `<p class="ci-card__body">${this.escapeHtml(program.description).substring(0, 150)}${program.description.length > 150 ? '...' : ''}</p>` : ''}
                <div class="ci-card__footer">
                    <div class="ci-campuses">
                        ${campusesHtml}
                    </div>
                    <a href="/programme?id=${program.id}" class="ci-btn ci-btn--primary ci-btn--sm">
                        Voir la formation
                    </a>
                </div>
            </div>
        `;
    },

    renderPagination(currentPage, totalPages) {
        if (totalPages <= 1) {
            this.elements.pagination.innerHTML = '';
            return;
        }

        let html = '';
        
        // Previous
        html += `<button class="ci-pagination__btn" ${currentPage <= 1 ? 'disabled' : ''} onclick="CISearch.goToPage(${currentPage - 1})">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>`;

        // Pages
        const maxVisible = 5;
        let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let end = Math.min(totalPages, start + maxVisible - 1);
        
        if (end - start + 1 < maxVisible) {
            start = Math.max(1, end - maxVisible + 1);
        }

        if (start > 1) {
            html += `<button class="ci-pagination__btn" onclick="CISearch.goToPage(1)">1</button>`;
            if (start > 2) html += `<span class="ci-pagination__btn" style="border:none;cursor:default;">...</span>`;
        }

        for (let i = start; i <= end; i++) {
            html += `<button class="ci-pagination__btn ${i === currentPage ? 'ci-pagination__btn--active' : ''}" onclick="CISearch.goToPage(${i})">${i}</button>`;
        }

        if (end < totalPages) {
            if (end < totalPages - 1) html += `<span class="ci-pagination__btn" style="border:none;cursor:default;">...</span>`;
            html += `<button class="ci-pagination__btn" onclick="CISearch.goToPage(${totalPages})">${totalPages}</button>`;
        }

        // Next
        html += `<button class="ci-pagination__btn" ${currentPage >= totalPages ? 'disabled' : ''} onclick="CISearch.goToPage(${currentPage + 1})">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>`;

        this.elements.pagination.innerHTML = html;
    },

    goToPage(page) {
        this.state.page = page;
        this.search();
        // Scroll vers les résultats
        this.elements.resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },

    showLoading() {
        this.elements.loading.style.display = 'flex';
        this.elements.resultsGrid.innerHTML = '';
        this.elements.pagination.innerHTML = '';
    },

    hideLoading() {
        this.elements.loading.style.display = 'none';
    },

    showError(message) {
        this.hideLoading();
        this.elements.resultsGrid.innerHTML = `
            <div class="ci-empty" style="grid-column: 1 / -1;">
                <h3 class="ci-empty__title">Erreur</h3>
                <p class="ci-empty__text">${this.escapeHtml(message)}</p>
            </div>
        `;
    },

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },
};

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
    CISearch.init();
});
