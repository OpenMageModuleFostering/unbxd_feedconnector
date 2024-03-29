<?php

class Unbxd_Datafeeder_Model_Feed_Jsonbuilder_Schemabuilder extends Unbxd_Datafeeder_Model_Feed_Jsonbuilder_Jsonbuilder {


	const SCHEMA = 'schema';
	const FIELD_NAME = "fieldName";
	const DATA_TYPE = "dataType";
	const MULTIVALUED = "multiValued";
	const AUTOSUGGEST = "autoSuggest";
	const TRUE = "true";
	const FALSE = "false";

	const FIELD_STATUS = 'status';
	const FIELD_DATA_TYPE = 'data_type';
	const FIELD_AUTOSUGGEST = 'autosuggest';

	public function getSchema($fields) {
		$jsonString = "";
		$fieldList = array();
		$featuredFields = Mage::getResourceSingleton("datafeeder/field")->getFeaturedFields();
		foreach($fields as $fieldName=>$values ) {
			if($values[self::FIELD_STATUS] == 1 || !array_key_exists($fieldName, $featuredFields) ) {
				$fieldList[] = array(self::FIELD_NAME => $fieldName,
				self::DATA_TYPE => $values[self::FIELD_DATA_TYPE],
				self::MULTIVALUED => ($this->endsWith($fieldName, "Associated")?self::TRUE:(Mage::helper('unbxd_datafeeder/UnbxdIndexingHelper')->isMultiSelect(($fieldName == "_gender")?"gender":$fieldName)?
					self::TRUE:self::FALSE)),
				self::AUTOSUGGEST => ($values[self::FIELD_AUTOSUGGEST] == 1?
					self::TRUE: self::FALSE));
			}
		}
		return json_encode($fieldList);
	}


	function endsWith($haystack, $needle)
	{
	    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
}

?>