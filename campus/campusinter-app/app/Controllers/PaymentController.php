<?php
/**
 * CAMPUS INTER - Payment Controller
 * Gestion des paiements (Timoney)
 */

declare(strict_types=1);

namespace CampusInter\Controllers;

use CampusInter\Config\Database;
use CampusInter\Helpers\Csrf;
use CampusInter\Helpers\Logger;

class PaymentController extends Controller
{
    /**
     * Page de paiement pour une candidature
     */
    public function paymentPage(): void
    {
        $reference = $_GET['ref'] ?? '';
        
        if (empty($reference)) {
            $this->redirect('/preinscription');
            return;
        }

        // Récupérer la candidature
        $stmt = $this->db->query(
            "SELECT a.*, p.name as program_name, p.level, 
                    c.name as campus_name, i.name as institution_name
             FROM applications a
             JOIN programs p ON a.program_id = p.id
             LEFT JOIN campuses c ON a.campus_id = c.id
             LEFT JOIN institutions i ON c.institution_id = i.id
             WHERE a.reference = ?",
            [$reference]
        );
        $application = $stmt->fetch();

        if (!$application) {
            $this->redirect('/preinscription');
            return;
        }

        // Vérifier si déjà payé
        if ($application['status'] === 'paid') {
            $this->redirect('/confirmation?ref=' . $reference);
            return;
        }

        $data = [
            'application' => $application,
            'amount' => 25000,
            'currency' => 'FCFA',
        ];

        $this->view('public/payment', $data);
    }

    /**
     * Traiter le paiement Timoney
     */
    public function processPayment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Méthode non autorisée'], 405);
            return;
        }

        $data = $this->getJsonInput();
        
        // Si pas de JSON, essayer POST classique
        if (empty($data)) {
            $data = $_POST;
        }

        $reference = $data['reference'] ?? '';
        $phone = $data['phone'] ?? '';
        $provider = $data['provider'] ?? 'orange_money';

        // Validation
        if (empty($reference) || empty($phone)) {
            $this->json(['success' => false, 'message' => 'Référence et numéro requis']);
            return;
        }

        // Récupérer la candidature
        $stmt = $this->db->query(
            "SELECT id, reference, status FROM applications WHERE reference = ?",
            [$reference]
        );
        $application = $stmt->fetch();

        if (!$application) {
            $this->json(['success' => false, 'message' => 'Candidature non trouvée']);
            return;
        }

        // Vérifier si déjà payé
        if ($application['status'] === 'paid') {
            $this->json(['success' => false, 'message' => 'Déjà payé']);
            return;
        }

        // Générer une référence de transaction unique
        $transactionRef = 'TIM-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(4)));
        $amount = 25000;

        // Enregistrer le paiement en attente
        $stmt = $this->db->query(
            "INSERT INTO payments (application_id, amount, currency, provider, transaction_reference, status)
             VALUES (?, ?, 'FCFA', ?, ?, 'pending')",
            [$application['id'], $amount, $provider, $transactionRef]
        );
        $paymentId = $this->db->lastInsertId();

        // Simuler l'appel API Timoney
        // En production, ici on ferait un vrai appel API
        $paymentResult = $this->callTimoneyApi($phone, $amount, $transactionRef, $provider);

        if ($paymentResult['success']) {
            // Mettre à jour le statut du paiement
            $this->db->query(
                "UPDATE payments SET status = 'paid', paid_at = NOW() WHERE id = ?",
                [$paymentId]
            );

            // Mettre à jour le statut de la candidature
            $this->db->query(
                "UPDATE applications SET status = 'paid' WHERE id = ?",
                [$application['id']]
            );

            Logger::info('Paiement réussi', [
                'application' => $reference,
                'transaction' => $transactionRef,
                'amount' => $amount
            ]);

            $this->json([
                'success' => true, 
                'message' => 'Paiement effectué avec succès',
                'redirect' => '/confirmation?ref=' . $reference
            ]);
        } else {
            // Paiement échoué
            $this->db->query(
                "UPDATE payments SET status = 'failed' WHERE id = ?",
                [$paymentId]
            );

            Logger::warning('Paiement échoué', [
                'application' => $reference,
                'transaction' => $transactionRef,
                'error' => $paymentResult['message'] ?? 'Erreur inconnue'
            ]);

            $this->json([
                'success' => false, 
                'message' => $paymentResult['message'] ?? 'Paiement échoué. Veuillez réessayer.'
            ]);
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkStatus(): void
    {
        $reference = $_GET['ref'] ?? '';
        
        if (empty($reference)) {
            $this->json(['success' => false, 'message' => 'Référence requise']);
            return;
        }

        $stmt = $this->db->query(
            "SELECT a.status, p.status as payment_status, p.paid_at, p.transaction_reference
             FROM applications a
             LEFT JOIN payments p ON a.id = p.application_id AND p.status = 'paid'
             WHERE a.reference = ?",
            [$reference]
        );
        $result = $stmt->fetch();

        if (!$result) {
            $this->json(['success' => false, 'message' => 'Non trouvé']);
            return;
        }

        $this->json([
            'success' => true,
            'status' => $result['status'],
            'paid' => $result['payment_status'] === 'paid',
            'paid_at' => $result['paid_at']
        ]);
    }

    /**
     * Appel API Timoney (simulation)
     * En production, remplacer par le vrai appel API
     */
    private function callTimoneyApi(string $phone, int $amount, string $transactionRef, string $provider): array
    {
        // Configuration Timoney
        $apiKey = getenv('TIMONEY_API_KEY') ?: '';
        $apiSecret = getenv('TIMONEY_API_SECRET') ?: '';
        $apiUrl = getenv('TIMONEY_API_URL') ?: 'https://api.timoney.com/v1';

        // Pour le développement, simuler un succès après 2 secondes
        if (getenv('APP_ENV') === 'development' || empty($apiKey)) {
            sleep(1); // Simuler latence réseau
            return [
                'success' => true,
                'message' => 'Paiement simulé avec succès',
                'transaction_id' => $transactionRef
            ];
        }

        // En production - appel réel à l'API Timoney
        $payload = [
            'phone' => $phone,
            'amount' => $amount,
            'currency' => 'XOF',
            'reference' => $transactionRef,
            'provider' => $provider, // orange_money, mtn_mobile
            'description' => 'Frais de dossier Campus Inter'
        ];

        $ch = curl_init($apiUrl . '/payments');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
                'X-API-Secret: ' . $apiSecret
            ],
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            $result = json_decode($response, true);
            return [
                'success' => true,
                'message' => $result['message'] ?? 'Paiement réussi',
                'transaction_id' => $result['transaction_id'] ?? $transactionRef
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur serveur de paiement'
        ];
    }

    /**
     * Générer un reçu PDF (optionnel)
     */
    public function receipt(): void
    {
        $reference = $_GET['ref'] ?? '';
        
        if (empty($reference)) {
            $this->redirect('/');
            return;
        }

        $stmt = $this->db->query(
            "SELECT a.*, p.name as program_name, 
                    pay.transaction_reference, pay.paid_at, pay.amount
             FROM applications a
             JOIN programs p ON a.program_id = p.id
             LEFT JOIN payments pay ON a.id = pay.application_id AND pay.status = 'paid'
             WHERE a.reference = ? AND a.status = 'paid'",
            [$reference]
        );
        $application = $stmt->fetch();

        if (!$application) {
            $this->redirect('/');
            return;
        }

        // Pour l'instant, afficher une page de reçu simple
        $this->view('public/receipt', ['application' => $application]);
    }
}
