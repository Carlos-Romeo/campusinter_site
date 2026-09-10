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
$showStats = $params->get('showStats', 1);
$showPartners = $params->get('showPartners', 1);
$showFAQ = $params->get('showFAQ', 1);
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <jdoc:include type="head" />
    
    <style>
        :root {
            --primary-color: <?php echo $primaryColor; ?>;
            --accent-color: <?php echo $accentColor; ?>;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --white: #ffffff;
            --light-bg: #f8f9fa;
            --transition: all 0.3s ease;
        }
    </style>
</head>
<body class="site component-page">
    
    <!-- ==============================================
         HEADER : Navigation fixe
         ============================================== -->
    <header id="site-header" class="site-header">
        <div class="header-container">
            <div class="header-logo">
                <a href="<?php echo JUri::base(); ?>" title="Campus Inter - Votre avenir commence ici !">
                    <img src="<?php echo JUri::base(); ?>templates/<?php echo $this->template; ?>/images/logo.png" 
                         alt="Campus Inter" class="logo-img">
                </a>
                <span class="logo-slogan">Votre avenir commence ici !</span>
            </div>
            
            <nav class="main-nav" role="navigation">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="<?php echo JUri::base(); ?>" class="nav-link">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo JUri::base(); ?>index.php/a-propos" class="nav-link">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo JUri::base(); ?>index.php/services" class="nav-link">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo JUri::base(); ?>index.php/partenaires" class="nav-link">Partenaires</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo JUri::base(); ?>index.php/contact" class="nav-link">Contact</a>
                    </li>
                </ul>
            </nav>
            
            <a href="<?php echo JUri::base(); ?>index.php/contact" class="btn-cta">
                <i class="fas fa-paper-plane"></i> Nous contacter
            </a>
            
            <button class="hamburger-btn" aria-label="Menu" aria-expanded="false">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </header>

    <!-- ==============================================
         CONTENU PRINCIPAL DE LA PAGE COMPOSANT
         ============================================== -->
    <main id="content" class="site-main">
        <div class="component-wrapper">
            <jdoc:include type="message" />
            <jdoc:include type="component" />
        </div>
    </main>

    <!-- ==============================================
         PIED DE PAGE
         ============================================== -->
    <footer id="footer" class="site-footer">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-col footer-about">
                        <h3 class="footer-title">Campus Inter</h3>
                        <p class="footer-desc">Votre partenaire de confiance pour la mobilité internationale des étudiants.</p>
                        <div class="social-links">
                            <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                    <div class="footer-col footer-contact">
                        <h3 class="footer-title">Contact</h3>
                        <ul class="contact-list">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Bè-Kpota, face à la mosquée<br>Lomé, Togo</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <a href="tel:+22822702596">+228 22 70 25 96</a><br>
                                <a href="tel:+22897754000">+228 97 75 40 00</a>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:contact@campusinter.com">contact@campusinter.com</a>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <span>Lun - Ven : 09h00 - 17h00<br>Sam : 08h00 - 13h00</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">&copy; <?php echo date('Y'); ?> Campus Inter. Tous droits réservés.</p>
                <ul class="legal-links">
                    <li><a href="<?php echo JUri::base(); ?>index.php/mentions-legales">Mentions légales</a></li>
                    <li><a href="<?php echo JUri::base(); ?>index.php/politique-confidentialite">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <jdoc:include type="modules" name="footer" style="none" />
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="<?php echo JUri::base(); ?>templates/<?php echo $this->template; ?>/js/scripts.js"></script>
</body>
</html>
