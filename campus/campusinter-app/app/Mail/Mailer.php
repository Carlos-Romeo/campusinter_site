<?php
/**
 * CAMPUS INTER - Mailer
 * Envoi d'emails via PHPMailer
 */

declare(strict_types=1);

namespace CampusInter\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use CampusInter\Helpers\Logger;

class Mailer
{
    private PHPMailer $mail;
    private array $config;

    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->mail = new PHPMailer(true);

        $this->setupSMTP();
    }

    private function setupSMTP(): void
    {
        $this->mail->isSMTP();
        $this->mail->Host = $this->config['MAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->config['MAIL_USERNAME'];
        $this->mail->Password = $this->config['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = $this->config['MAIL_ENCRYPTION'];
        $this->mail->Port = $this->config['MAIL_PORT'];
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';

        $this->mail->setFrom(
            $this->config['MAIL_FROM'],
            $this->config['MAIL_FROM_NAME']
        );
    }

    public function sendApplicationToAdmin(array $application, array $program, array $campus): bool
    {
        $to = $this->config['MAIL_TO'];
        $subject = 'Nouvelle préinscription — ' . $application['reference'];

        $html = $this->renderAdminEmail($application, $program, $campus);

        return $this->send($to, $subject, $html);
    }

    public function sendConfirmationToCandidate(array $application, array $program, array $campus): bool
    {
        if (!$this->config['SEND_CONFIRMATION_EMAIL']) {
            return true;
        }

        $to = $application['email'];
        $subject = 'Confirmation de préinscription — ' . $application['reference'];

        $html = $this->renderCandidateEmail($application, $program, $campus);

        return $this->send($to, $subject, $html);
    }

    private function send(string $to, string $subject, string $html): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $html;
            $this->mail->AltBody = strip_tags($html);

            $this->mail->send();
            Logger::info("Email envoyé à {$to}: {$subject}", 'email');
            return true;
        } catch (Exception $e) {
            Logger::error("Erreur envoi email à {$to}: " . $e->getMessage(), 'email', [
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function renderAdminEmail(array $application, array $program, array $campus): string
    {
        $appName = $this->config['APP_NAME'];
        $ref = htmlspecialchars($application['reference'], ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle préinscription — {$ref}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <div style="max-width:600px;margin:0 auto;background-color:#ffffff;">
        <!-- Header -->
        <div style="background-color:#1a1a2e;padding:30px;text-align:center;">
            <h1 style="color:#ffffff;margin:0;font-size:20px;">{$appName}</h1>
            <p style="color:#a0a0a0;margin:5px 0 0;font-size:14px;">Nouvelle préinscription</p>
        </div>
        
        <!-- Body -->
        <div style="padding:30px;">
            <div style="background-color:#e8f5e9;border-left:4px solid #4caf50;padding:15px;margin-bottom:25px;border-radius:4px;">
                <strong style="color:#2e7d32;">Référence : {$ref}</strong>
            </div>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">FORMATION</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Nom</td><td style="padding:5px 0;"><strong>" . htmlspecialchars($program['name'], ENT_QUOTES, 'UTF-8') . "</strong></td></tr>
                <tr><td style="padding:5px 0;color:#666;">Niveau</td><td style="padding:5px 0;\">" . htmlspecialchars($program['level'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Durée</td><td style="padding:5px 0;\">" . htmlspecialchars($program['duration'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">ÉTABLISSEMENT & CAMPUS</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Campus</td><td style="padding:5px 0;\">" . htmlspecialchars($campus['name'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Institution</td><td style="padding:5px 0;\">" . htmlspecialchars($campus['institution_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Ville</td><td style="padding:5px 0;\">" . htmlspecialchars($campus['city_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Adresse</td><td style="padding:5px 0;\">" . htmlspecialchars($campus['address'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">CANDIDAT</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Nom</td><td style="padding:5px 0;\">" . htmlspecialchars($application['last_name'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Prénom</td><td style="padding:5px 0;\">" . htmlspecialchars($application['first_name'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Date de naissance</td><td style="padding:5px 0;\">" . htmlspecialchars($application['birth_date'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">CONTACT</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Email</td><td style="padding:5px 0;\">" . htmlspecialchars($application['email'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Téléphone</td><td style="padding:5px 0;\">" . htmlspecialchars($application['phone'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">PARCOURS</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Dernier diplôme</td><td style="padding:5px 0;\">" . htmlspecialchars($application['last_diploma'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Établissement</td><td style="padding:5px 0;\">" . htmlspecialchars($application['last_diploma_institution'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Année</td><td style="padding:5px 0;\">" . htmlspecialchars($application['last_diploma_year'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">BACCALAURÉAT</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Année</td><td style="padding:5px 0;\">" . htmlspecialchars($application['bac_year'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Série</td><td style="padding:5px 0;\">" . htmlspecialchars($application['bac_series'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style="padding:5px 0;color:#666;">Moyenne</td><td style="padding:5px 0;\">" . htmlspecialchars($application['bac_average'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <h2 style="color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;">DERNIÈRE FORMATION</h2>
            <table style="width:100%;margin-bottom:20px;">
                <tr><td style="padding:5px 0;color:#666;width:120px;">Moyenne</td><td style="padding:5px 0;\">" . htmlspecialchars($application['last_diploma_average'] ?? 'Non précisé', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            " . (!empty($application['message']) ? "<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">MESSAGE DU CANDIDAT</h2><p style=\"background:#f9f9f9;padding:15px;border-radius:4px;color:#333;\">" . htmlspecialchars($application['message'], ENT_QUOTES, 'UTF-8') . "</p>" : "") . "
        </div>
        
        <!-- Footer -->
        <div style=\"background-color:#f4f4f4;padding:20px;text-align:center;\">
            <p style=\"color:#999;margin:0;font-size:12px;\">Cet email a été envoyé automatiquement par {$appName}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    private function renderCandidateEmail(array $application, array $program, array $campus): string
    {
        $appName = $this->config['APP_NAME'];
        $ref = htmlspecialchars($application['reference'], ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($application['first_name'] . ' ' . $application['last_name'], ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de préinscription</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <div style="max-width:600px;margin:0 auto;background-color:#ffffff;">
        <!-- Header -->
        <div style="background-color:#1a1a2e;padding:30px;text-align:center;">
            <h1 style="color:#ffffff;margin:0;font-size:20px;">{$appName}</h1>
            <p style="color:#a0a0a0;margin:5px 0 0;font-size:14px;">Confirmation de préinscription</p>
        </div>
        
        <!-- Body -->
        <div style="padding:30px;">
            <p style="color:#333;font-size:16px;">Bonjour {$name},</p>
            
            <div style="background-color:#e8f5e9;border-left:4px solid #4caf50;padding:15px;margin:20px 0;border-radius:4px;">
                <strong style="color:#2e7d32;">Votre demande a bien été enregistrée.</strong>
            </div>
            
            <table style="width:100%;margin:20px 0;background:#f9f9f9;border-radius:8px;">
                <tr><td style="padding:12px 15px;color:#666;width:120px;border-bottom:1px solid #eee;">Référence</td><td style="padding:12px 15px;border-bottom:1px solid #eee;"><strong>{$ref}</strong></td></tr>
                <tr><td style="padding:12px 15px;color:#666;border-bottom:1px solid #eee;">Formation</td><td style="padding:12px 15px;border-bottom:1px solid #eee;\">" . htmlspecialchars($program['name'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style=\"padding:12px 15px;color:#666;border-bottom:1px solid #eee;\">Niveau</td><td style=\"padding:12px 15px;border-bottom:1px solid #eee;\">" . htmlspecialchars($program['level'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style=\"padding:12px 15px;color:#666;border-bottom:1px solid #eee;\">Campus</td><td style=\"padding:12px 15px;border-bottom:1px solid #eee;\">" . htmlspecialchars($campus['name'], ENT_QUOTES, 'UTF-8') . "</td></tr>
                <tr><td style=\"padding:12px 15px;color:#666;\">Institution</td><td style=\"padding:12px 15px;\">" . htmlspecialchars($campus['institution_name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td></tr>
            </table>
            
            <p style="color:#666;font-size:14px;margin-top:20px;">
                Conservez votre référence. Elle vous permettra de suivre votre candidature.
            </p>
            
            <p style="color:#333;font-size:16px;margin-top:20px;">
                {$appName} vous remercie de votre intérêt.
            </p>
        </div>
        
        <!-- Footer -->
        <div style="background-color:#f4f4f4;padding:20px;text-align:center;">
            <p style="color:#999;margin:0;font-size:12px;">Cet email a été envoyé automatiquement par {$appName}</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
