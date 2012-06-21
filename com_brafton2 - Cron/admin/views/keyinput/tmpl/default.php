<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_brafton2&controller=brafton2&view=brafton2" method="post" name="adminForm">
<table>
  <tr>
     <td>
<b><u>API Key</u></b><br />
http://api.brafton.com/<input type="text" name="braftonxml_API_input" size=32 value="<?php echo $this->get_options(""); ?>"/> <br />
     </td>
  	</tr>
	<tr>
	 <td>
<br />	 
<!-- Include thumbnails next to excerpt? <br />
Yes <input type="radio" name="braftonxml_images" value="yes" /> <br />                
No &nbsp;<input type="radio" name="braftonxml_images" value="no" /> <br />
<br/>
!-->
<input type="submit" name="braftonxml_API_submit" id="braftonxml_API_submit" value="Submit" />

</td>
</tr>
</table>
</form>
<form action="index.php?option=com_brafton2&controller=brafton2&task=remove" method="post">
<input type="submit" value="Delete all brafton entries?"/>
</form>