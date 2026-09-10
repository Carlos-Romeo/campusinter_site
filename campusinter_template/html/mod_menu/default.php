<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template CampusInter
 *
 * @copyright   Copyright (C) 2024 Campus Inter. Tous droits réservés.
 * @license     GNU General Public License version 2 ou ultérieure
 */

defined('_JEXEC') or die;

// Récupération de la liste des éléments de menu
$app = JFactory::getApplication();
$menu = $app->getMenu();
$active = $menu->getActive();
$active_id = $active ? $active->id : 0;
$path = $menu->getPath($active_id);

// Fonction pour vérifier si un élément est actif
function isActive($id, $path) {
    foreach ($path as $item) {
        if ($item->id == $id) {
            return true;
        }
    }
    return false;
}
?>
<nav class="main-nav" role="navigation">
    <ul class="nav-list">
        <?php foreach ($list as $i => &$item) : ?>
            <?php 
            $class = ''; 
            if ($item->id == $active_id) {
                $class = 'active';
            } elseif (!empty($path) && $path[0]->id == $item->id) {
                $class = 'ancestor active';
            }
            ?>
            <li class="nav-item <?php echo $class; ?>">
                <?php 
                $attributes = array();
                if ($item->id == $active_id) {
                    $attributes['class'] = 'nav-link active';
                } else {
                    $attributes['class'] = 'nav-link';
                }
                $attributes['href'] = JRoute::_($item->flink);
                $attributes['title'] = htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
                ?>
                <a<?php echo JLayoutHelper::render('joomla.html.attr', array('attributes' => $attributes), false); ?>>
                    <?php echo $item->title; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
