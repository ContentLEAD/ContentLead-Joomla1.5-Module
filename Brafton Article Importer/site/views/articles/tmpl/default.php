<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="article-list">
	<?php foreach ($this->items as $row): ?>
		<div class="article-preview article-<?php echo $row->id ?>">
			<div class="article-title">
				<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id.':'.$row->title); ?>"> 
					<h1><?php echo $row->title;?></h1>
				</a>
			</div>
			<div class="article-excerpt">
				<p><?php echo $row->introtext; ?></p>
			</div>
		</div>
	<?php endforeach; ?>
</div> 