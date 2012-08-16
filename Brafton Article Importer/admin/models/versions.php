<?php
/*******************/
/** 1.5 FUNCTIONS **/
/*******************/
class Joomla15Functions {
    
    VERSION = '1.5';
    
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
                if (!$category->store()) {
                    JError::raiseError(500, $row->getError() );
                }
                
                $query = 'SELECT * FROM #__categories ORDER BY id DESC LIMIT 1';
                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $itemrow = $rows[0];

                $section = $this->enter_section();
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
}
/***********************/
/** END 1.5 FUNCTIONS **/
/***********************/

/********************/
/** 1.6+ Functions ***/
/********************/
class Joomla16Functions {

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
}
/***********************/
/** END 1.6+ FUNCTIONS **/
/***********************/