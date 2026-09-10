<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template CampusInter
 *
 * @copyright   Copyright (C) 2024 Campus Inter. Tous droits réservés.
 * @license     GNU General Public License version 2 ou ultérieure
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$params = $app->getTemplate(true)->params;
$primaryColor = $params->get('primaryColor', '#0a2540');
$accentColor = $params->get('accentColor', '#f39c12');
$offlineMessage = $app->get('offline_message');
$sitename = $app->get('sitename');
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($sitename, ENT_QUOTES, 'UTF-8'); ?> - Site en maintenance</title>
    <jdoc:include type="head" />
    <style>
        :root {
            --primary-color: <?php echo $primaryColor; ?>;
            --accent-color: <?php echo $accentColor; ?>;
            --white: #ffffff;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), #1a3a5c);
            color: var(--white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            text-align: center;
        }
        .offline-container {
            max-width: 600px;
            padding: 40px 24px;
        }
        .offline-logo {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
        }
        .offline-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .offline-message {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 32px;
        }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="offline-logo"><?php echo htmlspecialchars($sitename, ENT_QUOTES, 'UTF-8'); ?></div>
        <h1 class="offline-title">Site en maintenance</h1>
        <p class="offline-message">
            <?php echo $offlineMessage ? htmlspecialchars($offlineMessage, ENT_QUOTES, 'UTF-8') : 'Nous serons de retour très bientôt. Merci de votre patience.'; ?>
        </p>
    </div>
</body>
</html>
