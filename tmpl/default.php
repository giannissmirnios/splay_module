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