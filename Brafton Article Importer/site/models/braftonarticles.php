<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');


class BraftonArticlesModelBraftonArticles extends JModel
{	
	var $_data;
	
	function getData(){    
		
		$query = 'SELECT #__content.id,#__content.title,#__content.alias,#__content.introtext  FROM #__content,#__brafton WHERE #__content.id = #__brafton.id';		
		$this->_data = $this->_getList( $query );		
		return $this->_data;
	}
		function getItems() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
	
		return $itemrow;
	}
}
