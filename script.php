/**
 * @package Module mod_splay for Joomla! 3.3 and Joomla! 2.5
 * @version 1.0: mod_splay.php 599 2014-12-1 12:00:00
 * @author Ioannis Smirnios
 * @copyright (C) 2014- University of Patras
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.database.database' );

class mod_splayInstallerScript
{
	/**
	 * method to install the module
	 *
	 * @return void
	 */
	public function install($parent)
	{

        //$parent is the class calling this method
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query = "CREATE PROCEDURE #__check() BEGIN DECLARE done INT DEFAULT FALSE; DECLARE article_id,article_hits INT; DECLARE cur1 CURSOR FOR SELECT ID,hits FROM #__content ORDER BY ID DESC; DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE; IF (SELECT last_check + timeframe FROM #__burstinfo) <= Now() THEN UPDATE #__burstinfo SET last_check = Now(); OPEN cur1; read_loop: LOOP FETCH cur1 INTO article_id,article_hits; IF done THEN LEAVE read_loop; END IF; CALL #__addupdate(article_id,0,article_hits); IF (SELECT hitsnew-hitsold FROM #__tree WHERE id = article_id) > (SELECT visits FROM #__burstinfo) THEN CALL #__node(article_id); UPDATE #__tree SET timeofburst=now() WHERE id=article_id; END IF; END LOOP; CLOSE cur1; DROP TABLE #__bursts; CREATE TABLE #__bursts( `id` int(11) NOT NULL, `position` int(11) NOT NULL DEFAULT 0,`title` varchar(255), PRIMARY KEY (`id`) )ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; IF (SELECT 1 FROM #__tree LIMIT 1) = 1 THEN SET @parent := (SELECT id FROM #__tree WHERE parent = 0) ; INSERT INTO #__bursts VALUES ( @parent , 5 , '' ); IF (SELECT COUNT(*) FROM #__tree) < 3 THEN SET @counter := (SELECT COUNT(*) FROM #__tree)-1 ; ELSE SET @counter := 4 ; END IF; SET @next_node := 5; WHILE (@next_node > 0 && (SELECT 1 FROM #__bursts WHERE position = @next_node) = 1) DO SET @parent := (SELECT id FROM #__bursts WHERE position = @next_node ); SET @leftchild := (SELECT l FROM #__tree WHERE id = @parent) ; SET @rightchild := (SELECT r FROM #__tree WHERE id = @parent) ; IF ( @leftchild <> 0 && @counter > 0 ) THEN INSERT INTO #__bursts VALUES ( @leftchild , @counter , '' ); SET @counter = @counter - 1; END IF; IF ( @rightchild <> 0 && @counter > 0 ) THEN INSERT INTO #__bursts VALUES ( @rightchild , @counter , '' ); SET @counter = @counter - 1; END IF; SET @next_node = @next_node - 1; END WHILE; END IF; DELETE FROM #__bursts WHERE id IN (SELECT id from #__content WHERE state=0 OR access<>1); UPDATE #__bursts INNER JOIN #__content USING (id) SET #__bursts.title = #__content.title; END IF; END";
        $db->setQuery( $query );
        $db->query();
        $query = "CREATE PROCEDURE #__addupdate(IN new_node INT,IN node_parent INT , IN hitsnew INT ) BEGIN IF (SELECT 1 FROM #__tree WHERE id = new_node) = 1 THEN UPDATE #__tree SET #__tree.hitsold=#__tree.hitsnew ,#__tree.hitsnew=hitsnew WHERE id=new_node ; ELSEIF (SELECT 1 FROM #__tree LIMIT 1) = 1 THEN IF node_parent=0 THEN SET @new_node_parent := (SELECT id FROM #__tree WHERE parent = 0) ; CALL #__addupdate(new_node,@new_node_parent, hitsnew ); ELSE SET @new_node_parentl := (SELECT l FROM #__tree WHERE id = node_parent) ; SET @new_node_parentr := (SELECT r FROM #__tree WHERE id = node_parent) ; IF new_node<node_parent THEN IF @new_node_parentl = 0 THEN INSERT INTO #__tree VALUES ( new_node ,0 ,0,node_parent, 0 ,hitsnew , NULL ); UPDATE #__tree SET l = new_node WHERE id = node_parent ; ELSE CALL #__addupdate(new_node,@new_node_parentl ,hitsnew ); END IF; ELSE IF @new_node_parentr = 0 THEN INSERT INTO #__tree VALUES ( new_node ,0 ,0,node_parent, 0 ,hitsnew , NULL ); UPDATE #__tree SET r = new_node WHERE id = node_parent ; ELSE CALL #__addupdate(new_node,@new_node_parentr, hitsnew ); END IF; END IF; END IF; ELSE INSERT INTO #__tree VALUES ( new_node ,0 ,0,0, 0 ,hitsnew , NULL); END IF; END";
        $db->setQuery( $query );
        $db->query();
        $query = "CREATE PROCEDURE #__node(IN node INT) BEGIN SET @node_parent := (SELECT parent FROM #__tree WHERE id = node) ; IF @node_parent <> 0 THEN SET @node_grandparent := ( SELECT parent FROM #__tree WHERE id = @node_parent ) ; IF @node_grandparent = 0 THEN CALL #__zig(node,@node_parent); ELSE SET @subtree_ancestor := ( SELECT parent FROM #__tree WHERE id = @node_grandparent ); IF node < @node_parent THEN IF @node_parent < @node_grandparent THEN CALL #__zigzig(node,@node_parent,@node_grandparent,'LEFT'); ELSE CALL #__zigzag(node,@node_parent,@node_grandparent,'LEFT'); END IF; ELSE IF @node_parent > @node_grandparent THEN CALL #__zigzig(node,@node_parent,@node_grandparent,'RIGHT'); ELSE CALL #__zigzag(node,@node_parent,@node_grandparent,'RIGHT'); END IF; END IF; IF @subtree_ancestor > @node_grandparent THEN UPDATE #__tree SET l=node WHERE id = @subtree_ancestor; UPDATE #__tree SET parent=@subtree_ancestor WHERE id = node ; CALL #__node(node); ELSEIF @subtree_ancestor <> 0 THEN UPDATE #__tree SET r=node WHERE id = @subtree_ancestor; UPDATE #__tree SET parent=@subtree_ancestor WHERE id = node ; CALL #__node(node); ELSE UPDATE #__tree SET parent=0 WHERE id = node ; END IF; END IF; END IF; END";
        $db->setQuery( $query );
        $db->query();
        $query = "CREATE PROCEDURE #__zig(IN node INT,IN node_parent INT) BEGIN IF node < node_parent THEN SET @node_parentleftr := (SELECT r FROM #__tree WHERE id = node) ; UPDATE #__tree SET l=@node_parentleftr,parent=node WHERE id = node_parent ; UPDATE #__tree SET r=node_parent,parent=0 WHERE id = node ; UPDATE #__tree SET parent = node_parent WHERE id = @node_parentleftr ; ELSE SET @node_parentrightl := (SELECT l FROM #__tree WHERE id = node) ; UPDATE #__tree SET r=@node_parentrightl,parent=node WHERE id = node_parent ; UPDATE #__tree SET l=node_parent,parent=0 WHERE id = node; UPDATE #__tree SET parent = node_parent WHERE id = @node_parentrightl ; END IF; END";
        $db->setQuery( $query );
        $db->query();
        $query = "CREATE PROCEDURE #__zigzag(IN node INT,IN node_parent INT , IN node_grandparent INT, IN direction VARCHAR(6)) BEGIN SET @node_r := (SELECT r FROM #__tree WHERE id = node) ; SET @node_l := (SELECT l FROM #__tree WHERE id = node) ; IF STRCMP( direction , 'LEFT')=0 THEN UPDATE #__tree SET l=@node_r,parent=node WHERE id = node_parent; UPDATE #__tree SET r=@node_l,parent=node WHERE id = node_grandparent; UPDATE #__tree SET r=node_parent,l=node_grandparent WHERE id = node; UPDATE #__tree SET parent=node_parent WHERE id = @node_r; UPDATE #__tree SET parent=node_grandparent WHERE id = @node_l; ELSE UPDATE #__tree SET r=@node_l,parent=node WHERE id = node_parent; UPDATE #__tree SET l=@node_r,parent=node WHERE id = node_grandparent; UPDATE #__tree SET l=node_parent,r=node_grandparent WHERE id = node; UPDATE #__tree SET parent=node_parent WHERE id = @node_l; UPDATE #__tree SET parent=node_grandparent WHERE id = @node_r; END IF; END";
        $db->setQuery( $query );
        $db->query();
        $query = "CREATE PROCEDURE #__zigzig(IN node INT,IN node_parent INT , IN node_grandparent INT, IN direction VARCHAR(6)) BEGIN IF STRCMP( direction , 'LEFT')=0 THEN SET @node_r := (SELECT r FROM #__tree WHERE id = node) ; SET @node_parent_r :=(SELECT r FROM #__tree WHERE id = node_parent) ; UPDATE #__tree SET l=@node_r,parent=node,r=node_grandparent WHERE id = node_parent; UPDATE #__tree SET r=node_parent WHERE id = node; UPDATE #__tree SET l=@node_parent_r,parent=node_parent WHERE id = node_grandparent; UPDATE #__tree SET parent=node_parent WHERE id=@node_r; UPDATE #__tree SET parent=node_grandparent WHERE id=@node_parent_r; ELSE SET @node_l := (SELECT l FROM #__tree WHERE id = node) ; SET @node_parent_l :=(SELECT l FROM #__tree WHERE id = node_parent) ; UPDATE #__tree SET r=@node_l,parent=node,l=node_grandparent WHERE id = node_parent; UPDATE #__tree SET l=node_parent WHERE id = node; UPDATE #__tree SET r=@node_parent_l,parent=node_parent WHERE id = node_grandparent; UPDATE #__tree SET parent=node_parent WHERE id=@node_l; UPDATE #__tree SET parent=node_grandparent WHERE id=@node_parent_l; END IF; END";
        $db->setQuery( $query );
        $db->query();
        $query = "CREATE TRIGGER #__info_updated AFTER UPDATE ON #__modules FOR EACH ROW BEGIN IF NEW.module = 'mod_splay' AND NEW.params <> OLD.params THEN SET @input := (SELECT params from #__modules WHERE #__modules.`module` = 'mod_splay'); SELECT REPLACE(@input, '}', '') INTO @input; SELECT REPLACE(@input, '{', '') INTO @input; SELECT REPLACE(@input, '\"', '') INTO @input; SET @minutes_temp := SUBSTRING_INDEX(SUBSTRING_INDEX(@input, 'burst_minutes', 2), 'burst_minutes', -1); SET @minutes_string := SUBSTRING_INDEX(SUBSTRING_INDEX(@minutes_temp, ',', 1), ',', -1); SET @minutes := REPLACE(@minutes_string, ':', ''); SET @hours_temp := SUBSTRING_INDEX(SUBSTRING_INDEX(@input, 'burst_hours', 2), 'burst_hours', -1); SET @hours_string := SUBSTRING_INDEX(SUBSTRING_INDEX(@hours_temp, ',', 1), ',', -1); SET @hours := REPLACE(@hours_string, ':', ''); SET @days_temp := SUBSTRING_INDEX(SUBSTRING_INDEX(@input, 'burst_days', 2), 'burst_days', -1); SET @days_string := SUBSTRING_INDEX(SUBSTRING_INDEX(@days_temp, ',', 1), ',', -1); SET @days := REPLACE(@days_string, ':', ''); SET @hits_temp := SUBSTRING_INDEX(SUBSTRING_INDEX(@input, 'burst_hits', 2), 'burst_hits', -1); SET @hits_string := SUBSTRING_INDEX(SUBSTRING_INDEX(@hits_temp, ',', 1), ',', -1); SET @hits := REPLACE(@hits_string, ':', ''); UPDATE #__burstinfo SET visits = @hits; UPDATE #__burstinfo SET timeframe = STR_TO_DATE(CONCAT(@minutes,'//',@hours,'//',@days,'//00//0000'), '%i//%H//%d//%m//%Y'); END IF; END";
        $db->setQuery( $query );
        $db->query();

	}

	/**
	 * method to uninstall the module
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// $parent is the class calling this method
	}
 	function update($parent)
	{
		// $parent is the class calling this method
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
	}

}