<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">    
    <?php    
    foreach ($this->items as &$row)
    { ?>
            <tr>
			<td>
			<a href="<?php echo JRoute::_('index.php?option=com_content&view=article&id='.$row->id); ?>">
                <?php echo "<h1>".$row->title."</h1>"; ?>
			</a>	
            </td>           
        </tr>
		<tr>
			<td>
			<?php echo $row->introtext; ?>
			</td>
		</tr>	
        <?php        
    }
    ?>
    </table>
</div> 
</form>
