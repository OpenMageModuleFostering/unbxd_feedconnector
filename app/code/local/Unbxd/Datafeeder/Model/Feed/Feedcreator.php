<?php

class Unbxd_Datafeeder_Model_Feed_Feedcreator {

	var $fileName;

	var $fields;

	const STATUS = 'status';
	const DATA_TYPE = 'data_type';
	const AUTOSUGGEST = 'autosuggest';


	const PAGE_SIZE = 500;
	public function __construct(){
	}

	public function init($site, $fileName) {
		Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->setLog(str_replace(' ', '_',$site)."_Datafeeder.log");
        $this->setFields($site);
        $this->fileName = $fileName;
	}


	/**
 	* method to create the feed
 	**/
 	public function createFeed($fileName, $fromdate,$todate,$site,$operation,$ids){
 		$this->init($site, $fileName);		
 		if($this->createFile()){
 			$this->log("started writing header");
 			
 			if(!$this->writeFeedContent($fromdate,$todate,$site,$operation,$ids)){
 				return false;
 			}
 			
 		} else {
 			return false;
 		}
 		return true;
 	}

 	private function writeFeedContent($fromdate,$todate,$site,$operation,$ids) {
 		if(!$this->appendTofile('{"feed":')) {
 			$this->log("Error writing feed tag");
 			return false;
 		}

 		if(!$this->writeCatalogContent($fromdate,$todate,$site,$operation,$ids)) {
 			$this->log("Error writing catalog tag");
 			return false;
 		}

 		if(!$this->appendTofile("}")) {
 			$this->log("Error writing closing feed tag");
 			return false;
 		}

 		return true;
 	}

 	private function writeCatalogContent($fromdate,$todate,$site,$operation,$ids) {
 		if(!$this->appendTofile('{"catalog":{')) {
 			$this->log("Error writing closing catalog tag");
 			return false;
 		}
 		if(!$this->writeSchemaContent()) {
 			return false;
 		}

 		if(!$this->appendTofile(",")) {
 			$this->log("Error while adding comma in catalog");
 			return false;
 		}

 		if(!$this->writeProductsContent($fromdate,$todate,$site,$operation,$ids)) {
 			return false;
 		}

 		if(!$this->appendTofile("}}")) {
 			$this->log("Error writing closing catalog tag");
 			return false;
 		}
 		return true;
 	}

 	private function writeSchemaContent() {
 		return $this->appendTofile('"schema":'.Mage::getSingleton('unbxd_datafeeder/feed_jsonbuilder_schemabuilder')->getSchema($this->fields));
 	}

 	private function writeProductsContent($fromdate,$todate,$site,$operation,$ids) {
 		
 		$collection=$this->getCatalogCollection($fromdate,$todate,$site,$operation,$ids);
	    // get total size
 		//set the time limit to infinite
 		ignore_user_abort(true);
 		set_time_limit(0);
		$pageNum = 0;	
		$this->log('started writing products');

		if(!$this->appendTofile('"'. $operation . '":{ "items":[')) {
			$this->log("Error while adding items tag");
 			return false;
		}

		$firstLoop = true;

		while(true){	
			$collection->clear();
			$collection->getSelect()->limit(self::PAGE_SIZE, ($pageNum++) * self::PAGE_SIZE);
			$collection->load();
			if(count($collection) == 0){
				if($pageNum == 1){
					$this->log("No products found");
					return false;
				}
				break;
			}

			if(!$firstLoop) {
				if(!$this->appendTofile( ',')) {
					$this->log("Error while addings items separator");
	 				return false;
				}
			}
 			$content=Mage::getSingleton('unbxd_datafeeder/feed_jsonbuilder_productbuilder')->getProducts($collection, $this->fields);
			$status=$this->appendTofile($content);
    		if(!$status){
    			$this->log("Error while addings items");
    			return false;
    		}
	    	$this->log('Added '.($pageNum) * $pageSize.' products');
	    	$firstLoop = false;
 		}

 		if(!$this->appendTofile("]}")) {
 			$this->log("Error writing closing items tag");
 			return false;
 		}

		
 		$this->log('Added all products');
 		return true;
 	}


 	private function setFields($site) {
		$this->fields = Mage::getResourceSingleton('datafeeder/field')->getFieldMapping($site, true);
		$this->log("fields are set" );
		$this->fields["type_id"] =  array(self::STATUS => 1, 
														self::DATA_TYPE => "longText",
														self::AUTOSUGGEST => 0 );
		$this->fields["entity_id"] =  array(self::STATUS => 1, 
														self::DATA_TYPE => "text",
														self::AUTOSUGGEST => 0 );
		$this->fields["categoryIds"] =  array(self::STATUS => 1, 
														self::DATA_TYPE => "text",
														self::AUTOSUGGEST => 0 );
		
		$this->fields = array_merge($this->fields, Mage::getResourceSingleton("datafeeder/field")->getFeaturedFields());
	}

	/**
 	* method to get the catalog collection
 	* 
 	*/
 	public function getCatalogCollection($fromdate,$todate,$site,$operation,$ids) {
 		try{
		    if ($operation == "add") {
		    	// select all the attributes
				$website =Mage::getModel("core/website")->setName($site);
				$visiblityCondition = array('in' => array(4));

				$collection = Mage::getResourceModel('catalog/product_collection')
							->addWebsiteFilter($this->validateSite($site))
							->addAttributeToFilter('status',1)
							->joinField("qty", "cataloginventory_stock_item", 'qty', 'product_id=entity_id', null, 'left')
							->addAttributeToSelect('*')
							->addAttributeToFilter('visibility',$visiblityCondition);

				if(sizeof($ids) > 0){
					$condition = array('in' => $ids);
					$collection=$collection->addAttributeToFilter('entity_id',$condition);
				}
		   } else {
				$collection = Mage::getResourceModel('catalog/product_collection');
				if(sizeof($ids) > 0) {
                    $condition = array('in' => $ids);
                    $collection= $collection->addAttributeToFilter('entity_id',$condition)->addAttributeToSelect('entity_id');
                }
            }

			$this->log($collection->getSelect());
			return $collection;
 		} catch(Exception $e) {
 			$this->log($e->getMessage());
 		}					
 	}


 	/**
 	 * Function to initialize to feed creation process
 	 */
 	private function createFile(){
 		return Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->createFile($this->fileName);
 	}

 	private function appendTofile($data){
 		return Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->appendTofile($this->fileName, $data);
 	}

 	public function log($message) {
		Mage::getSingleton('unbxd_datafeeder/feed_filemanager')->log($message);
	}

		/**
	* method to validate whether the site exists or not
	**/
	public function validateSite($site){
		$sites=Mage::app()->getWebsites();
		if( !isset($site) || $site == "") {
			return false;
		}
		foreach( $sites as $eachSite){
			if(strcasecmp ( $eachSite->getName(), $site ) == 0 ){
				return $eachSite->getWebsiteId();	
			}
		}
		return -1;
	}
}
?>