<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template CampusInter
 *
 * @copyright   Copyright (C) 2024 Campus Inter. Tous droits réservés.
 * @license     GNU General Public License version 2 ou ultérieure
 */

defined('_JEXEC') or die;

$footerText = $params->get('footer_text', '');
$footerClass = $params->get('footer_class', '');
?>

<!-- Module de pied de page personnalisé Campus Inter -->
<div class="footer-module <?php echo htmlspecialchars($footerClass, ENT_QUOTES, 'UTF-8'); ?>">
    <?php if ($footerText) : ?>
        <p><?php echo $footerText; ?></p>
    <?php endif; ?>
</div>
