<?php

class Unbxd_Datafeeder_Model_Feed_Jsonbuilder_Productbuilder extends Unbxd_Datafeeder_Model_Feed_Jsonbuilder_Jsonbuilder {

	const DATA_TYPE = "dataType";
	const MULTIVALUED = "multiValued";
	const NUMBER = "number";
	const DECIMAL = "decimal";
	const DATE = "date";
	const IMAGE_HEIGHT = "image_height";
	const IMAGE_WIDTH = "image_width";
	const GENERATE_IMAGE = "generate_image";

	public function getProducts($collection, $fields) {
		$content='';
		$firstLoop = true;
 		foreach($collection as $product) {
 			if(!$firstLoop) {
				$content = $content . ",";
			}

			$productArray = $this->getProduct($product, $fields);
			$productArray = $this->postProcessProduct($productArray, $fields, false);
 			$content=$content.json_encode($productArray);

 			$firstLoop = false;
 		}
 		
 		return rtrim($content, ",");
	}

	public function getProduct($product, $fields, $childProduct = false) {

		$productArray =array();

		foreach($product->getData('') as $columnHeader=>$columndata){
		
			if($childProduct) {
				$unbxdFieldName = $columnHeader . "Associated";
				if ($columnHeader == "gender") {
					$unbxdFieldName = "_gender";
				}
			} else {
				$unbxdFieldName = $columnHeader;
				if ($columnHeader == "gender") {
					$unbxdFieldName = "_gender";
				}
			}
			if(!array_key_exists($unbxdFieldName, $fields)) {
				continue;
			}
			
			if($columnHeader=="entity_id"){ 				
 				$productArray['uniqueId'] = $columndata;
 			}

 			if($columnHeader=="url_path"){
 				// handling the url
 				$productArray['url_path'.($childProduct?"Associated":"")] = Mage::getUrl('').$columndata;
            } else if (Mage::helper('unbxd_datafeeder/UnbxdIndexingHelper')->isImage($columnHeader)) {
            	if($fields[$unbxdFieldName][self::GENERATE_IMAGE] == "1") {
            		try {
            			$productArray[$unbxdFieldName] = (string)Mage::helper('catalog/image')->init($product, $columnHeader)
            										->resize($fields[$unbxdFieldName][self::IMAGE_WIDTH],$fields[$unbxdFieldName][self::IMAGE_HEIGHT]);
            		} catch (Exception $e) {

            			error_log("Error while fetching the image" . $e->getTraceAsString());
            		}
            	} else {
            		$productArray[$unbxdFieldName] = $columndata;
            	}
            } else if( Mage::helper('unbxd_datafeeder/UnbxdIndexingHelper')->isMultiSelect($columnHeader)){
            	// handling the array
				$data = explode(",", $columndata);			
				$attributeModel = Mage::getResourceSingleton("datafeeder/attribute");
				foreach( $data as $eachdata){
					$attributeValue = $attributeModel ->getAttributeValue($columnHeader, trim($eachdata), $product);
					$valueArray = array();;
					$valueArray[] = $attributeModel ->getAttributeValue($columnHeader, trim($eachdata), $product);
  		      		$productArray[$unbxdFieldName] = $valueArray;
				}
			} else if($columnHeader == "category_id"){
				if(!isset($columndata)){
					continue;
				}
				$categoryIds = explode(",",$columndata);
				foreach($categoryIds as $categoryId){
					$productArray[$unbxdFieldName] = trim($categoryId);
                  	$productArray["category".($childProduct?"Associated":"")] = $this->getCategoryName(trim($categoryId));
				}
				
			} else if (is_array($columndata)){
 				$productArray[$unbxdFieldName] = $columndata;
 			} else if ($columndata instanceof Varien_Object){ 				
 				$productArray[$unbxdFieldName] = $columndata->getData();
 			} else {
				$productArray[$unbxdFieldName] = $columndata;
			}
		} 
		if(!$childProduct) {
			$productArray = $this->addChildrens($product, $fields, $productArray);
		}
		return $productArray;
	}

	public function addChildrens($product, $fields, $productArray) {
		$type = $product->getData('type_id');
		if ($type == "configurable" || $type == "grouped" ) {
			$associatedProducts = [];
			$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
	    	$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
	    	foreach ($simple_collection as $sp)
	    	{
	    		$childProduct = $this->getProduct($sp, $fields, true);
	    		$childProduct = $this->postProcessProduct($childProduct, $fields, true);
	    		$associatedProducts[] = $childProduct;
	    	}

	    	$productArray["associatedProducts"] = $associatedProducts;
	    	return $productArray;
		} else {
			return $productArray;
		}
	}

	public function postProcessProduct($product, $fields, $childExists=false) {
		if($childExists) {
			$product = $this->convertMultivalued($product);
		} else {
			$product = $this->convertMultivalued($product, $fields);
		}
		$product = $this->convertDataType($product, $fields, $childExists);
		return $product;
	}

	public function convertDataType($product, $fields, $childExists) {
		foreach($product as $field => $value) {
			if($field != "associatedProducts") {
				$product[$field] = $this->convertDataTypeByValue($fields[$field], $fieldName, $value);
			}
		}
		return $product;
	}

	public function convertDataTypeByValue($data_type, $fieldName, $value) {
		if($data_type[self::DATA_TYPE] == self::DECIMAL) {
			if(is_array($value)) {
				$valueAsAnArray  = array();
				foreach ($value as $eachValue) {
					$valueAsAnArray[] = floatval($eachValue);
				}
				return $valueAsAnArray;
			} else {
				return floatval($value);
			}
		} else if ($data_type[self::DATA_TYPE] == self::NUMBER) {
			if(is_array($value)) {
				$valueAsAnArray  = array();
				foreach ($value as $eachValue) {
					$valueAsAnArray[] = intval($eachValue);
				}
				return $valueAsAnArray;
			} else {
				return intval($value);
			}
		} else if ($data_type[self::DATA_TYPE] == self::DATE) { 
			$tokens = explode(" ",$value);
			$value = $tokens[0].'T'.$tokens[1].'Z';
			return $value;
		} 
		return $value;
	}

	public function convertMultivalued($product, $fields = null) {
		foreach($product as $field=>$value) {
			if((is_null($fields) || ($fields[$field][self::MULTIVALUED] && $fields[$field][self::MULTIVALUED]))
			 && !is_array($value) && ($field != "associatedProducts")) {
				$valueAsAnArray = array();
				$valueAsAnArray[] = $value;
				$product[$field] = $valueAsAnArray;
			}
		}
		return $product;
	}

}

?>