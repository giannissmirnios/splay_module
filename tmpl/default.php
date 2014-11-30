/**
 * @package Module mod_splay for Joomla! 3.3 and Joomla! 2.5
 * @version 1.0: mod_splay.php 599 2014-12-1 12:00:00
 * @author Ioannis Smirnios
 * @copyright (C) 2014- University of Patras
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h3>&nbsp;&nbsp;Trending articles</h3>
<div class="splay-container">
<?php
foreach ($items as $item){
?>
	<div class="splay-popular-article <?php echo $params->get('articles_orientation') ?>">
		<a href="index.php?option=com_content&view=article&id=<?php echo $item->id; ?>">
			<?php echo $item->title; ?></a>
	</div>
<?php	
	}
?>
</div>