<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class Brafton2TableBrafton2 extends JTable
{
	function __construct(&$db) 
	{
		parent::__construct('#__categories', 'id', $db);
	}
}
