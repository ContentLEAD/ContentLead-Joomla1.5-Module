<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class BraftonArticlesController extends JController
{

	function display($cachable = false) 
	{
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view','KeyInput'));

		parent::display($cachable);
	}
	
	/*function author()
	{
		$model = $this->getModel('brafton2');
		if(!$model->delete()) {
			$msg = JText::_( 'Error Removing Articles' );
		} else {
			$msg = JText::_( 'Deleted' );
		}
	 
		$this->setRedirect( 'index.php?option=com_brafton2', $msg );
	}*/
	
	
	
	
	/**
	 * Experimental feature...don't use me!
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('braftonarticles');
		if(!$model->delete()) {
			$msg = JText::_( 'Error Removing Articles' );
		} else {
			$msg = JText::_( 'Deleted' );
		}
	 
		$this->setRedirect( 'index.php?option=com_braftonarticles', $msg );
	}
	
}

