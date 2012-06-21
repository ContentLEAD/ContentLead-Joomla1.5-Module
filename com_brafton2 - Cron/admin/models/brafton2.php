<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.menu');
jimport('joomla.database.table.category');

include_once 'ApiHandler.php';

class Brafton2ModelBrafton2 extends JModel{
	
	protected $status;

	public function getAPI(){	
		//echo 'called';
		$db = & JFactory::getDBO();
		$API_KEY = $_POST["braftonxml_API_input"];			
		$feed_exists = $this->get_options("braf_api_key");			
		if($API_KEY){
			if(empty($feed_exists))
				$this->set_braf_options("braf_api_key", $API_KEY);
			else
				$this->check_set_feed($API_KEY);
		}
		else{
			if($feed_exists)
				$API_KEY = $feed_exists;
			else{
				echo "Please enter valid API key";
				return;
			}
		}
		
	}
	
	function set_braf_options($name, $value){
		$db = & JFactory::getDBO();
		$query = 'INSERT INTO #__brafton_options  (options_name, options_value) VALUES ("'.$name.'","'.$value.'")';
		$db->setQuery($query);
		$result = $db->loadResult();									
	}
	
	function check_set_feed($key){
		$db = & JFactory::getDBO();
		$query = 'UPDATE #__brafton_options '.
					 'SET options_value = "'.$key.'"'.
					 'WHERE options_name = "braf_api_key"';
			$db->setQuery($query);
			$result = $db->loadResult();									
	}
	
	function get_options($name){
		$name = "braf_api_key";
		$db = & JFactory::getDBO();
		$query = 'SELECT * FROM #__brafton_options  WHERE options_name = "'.$name.'"';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$itemrow = $rows[0]->options_value;
		return $itemrow;
	}	
	
	function delete(){
		$db = & JFactory::getDBO();
		$query = "DELETE jos_content FROM `jos_content` JOIN jos_brafton WHERE jos_content.id = jos_brafton.id";
		$db->setQuery($query);
		$result = $db->query();
		if($result)
			return true;
		else
			return false;
	}
	
}