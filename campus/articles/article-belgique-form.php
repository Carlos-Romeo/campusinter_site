
/**
 * ============================================================
 *  CAMPUS INTER — ARTICLE JOOMLA « ÉTUDIER EN BELGIQUE »
 *                    SECTION FORMULAIRE « DEMANDE D'INFORMATIONS »
 *  ============================================================
 *  INTÉGRATION :
 *   1. Dans l'article Joomla « Étudier en Belgique », mode Code.
 *   2. Activer Sourcerer (Regular Labs) pour cet article.
 *   3. Coller TOUT le code ci-dessous dans l'article.
 *   4. Unique bloc {source}...{/source} : le PHP traite, envoie
 *      l'email ET affiche le formulaire + le message de statut.
 *   5. Boîte de réception : amerocar010@gmail.com
 *   6. Régler la messagerie Joomla (Configuration globale →
 *      Serveur → Paramètres de messagerie, SMTP recommandé).
 *  ============================================================
 */
{source}
<?php
declare(strict_types=1);

define('CI_BE_DEST', 'amerocar010@gmail.com');

$ciBeStatut = '';
$ciBeMsg    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        $ciBeStatut = 'ok';
        $ciBeMsg    = 'Merci ! Votre demande a bien été envoyée, un conseiller Campus Inter vous recontacte sous 48h.';
    } else {
        $nom      = trim(strip_tags((string) ($_POST['nom']      ?? '')));
        $mail     = trim(strip_tags((string) ($_POST['email']    ?? '')));
        $tel      = trim(strip_tags((string) ($_POST['telephone']?? '')));
        $domaine  = trim(strip_tags((string) ($_POST['domaine']  ?? '')));
        $message  = trim(strip_tags((string) ($_POST['message']  ?? '')));

        if ($nom === '' || $mail === '' || $tel === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $ciBeStatut = 'err';
            $ciBeMsg    = 'Merci de renseigner votre nom, une adresse email valide et votre téléphone.';
        } else {
            $libelles = [
                'Management &amp; Commerce' => 'management',
                'Ingénierie' => 'ingenierie',
                'Digital &amp; Marketing' => 'digital',
                'Communication' => 'communication',
                'Santé' => 'sante',
                'Autre' => 'autre',
            ];
            $domaineLib = array_search($domaine, $libelles, true);
            $domaineAffiche = $domaineLib !== false ? $domaineLib : ($domaine !== '' ? $domaine : 'Non précisé');

            $lignes = '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Nom complet</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Adresse email</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Téléphone / WhatsApp</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($tel, ENT_QUOTES, 'UTF-8') . '</td></tr>'
                . '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Domaine d\'études</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars($domaineAffiche, ENT_QUOTES, 'UTF-8') . '</td></tr>';

            if ($message !== '') {
                $lignes .= '<tr>'
                    . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Message</td>'
                    . '<td style="padding:12px 16px;color:#3a3a3a;border-bottom:1px solid #e6efe7;font-family:Arial,Helvetica,sans-serif;font-size:14px;white-space:pre-wrap;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</td></tr>';
            }

            $lignes .= '<tr>'
                . '<td style="padding:12px 16px;width:36%;font-weight:700;color:#252525;background:#f5faf6;font-family:Arial,Helvetica,sans-serif;font-size:14px;">Reçu le</td>'
                . '<td style="padding:12px 16px;color:#3a3a3a;font-family:Arial,Helvetica,sans-serif;font-size:14px;">' . htmlspecialchars(date('d/m/Y à H:i'), ENT_QUOTES, 'UTF-8') . '</td></tr>';

            $corps = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#eef3ef;">'
                . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3ef;padding:24px 12px;"><tr><td align="center">'
                . '<table role="presentation" width="620" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #dce8df;">'
                . '<tr><td style="background:#159447;padding:22px 28px;">'
                . '<span style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:22px;font-weight:bold;">CAMPUS INTER</span>'
                . '<span style="color:#c9ead6;font-family:Arial,Helvetica,sans-serif;font-size:13px;display:block;margin-top:4px;">Demande d\'information — Étudier en Belgique</span>'
                . '</td></tr>'
                . '<tr><td style="padding:24px 28px;">' . $lignes . '</td></tr>'
                . '<tr><td style="background:#f5faf6;padding:14px 28px;border-top:1px solid #e6efe7;color:#6b7a6f;font-family:Arial,Helvetica,sans-serif;font-size:12px;">Message envoyé automatiquement depuis le site Campus Inter.</td></tr>'
                . '</table></td></tr></table></body></html>';

            $sujet = 'Demande d\'information (Belgique) — ' . $nom;

            $envoiOk = false;
            try {
                $mailer = \Joomla\CMS\Factory::getMailer();
                $mailer->setSender(['contact@campusinter.com', 'Site Campus Inter']);
                $mailer->addRecipient(CI_BE_DEST);
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
                $envoiOk = @mail(CI_BE_DEST, '=?UTF-8?B?' . base64_encode($sujet) . '?=', $corps, $entetes);
            }

            $ciBeStatut = $envoiOk ? 'ok' : 'err';
            $ciBeMsg    = $envoiOk
                ? 'Merci ' . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . ' ! Votre demande a bien été envoyée, un conseiller Campus Inter vous recontacte sous 48h.'
                : 'L\'envoi a échoué. Veuillez réessayer ou appeler le +228 22 70 25 96.';
        }
    }
}

/* ------- AFFICHAGE ------- */
echo '<style>'
    . '.ci-honeypot{position:absolute;left:-9999px;top:-9999px;width:1px;height:1px;overflow:hidden;}'
    . '.ci-form-status{display:block;font-size:.9rem;font-weight:600;margin:0 0 16px;padding:12px 18px;border-radius:10px;text-align:center;}'
    . '.ci-form-status.ci-statut-ok{background:#E8F7EE;color:#0F7A3A;border:1px solid #b7e3c9;}'
    . '.ci-form-status.ci-statut-err{background:#fde8ea;color:#b3261e;border:1px solid #f2c1c1;}'
    . '</style>';

if ($ciBeStatut === 'ok') {
    echo '<div class="ci-form-status ci-statut-ok" role="status">' . $ciBeMsg . '</div>';
} elseif ($ciBeStatut === 'err') {
    echo '<div class="ci-form-status ci-statut-err" role="status">' . $ciBeMsg . '</div>';
}

if ($ciBeStatut !== 'ok') {
    $ciToken = \Joomla\CMS\Factory::getSession()->getFormToken();
    echo '<form class="ci-form ci-reveal-right" method="post">'
        . '<input type="hidden" name="' . $ciToken . '" value="1">'
        . '<input type="text" name="website" class="ci-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">'
        . '<h3>Demande d\'informations</h3>'
        . '<div class="ci-champ"><label for="ci-nom">Nom complet</label>'
        . '<input type="text" id="ci-nom" name="nom" placeholder="Votre nom et prénom" required></div>'
        . '<div class="ci-champ"><label for="ci-email">Adresse e-mail</label>'
        . '<input type="email" id="ci-email" name="email" placeholder="vous@exemple.com" required></div>'
        . '<div class="ci-champ"><label for="ci-tel">Téléphone / WhatsApp</label>'
        . '<input type="tel" id="ci-tel" name="telephone" placeholder="+228 ..." required></div>'
        . '<div class="ci-champ"><label for="ci-domaine">Domaine d\'études</label>'
        . '<select id="ci-domaine" name="domaine">'
        . '<option value="">Sélectionnez un domaine</option>'
        . '<option value="management">Management &amp; Commerce</option>'
        . '<option value="ingenierie">Ingénierie</option>'
        . '<option value="digital">Digital &amp; Marketing</option>'
        . '<option value="communication">Communication</option>'
        . '<option value="sante">Santé</option>'
        . '<option value="autre">Autre</option>'
        . '</select></div>'
        . '<div class="ci-champ"><label for="ci-message">Votre message</label>'
        . '<textarea id="ci-message" name="message" placeholder="Parlez-nous de votre projet..."></textarea></div>'
        . '<button class="ci-btn ci-btn--primaire" type="submit" style="width:100%;justify-content:center;">Envoyer ma demande'
        . '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7z"/></svg>'
        . '</button></form>';
}
?>
{/source}
