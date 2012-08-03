<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport( 'joomla.database.table.category' );
jimport('joomla.application.component.view');
 

class BraftonArticlesViewKeyInput extends JView
{
	function display($tpl = null) 
	{
		JToolBarHelper::title(   JText::_( 'Brafton Article Importer' ), 'generic.png' );
		$this->status = $this->get('API');
		$this->status = JRequest::getVar('braftonxml_API_input');
		parent::display($tpl);
	}

	function get_options($name){
		//$name = "braf_api_key";
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