<?php
/*
 * Created on 14-May-2013
 *
 * @author antz(ananthesh@unbxd.com)
 */
 
 class Unbxd_Datafeeder_Helper_UnbxdIndexingHelper {
 	// This is like to act as a temporary cache, which holds the fieldName to fieldType information
 	// so that just to avoid multiple database reads, and make it faster
	var $fieldType = array();
	// This is the name of the logFile which it is writing in to//
	var $logFile;
	// This is also act like a temporary cache, which holds the category id to category information,
	// so that just to avoid multiple database reads, and make it faster
	var $categoryMap = array();
	// size to fetch how much products should it pick in batches
	var $PAGE_SIZE = 500;
	// the file to write to..
	var $file;
	// fields to be selected to push to unbxd
	var $fields;
	// Feed unlock interval
	//LOCK_TIMEOUT = 60 * 30;

     /**
      * method to get all the attributes
      **/
     public function getAttributes(){
         return Mage::getSingleton('eav/config')
             ->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getAttributeCollection();
     }

	public function __construct(){
		$this->logFile = Mage::getBaseDir('log').DS.'generic.log';
		$this->file =  Mage::getBaseDir('tmp').DS.'unbxdFeed.xml';
		$this-log("calling setfeilds method");
		$this->fields = array();
		$this-> setFieldType();
	}

	public function setFields($site) {
		$this->fields = Mage::getResourceSingleton('datafeeder/field')->getEnabledFields($site);
		$this-log("inside setfeilds method");
		$this->fields[] = 'entity_id';
		$this->log("fields are " .json_encode($this->fields));
	}

 	/**
 	 * Function to appened the contents to the file
 	 */
 	public function log($content){
 		try{
 			$resp = file_put_contents($this->logFile, date('Y-m-d H:i:s').$content."\n", FILE_APPEND);
 			if($resp){
 				return true;
 			} else {
 				error_log("UNBXD_MODULE:Error while appending the contents to log file");
 				return false;
 			}
 			return true;
 		}catch(Exception $ex) {
 			error_log("UNBXD_MODULE:Error while appending the contents to log file");
 			Mage::throwException($ex->getMessage());
 			return false;
 		}
 	}

 	/*
 	* function to check whether the field is a multiSelect/select or not, 
 	* This is optimized method, where it doesn't make a database call to get fieldType 
 	* where it fetches from the local variable, which holds the information of field to fieldType mapping
 	*/
    public function isMultiSelect($attributeName = ""){
	    	if($attributeName == "status" || $attributeName == "visibility") {
	    		return false;
	    	}
		if($this->getFieldType($attributeName) == "select" || $this->getFieldType($attributeName) == "multiselect" || $attributeName == "categoryIds"){
			return true;
		}
		return false;
    }


    public function isImage($attributeName = "") {
    	if($this->getFieldType($attributeName) == "media_image") {
    		return true;
    	}
    	return false;
    }
	
 	/**
 	* function to get Category from the category id, 
 	* This checks it present in the global array 'categoryMap', if it is not there fetches from db
 	* So that once it gets one category, it doesn't make db call again for the same category
 	*/
    public function getCategory($category_id = ""){
		if(!isset($this->categoryMap[$category_id])){
			$category = Mage::getModel('catalog/category')->load($category_id);
			$this->categoryMap[$category_id] = $category;
			return $this->categoryMap[$category_id];
		}
		return $this->categoryMap[$category_id];
    }

     public function getCategoryOnLevel($category_ids, $level) {
         if(!is_array($category_ids)) {
             return array();
         }
         $categoryValues = array();
         foreach($category_ids as $category_id) {
             $category = $this->getCategory($category_id);
             $parentIds = $category->getParentIds();
             if(!is_null($category) && $category->getLevel() == $level + 1) {
                $categoryValues = array_merge($categoryValues, array($category->getName()));
             } else if ($category instanceof Mage_Catalog_Model_Category &&
                 is_array($parentIds) &&
                 (sizeof($parentIds) >0)) {
                 $categoryValues = array_merge($categoryValues, $this->getCategoryOnLevel($parentIds, $level));
             }
         }
         return array_unique($categoryValues);
     }

 	/**
 	* method to get field type of the field
 	**/
	private function getFieldType($attributeName){
		if(array_key_exists( $attributeName, $this->fieldType)){
			return $this->fieldType[$attributeName]; 
		} else {
			return "text";
		}
	}
	
	/**
 	* method to set field type to the global object
 	**/
	private function setFieldType(){
		$attributes = $this->getAttributes();
		foreach($attributes as $attribute){
			$this->filterable[$attribute->getAttributeCode()] = $attribute->getData('is_filterable');
			$this->fieldType[$attribute->getAttributeCode()] = $attribute-> getFrontendInput();
		}
	}
 }
?>	

