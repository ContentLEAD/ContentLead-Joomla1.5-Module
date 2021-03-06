<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

require_once( JPATH_COMPONENT.DS.'controller.php' );

if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}
$classname	= 'BraftonArticlesController'.$controller;
$controller	= new $classname( );

$controller->execute( JRequest::getVar( 'task' ) );

//$controller->redirect();