<?php

class Unbxd_Searchcore_Model_State_Credentials implements Unbxd_Searchcore_Model_State {

	/**
         * Method which returns the state of the component
         * @param Mage_Core_Model_Website $website
         * @return Unbxd_Searchcore_Model_State_Response
         */
         public function getState(Mage_Core_Model_Website $website) {
		$response = Mage::getModel('unbxd_searchcore/state_response');
		$status = true;
		$message = array();
		if(!$this->_checkAuth($website)) {
			$status = false;
			$message[] = "Authorization is not done";
		}
		$response->setStatus($status);
		$response->setMessage($message);
		return $response;
	 }

	 /**
           * Method to check the Auth
           * @param Mage_Core_Model_Website $website
           * @return bool
           */
          protected function _checkAuth(Mage_Core_Model_Website $website) {
                 $secretKey = Mage::getResourceModel('unbxd_searchcore/config')
                                 ->getValue($website->getWebsiteId(), Unbxd_Searchcore_Helper_Constants::SECRET_KEY);
                 return !is_null($secretKey);
          }
	
}