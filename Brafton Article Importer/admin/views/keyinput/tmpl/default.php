<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_braftonarticles&controller=braftonarticles&task=setOptions" method="post" name="adminForm">
<table>
	<tr>
	<td>Hover over a title for futher information on that field.</td>
	</tr>
	<tr>
		<td><?php echo JHTML::tooltip('This is the key provided by Brafton/ContentLEAD which is used to import articles', 'API Key', '', '<h2 class=admin-header>API Key</h2>'); ?>
			http://api.brafton.com/<input type="text" name="braftonxml_API_input" size=47 value="<?php echo $this->get_options("braf_api_key"); ?>"/> <br />
		</td>
  	</tr>
	<tr>
		<td>
		<?php echo JHTML::tooltip('Changing the author after some articles have been uploaded 
		will ONLY change the author for recently uploaded articles, not the older ones.  This is (currently) working as intended.', 'Consider the Following', '', '<h2 class=admin-header>Post Author</h2>'); ?>
		Sets the post author for all imported entires<br/><br/>
			<select name="author">
				<?php $authors = $this->get_authors();
					  foreach($authors as $author): ?>
					   <option 
					  <?php if($this->get_options('author') == $author->id): ?>
					  selected="selected"
 					  <?php endif; ?>
					  value="<?php echo $author->id; ?>"><?php echo $author->name; ?></option>
				<?php endforeach; ?>	  
			</select>
		</td>
	<tr>
		<td>
			<br /><input type="submit" name="braftonxml_API_submit" id="braftonxml_API_submit" value="Submit" />
		</td>
	</tr>
</table>
</form>
<!--
<form action="index.php?option=com_brafton2&controller=brafton2&task=remove" method="post">
<input type="submit" value="Delete all brafton entries?"/>
</form>-->