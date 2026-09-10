
/**
 * ============================================================
 *  CAMPUS INTER — ARTICLE JOOMLA « ACCUEIL » — SECTION CTA + FORMULAIRE
 *  ============================================================
 *  INTÉGRATION :
 *   1. Dans l'article Joomla « Accueil », passer en mode Code.
 *   2. Activer Sourcerer (Regular Labs) pour cet article.
 *   3. Coller TOUT le code ci-dessous dans l'article.
 *   4. Tout est dans un unique bloc {source}...{/source} : le PHP
 *      traite le formulaire, envoie l'email ET affiche le formulaire
 *      avec son message de confirmation. Aucun fichier à la racine.
 *   5. Boîte de réception : amerocar010@gmail.com
 *   6. Régler la messagerie Joomla (Configuration globale → Serveur
 *      → Paramètres de messagerie, SMTP recommandé).
 *  ============================================================
 */

/* ---------------- BLOC PHP SOURCERER (article complet) ---------------- */
{source}
<?php
declare(strict_types=1);

define('CI_CTA_DEST', 'amerocar010@gmail.com');

$ciCtaStatut = '';
$ciCtaMsg    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        $ciCtaStatut = 'ok';
        $ciCtaMsg    = 'Merci ! Votre demande a bien été envoyée, un conseiller Campus Inter vous recontacte sous 48h.';
    } else {
        $nom  = trim(strip_tags((string) ($_POST['nom']  ?? '')));
        $mail = trim(strip_tags((string) ($_POST['email'] ?? '')));
        $tel  = trim(strip_tags((string) ($_POST['telephone'] ?? '')));
        $proj = trim(strip_tags((string) ($_POST['projet'] ?? '')));

        if ($nom === '' || $mail === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $ciCtaStatut = 'err';
            $ciCtaMsg    = 'Merci de renseigner un nom et une adresse email valide.';
        } else {
            $lignes = '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Nom complet</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Adresse email</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Téléphone / WhatsApp</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($tel !== '' ? $tel : 'Non renseigné', ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Type de projet</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($proj !== '' ? $proj : 'Non précisé', ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Reçu le</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars(date('d/m/Y à H:i'), ENT_QUOTES, 'UTF-8') . '</td></tr>';

            $corps = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#eef3ef;">'
                . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3ef;padding:24px 12px;"><tr><td align="center">'
                . '<table role="presentation" width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #dce8df;">'
                . '<tr><td style="background:#159447;padding:22px 28px;">'
                . '<span style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:bold;">CAMPUS INTER</span>'
                . '<span style="color:#c9ead6;font-family:Arial,Helvetica,sans-serif;font-size:13px;display:block;margin-top:4px;">Nouvelle demande reçue depuis le site</span>'
                . '</td></tr>'
                . '<tr><td style="padding:24px 28px;">' . $lignes . '</td></tr>'
                . '<tr><td style="background:#f5faf6;padding:14px 28px;border-top:1px solid #e6efe7;color:#6b7a6f;font-family:Arial,Helvetica,sans-serif;font-size:12px;">Message envoyé automatiquement depuis le site Campus Inter.</td></tr>'
                . '</table></td></tr></table></body></html>';

            $sujet = 'Nouvelle demande Campus Inter — ' . $nom;

            $envoiOk = false;
            try {
                $mailer = \Joomla\CMS\Factory::getMailer();
                $mailer->setSender(['contact@campusinter.com', 'Site Campus Inter']);
                $mailer->addRecipient(CI_CTA_DEST);
                $mailer->addReplyTo($mail);
                $mailer->setSubject($sujet);
                $mailer->setBody($corps);
                $mailer->isHTML(true);
                $mailer->Encoding = 'base64';
                $mailer->CharSet = 'UTF-8';
                $envoiOk = $mailer->Send() !== false;
            } catch (Throwable $e) {
                $envoiOk = false;
            }

            if (!$envoiOk) {
                $entetes  = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
                $entetes .= 'From: Site Campus Inter <contact@campusinter.com>' . "\r\n";
                $entetes .= 'Reply-To: ' . $mail . "\r\n";
                $envoiOk = @mail(CI_CTA_DEST, '=?UTF-8?B?' . base64_encode($sujet) . '?=', $corps, $entetes);
            }

            $ciCtaStatut = $envoiOk ? 'ok' : 'err';
            $ciCtaMsg    = $envoiOk
                ? 'Merci ' . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . ' ! Votre demande a bien été envoyée, un conseiller Campus Inter vous recontacte sous 48h.'
                : 'L\'envoi a échoué. Veuillez réessayer ou appeler le +228 22 70 25 96.';
        }
    }
}

/* ------- AFFICHAGE DU FORMULAIRE + STATUT ------- */

echo '<style>'
    . '.ci-honeypot{position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;}'
    . '.ci-form-status{display:block;font-size:.9rem;font-weight:600;margin:0 auto 18px;max-width:680px;padding:12px 18px;border-radius:10px;text-align:center;}'
    . '.ci-form-status.ci-statut-ok{background:rgba(232,247,238,.95);color:#0F7A3A;border:1px solid #b7e3c9;}'
    . '.ci-form-status.ci-statut-err{background:rgba(253,232,234,.95);color:#b3261e;border:1px solid #f2c1c1;}'
    . '</style>';

if ($ciCtaStatut === 'ok') {
    echo '<p class="ci-full ci-form-status ci-statut-ok" role="status">' . $ciCtaMsg . '</p>';
} elseif ($ciCtaStatut === 'err') {
    echo '<p class="ci-full ci-form-status ci-statut-err" role="status">' . $ciCtaMsg . '</p>';
}

if ($ciCtaStatut !== 'ok') {
    $ciToken = \Joomla\CMS\Factory::getSession()->getFormToken();
    echo '<form class="ci-cta-form ci-reveal" id="ciForm" method="post">'
        . '<input type="hidden" name="' . $ciToken . '" value="1">'
        . '<input type="text" name="website" class="ci-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">'
        . '<div><input type="text" name="nom" placeholder="Nom complet *" required aria-label="Nom complet"></div>'
        . '<div><input type="email" name="email" placeholder="Adresse email *" required aria-label="Adresse email"></div>'
        . '<div><input type="tel" name="telephone" placeholder="Téléphone / WhatsApp" aria-label="Téléphone"></div>'
        . '<div><select name="projet" aria-label="Type de projet">'
        . '<option value="">Type de projet</option><option>Étudier en France</option><option>Étudier en Belgique</option>'
        . '<option>Travailler en France</option><option>Visa &amp; Démarches</option><option>Installation</option>'
        . '</select></div>'
        . '<div class="ci-full"><button class="ci-btn ci-btn--primaire" type="submit">Envoyer ma demande'
        . '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4z"/></svg>'
        . '</button></div>'
        . '<p class="ci-full" style="font-size:.8rem;color:#dceadf;margin:0;">'
        . 'En envoyant ce formulaire, vous acceptez d\'être contacté par Campus Inter. Vos données restent confidentielles.'
        . '</p></form>';
}
?>
{/source}
