<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport( 'joomla.database.table.category' );
jimport('joomla.application.component.view');
 

class Brafton2ViewBrafton2 extends JView
{
	function display($tpl = null) 
	{
		$items =& $this->get( 'Data');
 
        $this->assignRef( 'items', $items );
 
		parent::display($tpl);	
		$this->setDocument();
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('News'));
	}
}