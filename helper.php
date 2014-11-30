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

class modsplayHelper
{
	static function getTopArticles(&$params)
	{
	
		//referance to database
		$db = JFactory::getDBO();
		
		$artnum = (int) $params->get('articles_number');
		$query = 'SELECT a.* FROM #__bursts AS a LIMIT  '. (int) $artnum;
		$db->setQuery($query); $rows = $db->loadObjectList();
		if ($db->getErrorNum()) {JError::raiseWarning( 500, $db->stderr() );}
		return $rows;
	}

}
