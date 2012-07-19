<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.component.controller');

class Brafton2Controller extends JController
{

	function display($tpl = null)
	{
		parent::display();
	}
	
	// do error checking here
	function loadArticles()
	{
		$model = $this->getModel('brafton2');
		$model->getXML();
	}

	/**
	 * loads pictures
	 * @return void
	 */
	function loadPictures()
	{
		$model = $this->getModel('brafton2');
		if(!$model->loadpics()) {
			$msg = JText::_( 'Error Uploading Pics' );
		} else {
			$msg = JText::_( 'Pics successfull uploaded' );
		}
	 
		$this->setRedirect( 'index.php?option=com_brafton2', $msg );
	}
}
