<?php
/**
 * Campus Inter - Page de Paiement
 * Paiement des frais de dossier par Mobile Money
 * 
 * Variables disponibles : $application, $amount, $currency
 */
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

<!-- Styles loaded via /assets/css/payments.css in layout -->

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
