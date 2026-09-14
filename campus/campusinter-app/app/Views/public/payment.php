<?php
/**
 * Campus Inter - Page de Paiement
 * Paiement des frais de dossier par Mobile Money
 */

$this->view('public/payment', [
    'application' => $application,
    'amount' => $amount,
    'currency' => $currency,
]);
?>

<div class="payment-container">
    <div class="payment-header">
        <div class="payment-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
        </div>
        <h1>Paiement des frais de dossier</h1>
        <p class="payment-subtitle">Finalisez votre pré-inscription</p>
    </div>

    <!-- Résumé de la candidature -->
    <div class="application-summary">
        <h3>Résumé de votre candidature</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="label">Référence</span>
                <span class="value reference"><?php echo htmlspecialchars($application['reference']); ?></span>
            </div>
            <div class="summary-item">
                <span class="label">Formation</span>
                <span class="value"><?php echo htmlspecialchars($application['program_name']); ?></span>
            </div>
            <div class="summary-item">
                <span class="label">Niveau</span>
                <span class="value"><?php echo htmlspecialchars($application['level']); ?></span>
            </div>
            <?php if (!empty($application['campus_name'])): ?>
            <div class="summary-item">
                <span class="label">Campus</span>
                <span class="value"><?php echo htmlspecialchars($application['campus_name']); ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-item">
                <span class="label">Candidat</span>
                <span class="value"><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></span>
            </div>
            <div class="summary-item">
                <span class="label">Email</span>
                <span class="value"><?php echo htmlspecialchars($application['email']); ?></span>
            </div>
        </div>
    </div>

    <!-- Montant à payer -->
    <div class="payment-amount">
        <span class="amount-label">Montant à payer</span>
        <span class="amount-value"><?php echo number_format($amount, 0, ',', ' '); ?> <?php echo $currency; ?></span>
        <span class="amount-note">Frais de dossier (non remboursable)</span>
    </div>

    <!-- Formulaire de paiement -->
    <form id="paymentForm" class="payment-form">
        <input type="hidden" name="reference" value="<?php echo htmlspecialchars($application['reference']); ?>">
        
        <div class="form-section">
            <h3>Choisissez votre mode de paiement</h3>
            
            <div class="payment-methods">
                <label class="payment-method">
                    <input type="radio" name="provider" value="orange_money" checked>
                    <div class="method-card">
                        <div class="method-icon orange">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <text x="12" y="16" text-anchor="middle" fill="white" font-size="10" font-weight="bold">OM</text>
                            </svg>
                        </div>
                        <span class="method-name">Orange Money</span>
                    </div>
                </label>
                
                <label class="payment-method">
                    <input type="radio" name="provider" value="mtn_mobile">
                    <div class="method-card">
                        <div class="method-icon mtn">
                            <svg xmlns="http://www.w33.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                                <text x="12" y="16" text-anchor="middle" fill="white" font-size="10" font-weight="bold">MTN</text>
                            </svg>
                        </div>
                        <span class="method-name">MTN Mobile Money</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="form-section">
            <h3>Numéro de téléphone</h3>
            <p class="section-note">Le numéro associé à votre compte Mobile Money</p>
            
            <div class="phone-input-group">
                <select name="country_code" class="country-code">
                    <option value="+228">🇹🇬 +228 (Togo)</option>
                    <option value="+225">🇨🇮 +225 (Côte d'Ivoire)</option>
                    <option value="+221">🇸🇳 +221 (Sénégal)</option>
                    <option value="+226">🇧🇫 +226 (Burkina Faso)</option>
                    <option value="+223">🇲🇱 +223 (Mali)</option>
                    <option value="+227">🇳🇪 +227 (Niger)</option>
                </select>
                <input type="tel" 
                       name="phone" 
                       id="paymentPhone"
                       class="phone-input" 
                       placeholder="Ex: 90 12 34 56"
                       required
                       pattern="[0-9\s\-]{8,}">
            </div>
            
            <div id="phoneError" class="error-message" style="display: none;">
                Veuillez entrer un numéro de téléphone valide
            </div>
        </div>

        <div class="form-section instructions">
            <h3>Comment payer ?</h3>
            <ol class="payment-steps">
                <li>Sélectionnez votre opérateur mobile</li>
                <li>Entrez votre numéro de téléphone</li>
                <li>Cliquez sur "Payer maintenant"</li>
                <li>Vous recevrez une demande de confirmation sur votre téléphone</li>
                <li>Confirmez le paiement en entrant votre code secret</li>
            </ol>
        </div>

        <div class="form-section security">
            <div class="security-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span>Paiement 100% sécurisé via Timoney</span>
            </div>
        </div>

        <button type="submit" class="btn-payment" id="btnPay">
            <span class="btn-text">Payer <?php echo number_format($amount, 0, ',', ' '); ?> <?php echo $currency; ?></span>
            <span class="btn-loading" style="display: none;">
                <svg class="spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.4 31.4" stroke-linecap="round">
                        <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
                    </circle>
                </svg>
                Traitement en cours...
            </span>
        </button>
    </form>

    <!-- Message d'erreur -->
    <div id="paymentError" class="alert alert-error" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span id="errorMessage"></span>
    </div>

    <!-- Lien retour -->
    <div class="payment-footer">
        <a href="/preinscription" class="back-link">← Retour au formulaire</a>
    </div>
</div>

<style>
/* Styles spécifiques au paiement */
.payment-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.payment-header {
    text-align: center;
    margin-bottom: 2rem;
}

.payment-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 50%;
    color: white;
    margin-bottom: 1rem;
}

.payment-header h1 {
    font-size: 1.75rem;
    margin: 0 0 0.5rem;
    color: var(--text-primary);
}

.payment-subtitle {
    color: var(--text-secondary);
    margin: 0;
}

.application-summary {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.application-summary h3 {
    font-size: 1rem;
    margin: 0 0 1rem;
    color: var(--text-primary);
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.summary-item .label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-item .value {
    font-weight: 500;
    color: var(--text-primary);
}

.summary-item .reference {
    font-family: monospace;
    color: var(--primary-color);
}

.payment-amount {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 12px;
    color: white;
    margin-bottom: 1.5rem;
}

.amount-label {
    display: block;
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.amount-value {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
}

.amount-note {
    display: block;
    font-size: 0.75rem;
    opacity: 0.8;
    margin-top: 0.5rem;
}

.payment-form {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.form-section {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    font-size: 1rem;
    margin: 0 0 0.5rem;
    color: var(--text-primary);
}

.section-note {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0 0 1rem;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.payment-method input {
    display: none;
}

.method-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.25rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-method input:checked + .method-card {
    border-color: var(--primary-color);
    background: var(--primary-light);
}

.method-icon {
    margin-bottom: 0.75rem;
}

.method-icon.orange {
    color: #FF6600;
}

.method-icon.mtn {
    color: #FFCC00;
}

.method-name {
    font-weight: 500;
    color: var(--text-primary);
}

.phone-input-group {
    display: flex;
    gap: 0.5rem;
}

.country-code {
    width: 140px;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 1rem;
    background: white;
}

.phone-input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 1rem;
}

.phone-input:focus,
.country-code:focus {
    outline: none;
    border-color: var(--primary-color);
}

.payment-steps {
    margin: 0;
    padding-left: 1.25rem;
    color: var(--text-secondary);
    line-height: 1.8;
}

.security {
    display: flex;
    justify-content: center;
}

.security-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #E8F5E9;
    color: #2E7D32;
    border-radius: 20px;
    font-size: 0.875rem;
}

.btn-payment {
    display: block;
    width: calc(100% - 3rem);
    margin: 1.5rem;
    padding: 1rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-payment:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-payment:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-loading {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    margin: 1.5rem;
    border-radius: 8px;
}

.alert-error {
    background: #FFEBEE;
    color: #C62828;
}

.error-message {
    color: #C62828;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.payment-footer {
    text-align: center;
    padding: 1.5rem;
}

.back-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.875rem;
}

.back-link:hover {
    color: var(--primary-color);
}

@media (max-width: 480px) {
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .phone-input-group {
        flex-direction: column;
    }
    
    .country-code {
        width: 100%;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const btnPay = document.getElementById('btnPay');
    const phoneInput = document.getElementById('paymentPhone');
    const phoneError = document.getElementById('phoneError');
    const errorDiv = document.getElementById('paymentError');
    const errorMessage = document.getElementById('errorMessage');

    // Validation du téléphone
    function validatePhone(phone) {
        const cleaned = phone.replace(/[\s\-]/g, '');
        return cleaned.length >= 8 && /^\d+$/.test(cleaned);
    }

    phoneInput.addEventListener('input', function() {
        if (validatePhone(this.value)) {
            phoneError.style.display = 'none';
            this.style.borderColor = '';
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Valider le téléphone
        if (!validatePhone(phoneInput.value)) {
            phoneError.style.display = 'block';
            phoneInput.style.borderColor = '#C62828';
            return;
        }

        // Désactiver le bouton
        btnPay.disabled = true;
        btnPay.querySelector('.btn-text').style.display = 'none';
        btnPay.querySelector('.btn-loading').style.display = 'inline-flex';
        errorDiv.style.display = 'none';

        // Préparer les données
        const formData = new FormData(form);
        const data = {
            reference: formData.get('reference'),
            phone: formData.get('country_code') + formData.get('phone').replace(/[\s\-]/g, ''),
            provider: formData.get('provider')
        };

        try {
            const response = await fetch('/api/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Rediriger vers la confirmation
                window.location.href = result.redirect || '/confirmation?ref=' + data.reference;
            } else {
                errorMessage.textContent = result.message || 'Erreur lors du paiement';
                errorDiv.style.display = 'flex';
            }
        } catch (error) {
            errorMessage.textContent = 'Erreur de connexion. Veuillez réessayer.';
            errorDiv.style.display = 'flex';
        } finally {
            btnPay.disabled = false;
            btnPay.querySelector('.btn-text').style.display = 'inline';
            btnPay.querySelector('.btn-loading').style.display = 'none';
        }
    });
});
</script>
