/**
 * CAMPUS INTER - API Client
 * Gestion des appels AJAX
 */

const CIApi = {
    baseUrl: '',
    
    async request(url, options = {}) {
        const defaults = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        };

        const config = { ...defaults, ...options };
        
        if (config.headers) {
            config.headers = { ...defaults.headers, ...config.headers };
        }

        // Ajouter le token CSRF pour les POST
        if (config.method === 'POST') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                config.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
        }

        try {
            const response = await fetch(this.baseUrl + url, config);
            const data = await response.json();

            if (!response.ok) {
                throw {
                    status: response.status,
                    message: data.message || 'Une erreur est survenue',
                    errors: data.errors || {},
                };
            }

            return data;
        } catch (error) {
            if (error.status) {
                throw error;
            }
            throw {
                status: 0,
                message: 'Erreur de connexion. Veuillez vérifier votre connexion internet.',
                errors: {},
            };
        }
    },

    get(url) {
        return this.request(url, { method: 'GET' });
    },

    post(url, data) {
        return this.request(url, {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },
};
