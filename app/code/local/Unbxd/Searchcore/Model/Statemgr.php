<?php

class Unbxd_Searchcore_Model_Statemgr {


	public function getCatalogState(Mage_Core_Model_Website $website) {
		return Mage::getModel('unbxd_searchcore/state_catalog')->getState($website);
	}

	public function getCredentialsState(Mage_Core_Model_Website $website) {
		return Mage::getModel('unbxd_searchcore/state_credentials')->getState($website);
	}

	public function getAnalyticsState(Mage_Core_Model_Website $website) {
		return Mage::getModel('unbxd_searchcore/state_analytics')->getState($website);
	}

	public function getAllStates(Mage_Core_Model_Website $website) {
		$catalogState = $this->getCatalogState($website);
		$credentialsState = $this->getCredentialsState($website);
		$analyticsState = $this->getAnalyticsState($website);
		return array('catalog' => $catalogState->_asArray(), 
				'analytics' => $analyticsState->_asArray(),
				'credentials' => $credentialsState->_asArray());
	}
}
?>
