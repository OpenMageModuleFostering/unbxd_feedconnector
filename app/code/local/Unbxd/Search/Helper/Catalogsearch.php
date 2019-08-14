<?php

/**
 * @category Unbxd
 * @package Unbxd_Recommendation
 * @author Unbxd Software Pvt. Ltd
 */
class Unbxd_Search_Helper_Catalogsearch extends Mage_CatalogSearch_Helper_Data {
    public function getResultUrl($query = null) {
	if(Mage::helper('unbxd_search')->isHostedSearchActive()) {
		$redirectUrl = Mage::helper('unbxd_search')->getHostedRedirectUrl();
		return $redirectUrl . ((!is_null($query) && $query != "")?($this->getQueryParamName()."=".$query):"");
	}
	return parent::getResultUrl($query);
    }
}
