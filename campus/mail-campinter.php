<?php
/**
 * ============================================================
 *  CAMPUS INTER — TRAITEMENT AJAX DES FORMULAIRES (JOOMLA)
 *  ============================================================
 *  Placez ce fichier à la racine de votre site Joomla :
 *  /mail-campinter.php
 *
 *  Il traite en AJAX les 3 formulaires du site :
 *    1. Formulaire CTA d'accueil (nom, email, téléphone, projet)
 *    2. Formulaire « Étudier en Belgique » (nom, email, téléphone, domaine, message)
 *    3. Formulaire Newsletter Blog (email)
 *
 *  Il utilise automatiquement la configuration SMTP de Joomla
 *  définie dans « Configuration globale » -> « Serveur ».
 *  ============================================================
 */

declare(strict_types=1);

// Entête JSON obligatoire
header('Content-Type: application/json; charset=UTF-8');

// Sécurité : n'accepter que les requêtes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// 1. Initialisation du framework Joomla
define('_JEXEC', 1);
if (!defined('JPATH_BASE')) {
    define('JPATH_BASE', __DIR__);
}

if (!file_exists(JPATH_BASE . '/includes/defines.php') || !file_exists(JPATH_BASE . '/includes/framework.php')) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur : ce fichier doit être placé à la racine de votre site Joomla.'
    ]);
    exit;
}

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';

// Adresse de destination des formulaires (réception)
define('CI_MAIL_DEST', 'amerocar010@gmail.com');

// 2. Vérification anti-spam Honeypot (le champ "website" doit rester vide)
$honeypot = trim((string) ($_POST['website'] ?? ''));
if ($honeypot !== '') {
    // Faux succès pour tromper les robots
    echo json_encode(['success' => true, 'message' => 'Demande envoyée avec succès.']);
    exit;
}

// 3. Récupération des données POST
$type      = trim((string) ($_POST['type'] ?? 'contact'));
$page      = trim(strip_tags((string) ($_POST['page'] ?? 'Accueil')));
$nom       = trim(strip_tags((string) ($_POST['nom'] ?? '')));
$mail      = trim(strip_tags((string) ($_POST['email'] ?? '')));
$tel       = trim(strip_tags((string) ($_POST['telephone'] ?? '')));
$projet    = trim(strip_tags((string) ($_POST['projet'] ?? '')));
$domaine   = trim(strip_tags((string) ($_POST['domaine'] ?? '')));
$message   = trim(strip_tags((string) ($_POST['message'] ?? '')));

// Validation de base de l'email
if ($mail === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Veuillez renseigner une adresse e-mail valide.']);
    exit;
}

// Construction du sujet et du corps de message selon le type
if ($type === 'newsletter') {
    $sujet = 'Nouvel abonnement newsletter — ' . $mail;
    $titreHeader = 'Nouvel abonnement à la newsletter';
    $lignes = '<tr>'
        . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Type</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Newsletter Campus Inter</td></tr>'
        . '<tr>'
        . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Adresse e-mail</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . '</td></tr>'
        . '<tr>'
        . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Date</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars(date('d/m/Y à H:i'), ENT_QUOTES, 'UTF-8') . '</td></tr>';
} else {
    // Formulaire de contact / information
    if ($nom === '') {
        echo json_encode(['success' => false, 'message' => 'Veuillez renseigner votre nom complet.']);
        exit;
    }

    $sujet = 'Nouvelle demande Campus Inter (' . $page . ') — ' . $nom;
    $titreHeader = 'Nouvelle demande reçue depuis le site (' . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') . ')';

    $lignes = '<tr>'
        . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Nom complet</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . '</td></tr>'
        . '<tr>'
        . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Adresse e-mail</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . '</td></tr>'
        . '<tr>'
        . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Téléphone / WhatsApp</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($tel !== '' ? $tel : 'Non renseigné', ENT_QUOTES, 'UTF-8') . '</td></tr>';

    if ($projet !== '') {
        $lignes .= '<tr>'
            . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Type de projet</td>'
            . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($projet, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    }

    if ($domaine !== '') {
        $lignes .= '<tr>'
            . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Domaine souhaité</td>'
            . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($domaine, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    }

    if ($message !== '') {
        $lignes .= '<tr>'
            . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Message</td>'
            . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;white-space:pre-wrap;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    }

    $lignes .= '<tr>'
        . '<td style="padding:12px 16px;font-weight:700;color:#252525;background:#f5faf6;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Reçu le</td>'
        . '<td style="padding:12px 16px;color:#3a3a3a;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars(date('d/m/Y à H:i'), ENT_QUOTES, 'UTF-8') . '</td></tr>';
}

$corps = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#eef3ef;">'
    . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3ef;padding:24px 12px;"><tr><td align="center">'
    . '<table role="presentation" width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #dce8df;">'
    . '<tr><td style="background:#159447;padding:22px 28px;">'
    . '<span style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:bold;">CAMPUS INTER</span>'
    . '<span style="color:#c9ead6;font-family:Arial,Helvetica,sans-serif;font-size:13px;display:block;margin-top:4px;">' . $titreHeader . '</span>'
    . '</td></tr>'
    . '<tr><td style="padding:24px 28px;"><table width="100%" cellpadding="0" cellspacing="0">' . $lignes . '</table></td></tr>'
    . '<tr><td style="background:#f5faf6;padding:14px 28px;border-top:1px solid #e6efe7;color:#6b7a6f;font-family:Arial,Helvetica,sans-serif;font-size:12px;">Message envoyé automatiquement depuis le site Campus Inter.</td></tr>'
    . '</table></td></tr></table></body></html>';

// 4. Envoi via le Mailer de Joomla (utilisant les réglages SMTP configurés dans Joomla)
$envoiOk = false;
$errorMessage = '';

try {
    // Instanciation de l'application Joomla Site
    $app = \Joomla\CMS\Factory::getApplication('site');
    $config = \Joomla\CMS\Factory::getApplication()->getConfig();

    // Récupération de l'adresse expéditrice officielle configurée dans Joomla
    $mailfrom = $config->get('mailfrom');
    $fromname = $config->get('fromname', 'Campus Inter');

    $mailer = \Joomla\CMS\Factory::getMailer();
    $mailer->setSender([$mailfrom, $fromname]);
    $mailer->addRecipient(CI_MAIL_DEST);
    $mailer->addReplyTo($mail, $nom !== '' ? $nom : $mail);
    $mailer->setSubject($sujet);
    $mailer->setBody($corps);
    $mailer->isHTML(true);
    $mailer->Encoding = 'base64';
    $mailer->CharSet = 'UTF-8';

    $result = $mailer->Send();
    $envoiOk = ($result === true);
    if (!$envoiOk && is_object($result) && method_exists($result, 'getMessage')) {
        $errorMessage = $result->getMessage();
    }
} catch (\Throwable $e) {
    $envoiOk = false;
    $errorMessage = $e->getMessage();
}

// 5. Fallback avec la fonction native PHP mail() si le Mailer Joomla échoue
if (!$envoiOk) {
    $entetes  = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
    $fromFallback = !empty($mailfrom) ? $mailfrom : 'contact@campusinter.com';
    $entetes .= 'From: Campus Inter <' . $fromFallback . ">\r\n";
    $entetes .= 'Reply-To: ' . $mail . "\r\n";
    $envoiOk = @mail(CI_MAIL_DEST, '=?UTF-8?B?' . base64_encode($sujet) . '?=', $corps, $entetes);
}

// 6. Réponse JSON au format attendu par le JavaScript de la page d'accueil
if ($envoiOk) {
    $msgSucces = ($type === 'newsletter')
        ? 'Merci ! Votre inscription à la newsletter a bien été prise en compte.'
        : 'Merci ' . ($nom !== '' ? htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . ' ' : '') . '! Votre demande a bien été envoyée, un conseiller Campus Inter vous recontacte sous 48h.';

    echo json_encode([
        'success' => true,
        'message' => $msgSucces
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'L\'envoi a échoué. Veuillez réessayer ou appeler le +228 22 70 25 96.' . (!empty($errorMessage) ? ' (' . $errorMessage . ')' : '')
    ]);
}
