<?php
/**
 * Unbxd helper.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Helper
 * @author antz
 */
class Unbxd_Search_Helper_Unbxdsearch extends Unbxd_Search_Helper_Data
{
    /**
     * Returns Unbxd engine config data.
     *
     * @param string $prefix
     * @param mixed $store
     * @return array
     */
    public function getEngineConfigData($prefix = '', $website = null)
    {
        return Mage::helper('unbxd_searchcore')->getEngineConfigData($prefix, $website);
        
    }
}
