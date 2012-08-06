<?php
/*
	Brafton Article Importer
	controller.php
	Main site side controller, handles all view requests, as well as requests to load the articles
	PLEASE NOTE: The import code must be on the site side to use the cron job.  Putting it on the admin side won't work due to permissions
*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class BraftonArticlesController extends JController
{

	/*
		display() - REQUIRED FOR JOOMLA
		Function to control all the different view displays
		Best way to do it seems to be using a switch statement to flip between views
		Seems kind of odd but I guess it's mostly to prevent defaults from being used.
	*/
	function display($tpl = null)
	{
		// If no view is set, default to article view
		if ( ! JRequest::getCmd( 'view' ) ) {
			JRequest::setVar('view', 'articles' );
		}
		
		// Get the view and model for the 'articles' view
		if(JRequest::getCmd('view') == 'articles')
		{
			$view = & $this->getView( 'articles', 'html' );
			$view->setModel( $this->getModel( 'braftonarticles' ), true );
			$view->display();
		}
		parent::display();
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
		$model->getXML();
	}

	/**
	 * loads pictures
	 * @return void
	 */
	function loadPictures()
	{
		$model = $this->getModel('braftonarticles');
		if(!$model->loadpics()) {
			$msg = JText::_( 'Error Uploading Pics' );
		} else {
			$msg = JText::_( 'Pics successfull uploaded' );
		}
	 
		$this->setRedirect( 'index.php?option=com_braftonarticles', $msg );
	}
}
