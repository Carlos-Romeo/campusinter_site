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
            Logger::error("Erreur envoi email à {$to}: " . $e->getMessage(), 'email');
            return false;
        }
    }

    private function h($text): string
    {
        return htmlspecialchars((string)($text ?? ''), ENT_QUOTES, 'UTF-8');
    }

    private function renderAdminEmail(array $application, array $program, array $campus): string
    {
        $appName = $this->h($this->config['APP_NAME']);
        $ref = $this->h($application['reference']);
        $progName = $this->h($program['name']);
        $progLevel = $this->h($program['level']);
        $progDuration = $this->h($program['duration'] ?? 'Non précisé');
        $campusName = $this->h($campus['name']);
        $instName = $this->h($campus['institution_name'] ?? '');
        $cityName = $this->h($campus['city_name'] ?? '');
        $campusAddr = $this->h($campus['address'] ?? 'Non précisé');
        $lastName = $this->h($application['last_name']);
        $firstName = $this->h($application['first_name']);
        $birthDate = $this->h($application['birth_date'] ?? 'Non précisé');
        $email = $this->h($application['email']);
        $phone = $this->h($application['phone'] ?? 'Non précisé');
        $lastDiploma = $this->h($application['last_diploma'] ?? 'Non précisé');
        $lastDiplomaInst = $this->h($application['last_diploma_institution'] ?? 'Non précisé');
        $lastDiplomaYear = $this->h($application['last_diploma_year'] ?? 'Non précisé');
        $bacYear = $this->h($application['bac_year'] ?? 'Non précisé');
        $bacSeries = $this->h($application['bac_series'] ?? 'Non précisé');
        $bacAverage = $this->h($application['bac_average'] ?? 'Non précisé');
        $lastDiplomaAvg = $this->h($application['last_diploma_average'] ?? 'Non précisé');
        $message = $this->h($application['message'] ?? '');

        $messageBlock = '';
        if (!empty($application['message'])) {
            $messageBlock = "<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">MESSAGE DU CANDIDAT</h2>
            <p style=\"background:#f9f9f9;padding:15px;border-radius:4px;color:#333;\">{$message}</p>";
        }

        return "<!DOCTYPE html>
<html lang=\"fr\">
<head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><title>Nouvelle préinscription — {$ref}</title></head>
<body style=\"margin:0;padding:0;background-color:#f4f4f4;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;\">
<div style=\"max-width:600px;margin:0 auto;background-color:#ffffff;\">
<div style=\"background-color:#1a1a2e;padding:30px;text-align:center;\"><h1 style=\"color:#ffffff;margin:0;font-size:20px;\">{$appName}</h1><p style=\"color:#a0a0a0;margin:5px 0 0;font-size:14px;\">Nouvelle préinscription</p></div>
<div style=\"padding:30px;\">
<div style=\"background-color:#e8f5e9;border-left:4px solid #4caf50;padding:15px;margin-bottom:25px;border-radius:4px;\"><strong style=\"color:#2e7d32;\">Référence : {$ref}</strong></div>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">FORMATION</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Nom</td><td style=\"padding:5px 0;\"><strong>{$progName}</strong></td></tr><tr><td style=\"padding:5px 0;color:#666;\">Niveau</td><td style=\"padding:5px 0;\">{$progLevel}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Durée</td><td style=\"padding:5px 0;\">{$progDuration}</td></tr></table>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">ÉTABLISSEMENT &amp; CAMPUS</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Campus</td><td style=\"padding:5px 0;\">{$campusName}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Institution</td><td style=\"padding:5px 0;\">{$instName}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Ville</td><td style=\"padding:5px 0;\">{$cityName}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Adresse</td><td style=\"padding:5px 0;\">{$campusAddr}</td></tr></table>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">CANDIDAT</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Nom</td><td style=\"padding:5px 0;\">{$lastName}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Prénom</td><td style=\"padding:5px 0;\">{$firstName}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Date de naissance</td><td style=\"padding:5px 0;\">{$birthDate}</td></tr></table>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">CONTACT</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Email</td><td style=\"padding:5px 0;\">{$email}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Téléphone</td><td style=\"padding:5px 0;\">{$phone}</td></tr></table>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">PARCOURS</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Dernier diplôme</td><td style=\"padding:5px 0;\">{$lastDiploma}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Établissement</td><td style=\"padding:5px 0;\">{$lastDiplomaInst}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Année</td><td style=\"padding:5px 0;\">{$lastDiplomaYear}</td></tr></table>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">BACCALAURÉAT</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Année</td><td style=\"padding:5px 0;\">{$bacYear}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Série</td><td style=\"padding:5px 0;\">{$bacSeries}</td></tr><tr><td style=\"padding:5px 0;color:#666;\">Moyenne</td><td style=\"padding:5px 0;\">{$bacAverage}</td></tr></table>
<h2 style=\"color:#1a1a2e;font-size:16px;border-bottom:2px solid #eee;padding-bottom:8px;\">DERNIÈRE FORMATION</h2>
<table style=\"width:100%;margin-bottom:20px;\"><tr><td style=\"padding:5px 0;color:#666;width:120px;\">Moyenne</td><td style=\"padding:5px 0;\">{$lastDiplomaAvg}</td></tr></table>
{$messageBlock}
</div>
<div style=\"background-color:#f4f4f4;padding:20px;text-align:center;\"><p style=\"color:#999;margin:0;font-size:12px;\">Cet email a été envoyé automatiquement par {$appName}</p></div>
</div>
</body>
</html>";
    }

    private function renderCandidateEmail(array $application, array $program, array $campus): string
    {
        $appName = $this->h($this->config['APP_NAME']);
        $ref = $this->h($application['reference']);
        $name = $this->h($application['first_name'] . ' ' . $application['last_name']);
        $progName = $this->h($program['name']);
        $progLevel = $this->h($program['level']);
        $campusName = $this->h($campus['name']);
        $instName = $this->h($campus['institution_name'] ?? '');

        return "<!DOCTYPE html>
<html lang=\"fr\">
<head><meta charset=\"UTF-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"><title>Confirmation de préinscription</title></head>
<body style=\"margin:0;padding:0;background-color:#f4f4f4;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;\">
<div style=\"max-width:600px;margin:0 auto;background-color:#ffffff;\">
<div style=\"background-color:#1a1a2e;padding:30px;text-align:center;\"><h1 style=\"color:#ffffff;margin:0;font-size:20px;\">{$appName}</h1><p style=\"color:#a0a0a0;margin:5px 0 0;font-size:14px;\">Confirmation de préinscription</p></div>
<div style=\"padding:30px;\">
<p style=\"color:#333;font-size:16px;\">Bonjour {$name},</p>
<div style=\"background-color:#e8f5e9;border-left:4px solid #4caf50;padding:15px;margin:20px 0;border-radius:4px;\"><strong style=\"color:#2e7d32;\">Votre demande a bien été enregistrée.</strong></div>
<table style=\"width:100%;margin:20px 0;background:#f9f9f9;border-radius:8px;\">
<tr><td style=\"padding:12px 15px;color:#666;width:120px;border-bottom:1px solid #eee;\">Référence</td><td style=\"padding:12px 15px;border-bottom:1px solid #eee;\"><strong>{$ref}</strong></td></tr>
<tr><td style=\"padding:12px 15px;color:#666;border-bottom:1px solid #eee;\">Formation</td><td style=\"padding:12px 15px;border-bottom:1px solid #eee;\">{$progName}</td></tr>
<tr><td style=\"padding:12px 15px;color:#666;border-bottom:1px solid #eee;\">Niveau</td><td style=\"padding:12px 15px;border-bottom:1px solid #eee;\">{$progLevel}</td></tr>
<tr><td style=\"padding:12px 15px;color:#666;border-bottom:1px solid #eee;\">Campus</td><td style=\"padding:12px 15px;border-bottom:1px solid #eee;\">{$campusName}</td></tr>
<tr><td style=\"padding:12px 15px;color:#666;\">Institution</td><td style=\"padding:12px 15px;\">{$instName}</td></tr>
</table>
<p style=\"color:#666;font-size:14px;margin-top:20px;\">Conservez votre référence. Elle vous permettra de suivre votre candidature.</p>
<p style=\"color:#333;font-size:16px;margin-top:20px;\">{$appName} vous remercie de votre intérêt.</p>
</div>
<div style=\"background-color:#f4f4f4;padding:20px;text-align:center;\"><p style=\"color:#999;margin:0;font-size:12px;\">Cet email a été envoyé automatiquement par {$appName}</p></div>
</div>
</body>
</html>";
    }
}
