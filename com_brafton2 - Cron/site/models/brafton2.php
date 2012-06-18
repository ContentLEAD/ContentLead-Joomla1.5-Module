<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


jimport('joomla.application.menu');
jimport('joomla.database.table.category');

include_once 'ApiHandler.php';

class Brafton2ModelBrafton2 extends JModel{
	
	protected $status;

	
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
	
		
	public function enter_category($cat,$date){
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
			$itemrow = $rows[0];
			if(!empty($itemrow)){
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
				$category->store();
				
				$query = 'SELECT * FROM #__categories ORDER BY id DESC LIMIT 1';
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$itemrow = $rows[0];		

				$section = $this->enter_section();
				$query = 'INSERT INTO #__brafton_categories (id, section_id, brafton_cat_id) VALUES ('.$itemrow->id.','.$section.','.$cat_id.')';
				$db->setQuery($query);
				$result = $db->loadResult();
				
				
				return array("cat_id"=> $id, "section"=> $section);
			}
		}		
	}
				
	public function enter_section(){
		$db = & JFactory::getDBO();					
		$section = $this->setTableSection();

		$query = 'SELECT * FROM #__sections WHERE title = "News"';
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$itemrow = $rows[0];		

		if(empty($itemrow)){ //if 'News' section doesn't exist 							
			$section->title =  "News"; 
			$section->scope = "content";
			$section->image_position = "left";
			$section->description = "<p>News</p>";
			$section->published = 1;
														
			$query = 'SELECT * FROM #__sections ORDER BY ordering DESC LIMIT 1';
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$itemrow = $rows[0];		
			
			$section->ordering = $itemrow->ordering+1;												
			$section->alias = "News";
			$section->store();		

			$query = 'SELECT id FROM #__sections WHERE title = "News"';
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$itemrow = $rows[0];		
			return $itemrow->id;
		}
		else		
			return $itemrow->id;
	}
	
	public function post_exists($id){
		$db = & JFactory::getDBO();
		$query = "SELECT *"
			. " FROM #__brafton"
			. " WHERE brafton_id = $id"
			;			
			$db->setQuery($query);
			$rows = $db->loadObjectList();		
			$itemrow = $rows[0];		
			return $itemrow->brafton_id;
	}
	
	public function getXML(){		
		$db = & JFactory::getDBO();	
		$fh = new ApiHandler('eee83d24-906b-4736-91d9-1031621b85eb', "http://api.brafton.com");				
		$articles = $fh->getNewsHTML();				
		foreach ($articles as $a) {
			JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
			$content = $this->getTableContent();							
			$post_title = $a->getHeadline();
			$time = $a->getCreatedDate();
			$publish = $a->getPublishDate();
			$_content = $a->getText();
			$brafton_id = $a->getId();
			$photos = $a->getPhotos();
			$post_excerpt = $a->getExtract();
			$category = $a->getCategories();
//			$pic_in_intro = $_POST["braftonxml_images"];			
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

											
			$apost = $this->post_exists($brafton_id);		
			if(!$apost){				
				$post_date = date_parse($time);				
				$post_date = str_replace("T"," ", $time);
				$trim_title = trim($post_title);
			   $strip_pun = array ( "!", "@", "#", "$", "%","^", "&", ".", "'", "?", "-", "\"", "/", "*", "~", ",", "_", ":", "<", ">", ";", "+", "=", "(", ")");				
				$trim_title = str_replace($strip_pun,"",$trim_title); 
				$alias_ex = explode(" ", $trim_title);
				$alias = implode("-",$alias_ex);
											
				$catid = $this->enter_category($category,$post_date);				
																
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
					
					$content->introtext = '<img src="'.$pic_thumb.'" border="0" align="left" />'.$post_excerpt;					
					$content->fulltext = '<img src="'.$pic_URL.'" border="0" align="left" />'.$_content;
				}		
												
//				$content->introtext = $post_excerpt;															
				$content->title = $post_title;
				$content->alias = $alias;
				
				$content->publish_up = $publish;
				$content->attribs = $att;
				$content->state = 1;
				$content->catid = $catid['cat_id'];				
				$content->sectionid = $catid['section'];				
				$content->created = $post_date;
				$content->store();
			
				$query = 'SELECT * FROM #__content ORDER BY id DESC LIMIT 1';
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$itemrow = $rows[0];			
						
				$query = 'INSERT INTO #__brafton  (id, brafton_id) VALUES ("'.$itemrow->id.'","'.$brafton_id.'")';
				$db->setQuery($query);
				$result = $db->loadResult();									
			}
		}
		return "Import successful";							
		
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
}