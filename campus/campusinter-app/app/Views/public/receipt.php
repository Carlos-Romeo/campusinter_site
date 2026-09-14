<?php
/**
 * Campus Inter - Reçu de paiement
 * 
 * Variable disponible : $application
 */
?>

<div class="receipt-container">
    <div class="receipt-header">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h1>Paiement confirmé</h1>
        <p>Votre frais de dossier a été enregistré</p>
    </div>

    <div class="receipt-card">
        <div class="receipt-title">REÇU DE PAIEMENT</div>
        
        <div class="receipt-logo">
            <strong>CAMPUS INTER</strong>
        </div>

        <div class="receipt-details">
            <div class="receipt-row">
                <span class="label">Référence candidature:</span>
                <span class="value"><?php echo htmlspecialchars($application['reference']); ?></span>
            </div>
            <div class="receipt-row">
                <span class="label">Référence transaction:</span>
                <span class="value"><?php echo htmlspecialchars($application['transaction_reference'] ?? 'N/A'); ?></span>
            </div>
            <div class="receipt-row">
                <span class="label">Date:</span>
                <span class="value"><?php echo date('d/m/Y à H:i', strtotime($application['paid_at'] ?? 'now')); ?></span>
            </div>
            <div class="receipt-row highlight">
                <span class="label">Montant payé:</span>
                <span class="value"><?php echo number_format($application['amount'] ?? 25000, 0, ',', ' '); ?> FCFA</span>
            </div>
        </div>

        <div class="receipt-separator"></div>

        <div class="receipt-info">
            <h3>Informations du candidat</h3>
            <div class="receipt-row">
                <span class="label">Nom:</span>
                <span class="value"><?php echo htmlspecialchars($application['last_name'] . ' ' . $application['first_name']); ?></span>
            </div>
            <div class="receipt-row">
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($application['email']); ?></span>
            </div>
            <div class="receipt-row">
                <span class="label">Formation:</span>
                <span class="value"><?php echo htmlspecialchars($application['program_name']); ?></span>
            </div>
            <div class="receipt-row">
                <span class="label">Niveau:</span>
                <span class="value"><?php echo htmlspecialchars($application['level']); ?></span>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Ce reçu fait foi de votre paiement.</p>
            <p>Conservez cette référence pour vos suivi.</p>
        </div>
    </div>

    <div class="receipt-actions">
        <button onclick="window.print()" class="btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Imprimer le reçu
        </button>
        <a href="/" class="btn-home">Retour à l'accueil</a>
    </div>
</div>

<style>
.receipt-container {
    max-width: 500px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.receipt-header {
    text-align: center;
    margin-bottom: 2rem;
}

.success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: #E8F5E9;
    color: #4CAF50;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.receipt-header h1 {
    font-size: 1.5rem;
    margin: 0 0 0.5rem;
    color: #2E7D32;
}

.receipt-header p {
    color: #666;
    margin: 0;
}

.receipt-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    overflow: hidden;
}

.receipt-title {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    text-align: center;
    padding: 1rem;
    font-weight: 700;
    letter-spacing: 2px;
}

.receipt-logo {
    text-align: center;
    padding: 1rem;
    font-size: 1.25rem;
    border-bottom: 2px dashed #eee;
}

.receipt-details,
.receipt-info {
    padding: 1.5rem;
}

.receipt-info h3 {
    font-size: 0.875rem;
    color: #666;
    margin: 0 0 1rem;
    text-transform: uppercase;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.receipt-row:last-child {
    border-bottom: none;
}

.receipt-row .label {
    color: #666;
}

.receipt-row .value {
    font-weight: 500;
    color: #333;
}

.receipt-row.highlight {
    background: #f8f9fa;
    margin: 0.5rem -1.5rem;
    padding: 1rem 1.5rem;
    border-radius: 4px;
}

.receipt-row.highlight .value {
    color: var(--primary-color);
    font-size: 1.25rem;
    font-weight: 700;
}

.receipt-separator {
    border-top: 2px dashed #eee;
    margin: 0 1.5rem;
}

.receipt-footer {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    text-align: center;
    font-size: 0.875rem;
    color: #666;
}

.receipt-footer p {
    margin: 0.25rem 0;
}

.receipt-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-print,
.btn-home {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-print {
    background: var(--primary-color);
    color: white;
    border: none;
}

.btn-print:hover {
    background: var(--primary-dark);
}

.btn-home {
    background: white;
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-home:hover {
    background: var(--bg-secondary);
}

@media print {
    .receipt-actions {
        display: none;
    }
    
    .receipt-container {
        margin: 0;
        max-width: 100%;
    }
}
</style>
