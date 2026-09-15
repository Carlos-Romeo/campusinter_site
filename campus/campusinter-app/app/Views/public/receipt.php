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

<!-- Styles loaded via /assets/css/payments.css in layout -->
