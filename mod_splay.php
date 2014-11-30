/**
 * @package Module mod_splay for Joomla! 3.3 and Joomla! 2.5
 * @version 1.0: mod_splay.php 599 2014-12-1 12:00:00
 * @author Ioannis Smirnios
 * @copyright (C) 2014- University of Patras
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

//include helper file
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');


//get the items to display from the helper

$items = modsplayHelper::getTopArticles($params);

//add css
$document = JFactory::getDocument();
$document->addStylesheet(JURI::base() . 'modules/mod_splay/assets/css/style.css');

//include template
require(JModuleHelper::getLayoutPath('mod_splay'));