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