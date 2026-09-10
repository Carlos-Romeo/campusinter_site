
/**
 * ============================================================
 *  CAMPUS INTER — ARTICLE JOOMLA « BLOG » — SECTION NEWSLETTER
 *  ============================================================
 *  INTÉGRATION :
 *   1. Dans l'article Joomla « Le blog », mode Code.
 *   2. Activer Sourcerer (Regular Labs) pour cet article.
 *   3. Coller TOUT le code ci-dessous dans l'article.
 *   4. Unique bloc {source}...{/source} : le PHP traite l'abonnement,
 *      envoie l'email ET affiche le formulaire + le statut.
 *   5. Boîte de réception : amerocar010@gmail.com
 *   6. Régler la messagerie Joomla (Configuration globale →
 *      Serveur → Paramètres de messagerie, SMTP recommandé).
 *  ============================================================
 */
{source}
<?php
declare(strict_types=1);

define('CI_NL_DEST', 'amerocar010@gmail.com');

$ciNlStatut = '';
$ciNlMsg    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        $ciNlStatut = 'ok';
        $ciNlMsg    = 'Merci ! Votre inscription à la newsletter est bien prise en compte.';
    } else {
        $mail = trim(strip_tags((string) ($_POST['email'] ?? '')));

        if ($mail === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $ciNlStatut = 'err';
            $ciNlMsg    = 'Merci de saisir une adresse email valide.';
        } else {
            $lignes = '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Abonnement</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Newsletter Campus Inter</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Adresse e-mail</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Inscrit le</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars(date('d/m/Y à H:i'), ENT_QUOTES, 'UTF-8') . '</td></tr>';

            $corps = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#eef3ef;">'
                . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3ef;padding:24px 12px;"><tr><td align="center">'
                . '<table role="presentation" width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #dce8df;">'
                . '<tr><td style="background:#159447;padding:22px 28px;">'
                . '<span style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:bold;">CAMPUS INTER</span>'
                . '<span style="color:#c9ead6;font-family:Arial,Helvetica,sans-serif;font-size:13px;display:block;margin-top:4px;">Nouvel abonnement à la newsletter</span>'
                . '</td></tr>'
                . '<tr><td style="padding:24px 28px;">' . $lignes . '</td></tr>'
                . '<tr><td style="background:#f5faf6;padding:14px 28px;border-top:1px solid #e6efe7;color:#6b7a6f;font-family:Arial,Helvetica,sans-serif;font-size:12px;">Message envoyé automatiquement depuis le site Campus Inter.</td></tr>'
                . '</table></td></tr></table></body></html>';

            $sujet = 'Nouvel abonnement à la newsletter — Campus Inter';

            $envoiOk = false;
            try {
                $mailer = \Joomla\CMS\Factory::getMailer();
                $mailer->setSender(['contact@campusinter.com', 'Site Campus Inter']);
                $mailer->addRecipient(CI_NL_DEST);
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
                $envoiOk = @mail(CI_NL_DEST, '=?UTF-8?B?' . base64_encode($sujet) . '?=', $corps, $entetes);
            }

            $ciNlStatut = $envoiOk ? 'ok' : 'err';
            $ciNlMsg    = $envoiOk
                ? 'Merci ! Votre inscription à la newsletter est bien prise en compte.'
                : 'L\'inscription a échoué. Veuillez réessayer plus tard.';
        }
    }
}

/* ------- AFFICHAGE ------- */
echo '<style>'
    . '.ci-honeypot{position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;}'
    . '.ci-newsletter-status{display:block;font-size:.9rem;font-weight:600;max-width:520px;margin:16px auto 0;padding:12px 18px;border-radius:999px;text-align:center;}'
    . '.ci-newsletter-status.ci-statut-ok{background:rgba(232,247,238,.15);color:#8fe3b0;border:1px solid rgba(232,247,238,.35);}'
    . '.ci-newsletter-status.ci-statut-err{background:rgba(253,232,234,.15);color:#ffb3ae;border:1px solid rgba(253,232,234,.35);}'
    . '</style>';

if ($ciNlStatut === 'ok') {
    echo '<p class="ci-newsletter-status ci-statut-ok" role="status">' . $ciNlMsg . '</p>';
} elseif ($ciNlStatut === 'err') {
    echo '<p class="ci-newsletter-status ci-statut-err" role="status">' . $ciNlMsg . '</p>';
}

if ($ciNlStatut !== 'ok') {
    $ciToken = \Joomla\CMS\Factory::getSession()->getFormToken();
    echo '<form class="ci-newsletter-form" method="post">'
        . '<input type="hidden" name="' . $ciToken . '" value="1">'
        . '<input type="text" name="website" class="ci-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">'
        . '<input type="email" name="email" placeholder="Votre adresse e-mail" aria-label="Adresse e-mail" required>'
        . '<button class="ci-btn ci-btn--primaire" type="submit">S\'abonner'
        . '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>'
        . '</button></form>';
}
?>
{/source}
