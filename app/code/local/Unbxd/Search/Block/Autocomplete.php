<?php

class Unbxd_Search_Block_Autocomplete extends Mage_Core_Block_Template
{
	protected $_oneColumnTemplate = array(
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_1COLUMN,
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT_RIGHT,
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_MAIN_TEMPLATE => array(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_INQUERY,
										Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_KEYWORDSUGGESTION,
										Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TOP_QUERY,
										Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_POP_PRODUCTS),
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGET_SIDE_TEMPLATE => array()); 


	protected $_twoColumnLeftTemplate = array(
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_2COLUMN,
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT_LEFT,
                Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_MAIN_TEMPLATE => array(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_INQUERY,
                                                                                Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_POP_PRODUCTS),
                Unbxd_Searchcore_Helper_Constants::AUTOSUGGET_SIDE_TEMPLATE => array(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TOP_QUERY,
										Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_KEYWORDSUGGESTION));

	protected $_twoColoumnRightTemplate = array(
                Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_2COLUMN,
                Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT_RIGHT,
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_MAIN_TEMPLATE => array(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_INQUERY,
                                                                                 Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_POP_PRODUCTS),
                 Unbxd_Searchcore_Helper_Constants::AUTOSUGGET_SIDE_TEMPLATE => array(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TOP_QUERY,
                                                                                 Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_KEYWORDSUGGESTION));

	protected $_oneColumnAddToCartTemplate = array(
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SHOW_CART => Unbxd_Searchcore_Helper_Constants::TRUE,
                Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_1COLUMN,
                Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT => Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SIDECONTENT_RIGHT,
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_MAIN_TEMPLATE => array(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_POP_PRODUCTS),
		Unbxd_Searchcore_Helper_Constants::AUTOSUGGET_SIDE_TEMPLATE => array());

	protected $_templateConfig = array();

	public function _prepareLayout() {
		$this->_templateConfig = array(
	          Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_1COLUMN => $this->_oneColumnTemplate,
	          Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_2COLUMN_LEFT => $this->_twoColumnLeftTemplate,
	          Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_2COLUMN_RIGHT => $this->_twoColoumnRightTemplate,
	          Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_1COLUMN_ADD_TO_CART => $this->_oneColumnAddToCartTemplate);
	}

	public function getTemplateConfig($templateName) {
		if(!array_key_exists($templateName, $this->_templateConfig)) {
			$templateName = Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TEMPLATE_1COLUMN;
		}
		return $this->_templateConfig[$templateName];
	}

	public function getAvailableAutosuggestComp() {
	     $autosuggestComponents = array();
	     $website = Mage::app()->getWebsite();
	     $config = Mage::helper('unbxd_searchcore')->getEngineConfigData(Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_MAX_PRODUCTS);
	     if (Mage::helper('unbxd_searchcore')
	         ->isConfigTrue($website, Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_SEARCH_SCOPE_STATUS)
	     ) {
	         $autosuggestComponents[] = Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_INQUERY;
	     }
	     if (Mage::helper('unbxd_searchcore')
	         ->isConfigTrue($website, Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_KEYWORD_STATUS)) {
	         $autosuggestComponents[] = Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_KEYWORDSUGGESTION;
	     }
	     if (Mage::helper('unbxd_searchcore')
	         ->isConfigTrue($website, Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TOP_QUERIES_STATUS)
	     ) {
	         $autosuggestComponents[] = Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_TOP_QUERY;
	     }
	     if ((int)$config[Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_MAX_PRODUCTS] > 0) {
	         $autosuggestComponents[] = Unbxd_Searchcore_Helper_Constants::AUTOSUGGEST_POP_PRODUCTS;
	     }
	     return $autosuggestComponents;
	}

}
?>
