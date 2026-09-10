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
$doc = JFactory::getDocument();
$params = $app->getTemplate(true)->params;
$primaryColor = $params->get('primaryColor', '#0a2540');
$accentColor = $params->get('accentColor', '#f39c12');
$errorCode = $this->error->getCode();
$errorMessage = $this->error->getMessage();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $errorCode . ' - ' . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></title>
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
        .error-container {
            max-width: 600px;
            padding: 40px 24px;
        }
        .error-code {
            font-family: 'Poppins', sans-serif;
            font-size: 6rem;
            font-weight: 800;
            color: var(--accent-color);
            line-height: 1;
            margin-bottom: 16px;
        }
        .error-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .error-message {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 32px;
        }
        .btn-home {
            display: inline-block;
            padding: 14px 32px;
            background: var(--accent-color);
            color: var(--white);
            border-radius: 50px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: #e67e22;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(243, 156, 18, 0.4);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code"><?php echo $errorCode; ?></div>
        <h1 class="error-title">Page non trouvée</h1>
        <p class="error-message">La page que vous recherchez n'existe pas ou a été déplacée.</p>
        <a href="<?php echo JUri::base(); ?>" class="btn-home">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>
</body>
</html>
