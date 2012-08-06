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
	
	function setOptions()
	{
		$model = $this->getModel('keyinput');
		$model->setOptions();
		$this->setRedirect( 'index.php?option=com_braftonarticles', $msg );
	}
	
	/**
	 * Experimental feature...don't use me!
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('keyinput');
		if(!$model->delete()) {
			$msg = JText::_( 'Error Removing Articles' );
		} else {
			$msg = JText::_( 'Deleted' );
		}
	 
		$this->setRedirect( 'index.php?option=com_braftonarticles', $msg );
	}	
}