<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.menu');
jimport('joomla.database.table.category');

include_once 'ApiHandler.php';

class Brafton2ModelBrafton2 extends JModel{
	
	protected $status;
	
	/*	getAPI()
	 *	Misnomer function name for Joomla! compatibility
	 *  I don't the weird name is necessary, but I'm trying to preserve legacy code so stuff doesn't break
	 *  Anyway, this function SETS the API key into the database, which is necessary for the component to use
	 *
	 *  PRE: $_POST['braftonxml_API_input'] is the user input from admin panel (see /admin/views/keyinput/tmpl/default.php)
	 *  POST: Sets the API key into the database (table #__brafton_options)
	 */
	
	public function getAPI(){	
		$API_pattern = "^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$";		// pattern for regex check
		$db = & JFactory::getDBO();
		$options = JRequest::get('post');
		$API_KEY = $options["braftonxml_API_input"];
		$author = $options["author"];
		$author_exists = $this->get_options("author");
		$feed_exists = $this->get_options("braf_api_key");		// check to see if there's a key already set?
		if($API_KEY && preg_match('/' . $API_pattern . '/', $API_KEY)){
			if(empty($feed_exists))
				$this->set_braf_options("braf_api_key", $API_KEY);
			else
				$this->check_set_feed("braf_api_key", $API_KEY);
		} //end if
		else
		{
			echo "Please enter valid API key";
			return;
		} //end else
		
		if($author)
		{
			if(empty($author_exists))
				$this->set_braf_options("author", $author);
			else
				$this->check_set_feed("author", $author);
		}
		else
			echo "problem entering author";
		
		echo "Ready for cron";
	}
	
	function set_braf_options($name, $value){
		$db = & JFactory::getDBO();
		$query = 'INSERT INTO #__brafton_options  (options_name, options_value) VALUES ("'.$name.'","'.$value.'")';
		$db->setQuery($query);
		$result = $db->loadResult();									
	}
	
	function check_set_feed($option, $key){
		$db = & JFactory::getDBO();
		$query = 'UPDATE #__brafton_options '.
					 'SET options_value = "'.$key.'"'.
					 'WHERE options_name = "'. $option .'"';
			$db->setQuery($query);
			$result = $db->loadResult();									
	}
	
	function get_options($name){
		//$name = "braf_api_key";
		$db = & JFactory::getDBO();
		$query = 'SELECT * FROM #__brafton_options  WHERE options_name = "'.$name.'"';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$itemrow = $rows[0]->options_value;
		return $itemrow;
	}	
	
	/*	delete()
	 *	Removes all articles imported from Brafton. Mostly for debugging.
	 *  PRE: N/A
	 *  POST: Removes all Brafton articles
	 *  TODO: Remove #__brafton enties.
	 *  TODO: Possible debug mode?
	 */
	function delete(){
		$db = & JFactory::getDBO();
		$query = "DELETE jos_content FROM `jos_content` JOIN jos_brafton WHERE jos_content.id = jos_brafton.id";
		$db->setQuery($query);
		$result = $db->query();
		if($result)
			return true;
		else
			return false;
	} //end delete()
	
}