<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class BraftonArticlesController extends JController
{
	function __construct( $config = array())
	{
		parent::__construct( $config );
	}
	
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
		
	/*
		loadArticles() - REQUIRED FOR BRAFTON ARTICLE IMPORTER
		This grabs the model code and starts the import of the articles
		NOTE: This is just the articles, for pictures see below.
		TODO: Error checking. possible redirect?
	*/
	function loadArticles()
	{
		$model = $this->getModel('braftonarticles');
		if(!$model->getXML()) {
			return false;
		} else {
			return true;
		}
	}
	
	function loadPictures()
	{
		$model = $this->getModel('braftonarticles');
		if(!$model->loadpics()) {
			return false;
		} else {
			return true;
		}
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