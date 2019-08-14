<?php
/**
 * Class that will require catalog search reindexation after search engine choice in config.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Model
 */
class Unbxd_Search_Model_Adminhtml_System_Config_Backend_Engine extends Mage_Core_Model_Config_Data
{
    /**
     * Requires catalog category products and catalog search reindexation.
     *
     * @return Unbxd_Search_Model_Adminhtml_System_Config_Backend_Engine
     */
    protected function _afterSave()
    {
        $indexer = Mage::getSingleton('index/indexer');
        $indexer->getProcessByCode('catalogsearch_fulltext')
            ->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
        $indexer->getProcessByCode('catalog_category_product')
            ->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);

        return $this;
    }
}
