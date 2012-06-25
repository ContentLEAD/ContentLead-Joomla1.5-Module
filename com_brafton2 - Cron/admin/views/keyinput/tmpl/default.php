<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_brafton2&controller=brafton2&view=brafton2" method="post" name="adminForm">
<table>
	<tr>
		<td><?php echo JHTML::tooltip('This is the key provided by Brafton/ContentLEAD which is used to import articles', 'API Key', '', '<b><u>API Key</u></b>'); ?><br />
			http://api.brafton.com/<input type="text" name="braftonxml_API_input" size=47 value="<?php echo $this->get_options(""); ?>"/> <br />
		</td>
  	</tr>
	<tr>
		<td>
		<?php echo JHTML::tooltip('Changing the author after some articles have been uploaded 
		will ONLY change the author for recently uploaded articles, not the older ones.  This is (currently) working as intended.', 'Consider the Following', '', '<b><u>Post Author</b></u>'); ?><br/>
		<p>Sets the post author for all imported entires</p>
			<select name="author">
				<?php $authors = $this->get_authors();
					  foreach($authors as $author): ?>
					  <option value="<?php echo $author->id; ?>"><?php echo $author->name; ?></option>
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