<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');


class JoomContentTableJoomContent extends JTable
{

	function __construct(&$db) 
	{
		parent::__construct('#__content', 'id', $db);
	}
}
