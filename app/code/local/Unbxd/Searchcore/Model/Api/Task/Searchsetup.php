<?php

/**
 * @category Unbxd
 * @package Unbxd_Searchcore
 * @author Unbxd Software Pvt. Ltd
 */
class Unbxd_Searchcore_Model_Api_Task_Searchsetup extends Unbxd_Searchcore_Model_Api_Task {

    const method = Zend_Http_Client::POST;

    const STATUS = 'status';

    const MESSAGE = 'message';

    public function prepare(Mage_Core_Model_Website $website) {
        $this->preparationSuccessful = true;
        $this->prepareUrl($website);
        $this->prepareHeaders($website);
        return $this;
    }

    protected  function prepareUrl(Mage_Core_Model_Website $website) {
        $siteKey = Mage::getResourceModel("unbxd_searchcore/config")
            ->getValue($website->getWebsiteId(), Unbxd_Searchcore_Helper_Confighelper::SITE_KEY);
        if(is_null($siteKey)) {
            $this->preparationSuccessful = false;
            $this->errors["message"] = "Site key not set";
            return;
        }

        static::$url = static::$PLATFORM_API_BASE_URL . $siteKey . "/complete-search";
    }

    protected function prepareHeaders(Mage_Core_Model_Website $website) {
        $apiKey = Mage::getResourceModel("unbxd_searchcore/config")
            ->getValue($website->getWebsiteId(), Unbxd_Searchcore_Helper_Confighelper::API_KEY);
        $secretKey = Mage::getResourceModel("unbxd_searchcore/config")
            ->getValue($website->getWebsiteId(), Unbxd_Searchcore_Helper_Confighelper::SECRET_KEY);
        if(is_null($secretKey) || is_null($apiKey)) {
            $this->preparationSuccessful = false;
            $this->errors["message"] = "Site key not set";
            return;
        }
        $this->headers["Authorization"] = base64_encode($apiKey.":" .$secretKey);
    }

    protected function postProcess(Unbxd_Searchcore_Model_Api_Response $response) {
        return $response;
    }
}