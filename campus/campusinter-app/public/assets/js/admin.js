/**
 * CAMPUS INTER - Admin JavaScript
 * Gestion de l'interface d'administration
 */

const CIAdmin = {
    init() {
        this.initSidebar();
        this.initDeleteButtons();
        this.initStatusSelects();
        this.initFormValidation();
    },

    // =====================================================
    // SIDEBAR MOBILE
    // =====================================================
    
    initSidebar() {
        const toggle = document.getElementById('ci-sidebar-toggle');
        const sidebar = document.getElementById('ci-sidebar');
        const overlay = document.getElementById('ci-sidebar-overlay');

        if (!toggle || !sidebar) return;

        const close = () => {
            sidebar.classList.remove('ci-admin__sidebar--open');
            overlay?.classList.remove('ci-admin__sidebar-overlay--visible');
        };

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('ci-admin__sidebar--open');
            overlay?.classList.toggle('ci-admin__sidebar-overlay--visible');
        });

        overlay?.addEventListener('click', close);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('ci-admin__sidebar--open')) {
                close();
            }
        });

        const mq = window.matchMedia('(min-width: 1024px)');
        mq.addEventListener('change', (e) => {
            if (e.matches) close();
        });
    },

    // =====================================================
    // SUPPRESSION AVEC CONFIRMATION
    // =====================================================

    initDeleteButtons() {
        document.querySelectorAll('[data-ci-delete]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const url = btn.dataset.ciDelete;
                const name = btn.dataset.ciName || 'cet élément';

                if (confirm(`Êtes-vous sûr de vouloir supprimer "${name}" ?`)) {
                    this.deleteItem(url, btn);
                }
            });
        });
    },

    async deleteItem(url, btn) {
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                // Supprimer la ligne du tableau
                const row = btn.closest('tr');
                if (row) {
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                }
            } else {
                this.showToast(data.message, 'error');
            }
        } catch (error) {
            this.showToast('Erreur lors de la suppression.', 'error');
        }
    },

    // =====================================================
    // MISE À JOUR DU STATUT
    // =====================================================

    initStatusSelects() {
        document.querySelectorAll('[data-ci-status]').forEach(select => {
            select.addEventListener('change', (e) => {
                const id = select.dataset.ciStatus;
                const status = select.value;
                this.updateStatus(id, status, select);
            });
        });
    },

    async updateStatus(id, status, select) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            const formData = new FormData();
            formData.append('id', id);
            formData.append('status', status);
            formData.append('_csrf_token', csrfToken);

            const response = await fetch('/admin/applications/status', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });
            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
            } else {
                this.showToast(data.message, 'error');
            }
        } catch (error) {
            this.showToast('Erreur lors de la mise à jour.', 'error');
        }
    },

    // =====================================================
    // VALIDATION FORMULAIRES
    // =====================================================

    initFormValidation() {
        document.querySelectorAll('.ci-admin__form form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = 'var(--ci-error)';
                        isValid = false;
                    } else {
                        field.style.borderColor = '';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    this.showToast('Veuillez remplir tous les champs obligatoires.', 'error');
                }
            });
        });
    },

    // =====================================================
    // TOAST NOTIFICATIONS
    // =====================================================

    showToast(message, type = 'info') {
        const existing = document.querySelector('.ci-toast--admin');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `ci-toast ci-toast--${type} ci-toast--visible ci-toast--admin`;
        toast.innerHTML = `
            <span class="ci-toast__message">${message}</span>
            <button class="ci-toast__close" onclick="this.parentElement.remove()">&times;</button>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('ci-toast--visible');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    },
};

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
    CIAdmin.init();
});
