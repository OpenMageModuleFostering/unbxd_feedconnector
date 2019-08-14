<?php
class Unbxd_Search_Model_Resource_Engine_Unbxdsearch_Client extends Unbxd_Client{
	
	public function __construct()
    {
        $config = $this->_getHelper()->getEngineConfigData();
        $config['sitename'] = Mage::helper('unbxd_search/unbxdsearch')->getSiteName();
        $config['apikey'] = Mage::helper('unbxd_search/unbxdsearch')->getApiKey();
        parent::__construct($config);

    }
    
    protected function _getHelper()
    {
        return Mage::helper('unbxd_search/unbxdsearch');
    }
    	
	
}
?>
