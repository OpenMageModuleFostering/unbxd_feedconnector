<?php 

class Unbxdsearch_Datafeeder_Model_Mysql4_Field extends Mage_Core_Model_Mysql4_Abstract
{
	protected function _construct()
	{
		  $this->_init('datafeeder/field', 'field_id');
	}
	
	/*
	* Method to get Unbxd Fields Configuration as Mapping give the site
	*/
	public function getFieldMapping($site) {
		$results = Mage::getModel('datafeeder/field')->getCollection()->addFieldToFilter("site", $site);
		$fieldMapping = [];
		$_reader = Mage::getSingleton('core/resource')->getConnection('core_read');
		$table = $_reader->getTableName('unbxd_search_field');
		$select = $_reader->select();
		$select->from($table);
		$select->where("site = '" . $site. "'");

		$results = $_reader->fetchAll($select);

		foreach($results as $eachResult) {
			$fieldMapping[$eachResult["name"]]=$eachResult['status'];
		}
		return $fieldMapping;
	}

	/**
	* Returns the fields as an array of field Name to value
	**/
	public function getFields($site) {
		$fieldMapping = $this->getFieldMapping($site);
		$deltaUpdate =[];
		$attributes = Mage::helper('unbxdsearch_datafeeder/UnbxdIndexingHelper')->getAttributes();
		foreach($attributes as $attribute){
			if (!array_key_exists($attribute->getAttributeCode(), $fieldMapping)) {
				$deltaUpdate[$attribute->getAttributeCode()] = "1";
				$fieldMapping[$attribute->getAttributeCode()] = "1";
			}
		}
		if(sizeof($deltaUpdate) > 0) {
			$this->saveField($deltaUpdate, $site);
		}
		return $fieldMapping;
	}

	/*
	* update fields
	*/
	public function updateFields($fieldMapping, $site) {
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		foreach($fieldMapping as $fieldName=>$status) {
			$write->update($write->getTableName("unbxd_search_field"), array("status" => $status), "site='".$site."' AND name='".$fieldName."'"); 
		}
	}

	/*
	* method to save the fieldMapping information
	*/
	public function saveField($fieldMapping, $site) {
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$insertingRequestArray = [];	
		foreach($fieldMapping as $field=>$value) { 
			$insertingRequest = [];
			$insertingRequest["name"] = $field;
			$insertingRequest["status"] = $value;
			$insertingRequest["site"] = $site;
			$insertingRequestArray[] = $insertingRequest;
		}

		$write->insertMultiple($write->getTableName("unbxd_search_field"), $insertingRequestArray);
	}

	/*
	* method to get All Enabled fields
	*/
	public function getEnabledFields($site) {
		$results = Mage::getModel('datafeeder/field')->getCollection()->addFieldToFilter("site", $site);
		$fields = [];
		$_reader = Mage::getSingleton('core/resource')->getConnection('core_read');
		$table = $_reader->getTableName('unbxd_search_field');
		$select = $_reader->select();
		$select->from($table);
		$select->where("site = '" . $site. "' AND status='1'");
		$results = $_reader->fetchAll($select);
		foreach($results as $eachResult) {
			$fields[]=$eachResult["name"];
		}
		return $fields;
	}
}
?>