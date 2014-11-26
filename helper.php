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
