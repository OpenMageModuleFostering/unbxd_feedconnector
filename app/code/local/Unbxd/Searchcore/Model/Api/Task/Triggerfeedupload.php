<?php

/**
 * class to validate the secret key and site key with unbxd
 *
 * @category Unbxd
 * @package Unbxd_Searchcore
 * @author Unbxd Software Pvt. Ltd
 */
class Unbxd_Searchcore_Model_Api_Task_Triggerfeedupload extends Unbxd_Searchcore_Model_Api_Task {

    const method = Zend_Http_Client::POST;

    const TIMEOUT = 5;

    public function prepare(Mage_Core_Model_Website $website) {
        $this->preparationSuccessful = true;
        $this->prepareUrl();
        $this->prepareParams($website);
        return $this;
    }

    protected function prepareUrl() {
        static::$url = Mage::getBaseUrl()."searchcore/config/productsync";
        return $this;
    }

    protected function prepareParams(Mage_Core_Model_Website $website) {
        $this->setData("site", $website->getName());
        $this->setData("auth", Mage::getSingleton('unbxd_searchcore/auth')->getAuthKey());
        return $this;
    }

    protected function postProcess(Unbxd_Searchcore_Model_Api_Response $response) {
        $response->setSuccess(true);
        $response->setErrors(array());
        return $response;
    }
}

?>