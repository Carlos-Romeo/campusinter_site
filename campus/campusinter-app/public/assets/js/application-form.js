/**
 * CAMPUS INTER - Formulaire de préinscription
 * Gestion multi-étapes et soumission
 */

const CIApplicationForm = {
    currentStep: 1,
    totalSteps: 3,
    formData: {},
    errors: {},

    init() {
        this.form = document.getElementById('ci-application-form');
        if (!this.form) return;

        this.formData = {
            program_id: parseInt(document.getElementById('ci-program-id')?.value) || 0,
            campus_id: parseInt(document.getElementById('ci-campus-id')?.value) || 0,
        };

        this.bindEvents();
        this.showStep(1);
    },

    bindEvents() {
        // Navigation étapes
        document.querySelectorAll('[data-ci-step]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const step = parseInt(btn.dataset.ciStep);
                if (step > this.currentStep) {
                    if (this.validateCurrentStep()) {
                        this.goToStep(step);
                    }
                } else {
                    this.goToStep(step);
                }
            });
        });

        // Soumission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submit();
        });

        // Validation en temps réel
        this.form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => {
                if (this.errors[field.name]) {
                    this.clearFieldError(field.name);
                }
            });
        });
    },

    goToStep(step) {
        if (step < 1 || step > this.totalSteps) return;
        
        // Si on va à l'étape 3 (confirmation), remplir le résumé
        if (step === 3) {
            this.fillSummary();
        }

        this.showStep(step);
    },

    showStep(step) {
        this.currentStep = step;

        // Cacher toutes les étapes
        document.querySelectorAll('.ci-form-step').forEach(el => {
            el.style.display = 'none';
        });

        // Afficher l'étape courante
        const currentStepEl = document.querySelector(`[data-ci-step-content="${step}"]`);
        if (currentStepEl) {
            currentStepEl.style.display = 'block';
        }

        // Mettre à jour les indicateurs
        document.querySelectorAll('.ci-step').forEach(el => {
            const stepNum = parseInt(el.dataset.ciStepIndicator);
            el.classList.remove('ci-step--active', 'ci-step--completed');
            
            if (stepNum === step) {
                el.classList.add('ci-step--active');
            } else if (stepNum < step) {
                el.classList.add('ci-step--completed');
            }
        });

        // Scroll en haut
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    validateCurrentStep() {
        this.errors = {};
        const currentStepEl = document.querySelector(`[data-ci-step-content="${this.currentStep}"]`);
        if (!currentStepEl) return true;

        const fields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    },

    validateField(field) {
        const name = field.name;
        const value = field.value.trim();
        let error = '';

        // Required
        if (field.hasAttribute('required') && !value) {
            error = 'Ce champ est requis.';
        }
        // Email
        else if (name === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                error = 'L\'email n\'est pas valide.';
            }
        }
        // Phone
        else if (name === 'phone' && value) {
            const phoneClean = value.replace(/[\s\-\(\)\+]/g, '');
            if (phoneClean.length < 8 || !/^\d+$/.test(phoneClean)) {
                error = 'Le numéro de téléphone n\'est pas valide.';
            }
        }
        // Birth date
        else if (name === 'birth_date' && value) {
            const date = new Date(value);
            const now = new Date();
            if (isNaN(date.getTime()) || date >= now) {
                error = 'La date de naissance n\'est pas valide.';
            }
        }
        // Bac average
        else if (name === 'bac_average' && value) {
            const avg = parseFloat(value);
            if (isNaN(avg) || avg < 0 || avg > 20) {
                error = 'La moyenne doit être entre 0 et 20.';
            }
        }
        // Last diploma average
        else if (name === 'last_diploma_average' && value) {
            const avg = parseFloat(value);
            if (isNaN(avg) || avg < 0 || avg > 20) {
                error = 'La moyenne doit être entre 0 et 20.';
            }
        }
        // Bac year
        else if (name === 'bac_year' && value) {
            const year = parseInt(value);
            const now = new Date().getFullYear();
            if (isNaN(year) || year < 1950 || year > now) {
                error = `L'année doit être entre 1950 et ${now}.`;
            }
        }

        if (error) {
            this.showFieldError(name, error);
            return false;
        }

        this.clearFieldError(name);
        return true;
    },

    showFieldError(name, message) {
        this.errors[name] = message;
        const field = this.form.querySelector(`[name="${name}"]`);
        if (!field) return;

        // Ajouter la classe d'erreur
        field.style.borderColor = 'var(--ci-error)';

        // Afficher le message
        let errorEl = field.parentElement.querySelector('.ci-error');
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.className = 'ci-error';
            field.parentElement.appendChild(errorEl);
        }
        errorEl.textContent = message;
        errorEl.style.display = 'block';
    },

    clearFieldError(name) {
        delete this.errors[name];
        const field = this.form.querySelector(`[name="${name}"]`);
        if (!field) return;

        field.style.borderColor = '';
        const errorEl = field.parentElement.querySelector('.ci-error');
        if (errorEl) {
            errorEl.style.display = 'none';
        }
    },

    collectFormData() {
        const data = {};
        const fields = this.form.querySelectorAll('input, select, textarea');
        
        fields.forEach(field => {
            if (field.name && field.name !== '_csrf_token' && field.name !== 'website') {
                const value = field.value.trim();
                if (value) {
                    data[field.name] = value;
                }
            }
        });

        return { ...this.formData, ...data };
    },

    fillSummary() {
        const data = this.collectFormData();
        
        const summaryEl = document.getElementById('ci-summary');
        if (!summaryEl) return;

        let html = `
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Prénom</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.first_name || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Nom</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.last_name || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Email</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.email || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Téléphone</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.phone || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Date de naissance</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.birth_date || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Dernier diplôme</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.last_diploma || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Année du BAC</span>
                <span class="ci-confirmation__detail-value">${this.escapeHtml(data.bac_year || '-')}</span>
            </div>
            <div class="ci-confirmation__detail">
                <span class="ci-confirmation__detail-label">Moyenne du BAC</span>
                <span class="ci-confirmation__detail-value">${data.bac_average ? data.bac_average + '/20' : '-'}</span>
            </div>
        `;

        if (data.message) {
            html += `
                <div class="ci-confirmation__detail" style="flex-direction: column; gap: 8px;">
                    <span class="ci-confirmation__detail-label">Message</span>
                    <span class="ci-confirmation__detail-value">${this.escapeHtml(data.message)}</span>
                </div>
            `;
        }

        summaryEl.innerHTML = html;
    },

    async submit() {
        if (!this.validateCurrentStep()) return;

        const btn = document.getElementById('ci-submit-btn');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('ci-btn--loading');
        }

        try {
            const data = this.collectFormData();
            
            // Ajouter le token CSRF
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                data._csrf_token = csrfMeta.getAttribute('content');
            }

            const response = await CIApi.post('/api/applications', data);

            if (response.success) {
                // Rediriger vers la page de confirmation
                const params = new URLSearchParams({
                    ref: response.reference,
                    program: response.program,
                    campus: response.campus,
                });
                window.location.href = `/confirmation?${params}`;
            } else {
                this.showFormError(response.message);
                if (response.errors) {
                    Object.entries(response.errors).forEach(([name, message]) => {
                        this.showFieldError(name, message);
                    });
                }
            }
        } catch (error) {
            this.showFormError(error.message || 'Une erreur est survenue. Veuillez réessayer.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('ci-btn--loading');
            }
        }
    },

    showFormError(message) {
        let errorEl = document.getElementById('ci-form-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.id = 'ci-form-error';
            errorEl.className = 'ci-toast ci-toast--error ci-toast--visible';
            errorEl.style.cssText = 'position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1000;';
            document.body.appendChild(errorEl);
        }

        errorEl.innerHTML = `
            <span class="ci-toast__message">${this.escapeHtml(message)}</span>
            <button class="ci-toast__close" onclick="this.parentElement.remove()">&times;</button>
        `;

        // Auto-remove après 5 secondes
        setTimeout(() => {
            if (errorEl.parentElement) {
                errorEl.remove();
            }
        }, 5000);
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
    CIApplicationForm.init();
});
