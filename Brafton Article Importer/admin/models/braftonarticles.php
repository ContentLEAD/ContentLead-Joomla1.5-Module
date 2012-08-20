<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.menu');
jimport('joomla.database.table.category');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
error_reporting(E_ALL);
ini_set("display_errors", 1);

$params =& JComponentHelper::getParams('com_media');
$path = "file_path";
define('COM_MEDIA_BASE', JPath::clean(JPATH_ROOT.DS.$params->get($path, 'images'.DS.'stories')));

include_once 'ApiHandler.php';

class BraftonArticlesModelBraftonArticles extends JModel{

	var $_data;
	protected $status;
	const _TIME = 0;
	
	public function getTable($type = 'Brafton2', $prefix = 'Brafton2Table', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getTableContent($type = 'JoomContent', $prefix = 'JoomContentTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function setTableSection($type = 'JoomSection', $prefix = 'JoomSectionTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function getData(){    
		
		$query = 'SELECT #__content.id,#__content.title,#__content.alias,#__content.introtext  FROM #__content,#__brafton WHERE #__content.id = #__brafton.id';		
		$this->_data = $this->_getList( $query );
		return $this->_data;
	}
	
	public function setrgt($initialrgt) {
		$db = & JFactory::getDBO();
		$query = 'UPDATE #__categories SET lft = lft +2, rgt = rgt +2 WHERE rgt > '.$initialrgt.' AND NOT id=1';
		$db->setQuery($query);
		$result = $db->loadResult();
		$query = 'UPDATE #__categories SET rgt = rgt +2 WHERE id=1';
		$db->setQuery($query);
		$result = $db->loadResult();
	}

	public function getrgt() {
		$db = & JFactory::getDBO();
		$query = 'select rgt from #__categories where id=1';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$rootrow = $rows[0];
		$initialrgt = $rootrow->rgt;
		return $initialrgt;
	}

	public function enter_category_25($cat,$date) {
		$db = & JFactory::getDBO();
		foreach($cat as $catitem) {
			$cat_id = $catitem->getId();
			$category = $this->getTable();
			$query = "SELECT * FROM #__brafton_categories WHERE brafton_cat_id = $cat_id";
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$itemrow = $rows[0];
			if(!empty($itemrow)) {
			// When a category is already in the database...
			// lft of cat in assets table stays the same
			// rgt should represent all articles between the category's lft and rft
			// I want to do this without directly editing the assets table...is it possible?
				$rgt = $this->getrgt();
				$query = "UPDATE #__assets SET rgt = " . $rgt . " WHERE id=".$cat_id;
				return array("cat_id"=>$itemrow->id, "section"=>0);
			} else {
				$rgt = $this->getrgt();

				$catalias = explode(" ", $catitem->getName());
				$catalias = implode("-",$catalias);

				$category->title = $catitem->getName(); 
				$category->lft = $rgt;
				$category->rgt = $rgt+1;
				$category->extension = "com_content";
				$category->alias = $catalias; 
				$category->description = "<p>".$catitem->getName()."<p>";
				$category->path = $catitem->getName();
				$category->published = 1;
				$category->access = 1; 
				$category->language = "en-GB";
				$category->level = 1; 
				$category->parent_id = 1; 
				$category->params = '{"target":"","image":""}'; 
				$category->metadata = '{"page_title":"","author":"","robots":""}'; 
				$category->created_user_id = 42;
				$this->setrgt($rgt);
				$category->store();

				$query = 'SELECT * FROM #__categories ORDER BY id DESC LIMIT 1';
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$itemrow = $rows[0];

				$query = 'INSERT INTO #__brafton_categories (id, brafton_cat_id) VALUES ('.$itemrow->id.','.$cat_id.')';
				$db->setQuery($query);
				$result = $db->loadResult();

				return array("cat_id"=>$itemrow->id, "section"=>0);
			}
		}
	}
	/*******************/
	/** 1.5 FUNCTIONS **/
	/*******************/
	public function enter_category($cat,$date,$section){
		$db = & JFactory::getDBO();
		foreach($cat as $catitem){
			$cat_id = $catitem->getId();
			$category = $this->getTable();

			$query = "SELECT *"
			. " FROM #__brafton_categories"
			. " WHERE brafton_cat_id = $cat_id"
			;	
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if(!empty($rows)){
				$itemrow = $rows[0];
				return array("cat_id"=>$itemrow->id, "section"=>$itemrow->section_id);
			}
			else{			
				$catalias = explode(" ", $catitem->getName());
				$catalias = implode("-",$catalias);
				
				$query = 'SELECT * FROM #__categories ORDER BY ordering DESC LIMIT 1';
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$itemrow = $rows[0];
				$lastorder = $itemrow->ordering;
				
				$query = "SELECT id"
				. " FROM #__sections"
				. " WHERE title = 'News'"
				;	
				$db->setQuery($query);
				$rows = $db->loadObjectList();		
				$itemrow = $rows[0];

				$category->title = $catitem->getName(); 			
				$category->alias = $catalias; 
				$category->section = $itemrow->id;
				$category->description = "<p>".$catitem->getName()."<p>";
				$category->image_position = "left";
				$category->published = 1;
				$category->ordering = $lastorder +1;
				$category->access = 0; 
				if (!$category->store()) {
					JError::raiseError(500, $row->getError() );
				}
				
				$query = 'SELECT * FROM #__categories ORDER BY id DESC LIMIT 1';
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$itemrow = $rows[0];

				$query = 'INSERT INTO #__brafton_categories (id, section_id, brafton_cat_id) VALUES ('.$itemrow->id.','.$section.','.$cat_id.')';
				$db->setQuery($query);
				$db->query();
				return array("cat_id"=> $itemrow->id, "section"=> $section);
			}
		}		
	}
	
	public function enter_section(){
		$db = & JFactory::getDBO();					
		$section = $this->setTableSection();

		$query = 'SELECT * FROM #__sections WHERE title = "News"';
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if(empty($rows)){ //if 'News' section doesn't exist 
			$section->title =  "News"; 
			$section->scope = "content";
			$section->image_position = "left";
			$section->description = "<p>News</p>";
			$section->published = 1;

			$query = 'SELECT * FROM #__sections ORDER BY ordering DESC LIMIT 1';
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$news_ordering = 0;
			if(!empty($rows)) {
				$itemrow = $rows[0];
				$news_ordering = $itemrow->ordering;
			}
			$section->ordering = $news_ordering + 1;
			$section->alias = "News";
			$section->store();		

			$query = 'SELECT id FROM #__sections WHERE title = "News"';
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$itemrow = $rows[0];		
			return $itemrow->id;
		}
		else {
			$itemrow = $rows[0];		
			return $itemrow->id;
		}
	}
	/***********************/
	/** END 1.5 FUNCTIONS **/
	/***********************/
	
	public function post_exists($id){
		$db = & JFactory::getDBO();
		$query = "SELECT *"
			. " FROM #__brafton"
			. " WHERE brafton_id = $id"
			;			
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if(empty($rows))
				return false;
			else
				return true;
	}
	
	public function pic_exists($id){
		$db = & JFactory::getDBO();
		$query = "SELECT *"
			. " FROM #__brafton_pics"
			. " WHERE brafton_id = $id"
			;
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if(empty($rows))
				return false;
			else
				return true;
	}
	/*************************************/
	/** This is where the magic happens **/
	/*************************************/
	public function getXML(){
		if(self::_TIME)
		{
			// Let's test the speed
			$time_start = microtime(true);
			echo "Starting article importer at " . $time_start . "<br>";
		}
		$version = new JVersion();
		$API_pattern = "^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$";
		$db = & JFactory::getDBO();	
		$feed_exists = $this->get_options("braf_api_key");
		if($feed_exists && preg_match('/' . $API_pattern . '/', $feed_exists))
			$API_KEY = $feed_exists;
		else
			return JText::_( 'Invalid API key.  Please double check the admin panel!' );
		
		$fh = new ApiHandler($API_KEY, "http://api.brafton.com");
		$articles = $fh->getNewsHTML();
		foreach ($articles as $a) {
		if(self::_TIME)
		{
			$article_start = microtime(true);
			echo "Starting import of article " . $brafton_id . " at " . $article_start . "<br>";
		}
		$brafton_id = $a->getId();
		$apost = $this->post_exists($brafton_id);
			if(!$apost)
			{
				JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_braftonarticles'.DS.'tables');
				$content = $this->getTableContent();
				$post_title = $a->getHeadline();
				$time = $a->getCreatedDate();
				$publish = $a->getPublishDate();
				$_content = $a->getText();
				$photos = $a->getPhotos();
				$post_excerpt = $a->getExtract();
				$category = $a->getCategories();
				$att = "show_title=1 \n".
					   "link_titles=1 \n".
					   "show_intro=0 \n".
					   "show_section=1 \n".
					   "link_section=1 \n".
					   "show_category=1 \n".
					   "link_category=1 \n".
					   "show_vote=0 \n".
					   "show_author=0 \n".
					   "show_create_date=1 \n".
					   "show_modify_date=0 \n".
					   "show_pdf_icon=0 \n".
					   "show_print_icon=0 \n".
					   "show_email_icon=0 \n".
					   "language= \n".
					   "keyref= \n".
					   "readmore= ";

					$post_date = date_parse($time);				
					$post_date = str_replace("T"," ", $time);
					$trim_title = trim($post_title);
					$strip_pun = array ( "!", "@", "#", "$", "%","^", "&", ".", "'", "?", "-", "\"", "/", "*", "~", ",", "_", ":", "<", ">", ";", "+", "=", "(", ")");				
					$trim_title = str_replace($strip_pun,"",$trim_title); 
					$alias_ex = explode(" ", $trim_title);
					$alias = implode("-",$alias_ex);
					
					if($version->getShortVersion() < '1.6')
					{
						$section = $this->enter_section();
						$catid = $this->enter_category($category, $post_date, $section);
					}
					else
						$catid = $this->enter_category_25($category,$post_date);

					if(empty($photos)){
						$content->fulltext = $_content;
						$content->introtext = $post_excerpt;
					}
					else{
						if(($photos[0]->getMedium()->getURL() != "NULL")){
							$pic_thumb = $photos[0]->getSmall()->getURL();
							$pic_URL = $photos[0]->getMedium()->getURL();
						}
						else{
							$pic_thumb = $photos[0]->getSmall()->getURL();
							$pic_URL = $photos[0]->getLarge()->getURL();
						}
						$pic_base = basename($pic_URL);
						// Strip random numbers off of pictures
						$firstPlace = strpos($pic_base, "_", 0);
						$lastPlace = strrpos($pic_base, ".");
						$pic_base = substr_replace($pic_base, '', $firstPlace - 1, $lastPlace - $firstPlace + 1);
						
						$destination_folder = 'images'. DS;
						$picURL = $destination_folder . $pic_base;
						
						if(!is_null($pic_thumb))
							$img_thumb = '<img class="article-thumbnail" style="width:100px; height:auto;" src="'.$picURL.'" border="0" align="left" />';
						if(!is_null($picURL))
							$imgURL = '<img class="article-image" src="'.$picURL.'" border="0" align="left" />';
						
						$content->introtext = $img_thumb.$post_excerpt;
						$content->fulltext = $imgURL.'<div class="post-content">'.$_content.'</div>';
					}		
													
	//				$content->introtext = $post_excerpt;															
					$content->title = $post_title;
					$content->alias = $alias;
					if($version->getShortVersion() > '1.6')
						$content->language = "en-GB";
					$content->publish_up = $publish;
					$content->attribs = $att;
					$content->state = 1;
					$content->catid = $catid['cat_id'];				
					$content->sectionid = $catid['section'];				
					$content->created = $post_date;
					$content->created_by = $this->get_options("author");
					$content->store();
				
					$query = 'SELECT * FROM #__content ORDER BY id DESC LIMIT 1';
					$db->setQuery($query);
					$rows = $db->loadObjectList();
					$itemrow = $rows[0];			
							
					$query = 'INSERT INTO #__brafton  (id, brafton_id) VALUES ("'.$itemrow->id.'","'.$brafton_id.'")';
					$db->setQuery($query);
					$db->query();									
			}
			if(self::_TIME)
			{
				$article_end = microtime(true);
				$article_time = $article_end - $article_start;
				echo "Importing article " . $brafton_id . " took " . $article_time . "<br><br>";
			}
		}
		if(self::_TIME)
		{
			$time_end = microtime(true);
			$total_time = $time_end - $time_start;
			echo "Total article import time: " .  $total_time . "<br>";
		}
		return "Import successful";							
	}
	/******************/
	/** END getXML() **/
	/******************/
	
	function set_braf_options($name, $value){
		$db = & JFactory::getDBO();
		$query = 'INSERT INTO #__brafton_options  (options_name, options_value) VALUES ("'.$name.'","'.$value.'")';
		$db->setQuery($query);
		$db->query();									
	}
	
	function check_set_feed($key){
		$db = & JFactory::getDBO();
		$query = 'UPDATE #__brafton_options '.
					 'SET options_value = "'.$key.'"'.
					 'WHERE options_name = "braf_api_key"';
		$db->setQuery($query);
		$db->query();							
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
	
	function loadpics() 
	{
		if(self::_TIME)
		{
			$time_start = microtime(true);
			echo "Starting photo importer at " . $time_start . "<br>";
		}
		$API_pattern = "^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$";
		$db = & JFactory::getDBO();	
		$feed_exists = $this->get_options("braf_api_key");
		if($feed_exists && preg_match('/' . $API_pattern . '/', $feed_exists))
			$API_KEY = $feed_exists;
		else
			return JText::_( 'Invalid API key.  Please double check the admin panel!' );
			
		$fh = new ApiHandler($API_KEY, "http://api.brafton.com");				
		$articles = $fh->getNewsHTML();				
		foreach ($articles as $a)
		{
			if(self::_TIME)
			{
				$photo_start = microtime(true);
				echo "Starting import of photo " . $brafton_id . " at " . $photo_start . "<br>";
			}
			JTable::addIncludePath('JPATH_COMPONENT'.DS.'tables');	
			$brafton_id = $a->getId();
			$apost = $this->pic_exists($brafton_id);	
			if(!$apost)
			{
				$photos = $a->getPhotos();	
				if(empty($photos)) 
				{
					// No Photos Found
				}
				else 
				{
					if(($photos[0]->getMedium()->getURL() != "NULL"))
					{
						$pic_thumb = $photos[0]->getSmall()->getURL();
						$pic_URL = $photos[0]->getMedium()->getURL();												
					}
					else
					{
						$pic_thumb = $photos[0]->getSmall()->getURL();
						$pic_URL = $photos[0]->getLarge()->getURL();						
					}		
					$pic_base = basename($pic_URL);
					
					// Strip random numbers off of pictures
					$firstPlace = strpos($pic_base, "_", 0);
					$lastPlace = strrpos($pic_base, ".");
					$pic_base = substr_replace($pic_base, '', $firstPlace - 1, $lastPlace - $firstPlace + 1);
							
					$destination_folder = COM_MEDIA_BASE . DS . $pic_base;
					
					if(!copy($pic_URL, $destination_folder))
						echo "failed to copy $pic_URL";
					else
					{
						$query = 'INSERT INTO #__brafton_pics (brafton_id) VALUES ("' . $brafton_id . '")';
						$db->setQuery($query);
						$db->query();
					}
				} //end else
			} // end if
			// Timing debugging
			if(self::_TIME)
			{
				$photo_end = microtime(true);
				$photo_time = $photo_end - $photo_start;
				echo "Importing photo " . $brafton_id . " took " . $photo_time . "<br><br>";
			}
		} //end foreach 
		// Timing debugging
		if(self::_TIME)
		{
			$time_end = microtime(true);
			$time = $time_end - $time_start;
			echo "Total photo import time: " .  $time . "<br>";
		}
			return "Import successful";							
	} //end loadpics()
} // end class