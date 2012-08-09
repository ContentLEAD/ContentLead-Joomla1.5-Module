<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport( 'joomla.database.table.category' );
jimport('joomla.application.component.view');
 

class BraftonArticlesViewKeyInput extends JView
{
	protected $status; 
	
	function display($tpl = null) 
	{
		JToolBarHelper::title('Brafton Article Importer','logo');
		JHtml::stylesheet('com_braftonarticles/css/admin/style.css', 'media/');
		parent::display($tpl);
	}

	function get_options($name){
		$db = & JFactory::getDBO();
		$query = 'SELECT * FROM #__brafton_options  WHERE options_name = "'.$name.'"';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$itemrow = $rows[0]->options_value;
		return $itemrow;
	}
	
	function get_authors()
	{
		$db = & JFactory::getDBO();	
		$query = 'SELECT name, id FROM #__users';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
}