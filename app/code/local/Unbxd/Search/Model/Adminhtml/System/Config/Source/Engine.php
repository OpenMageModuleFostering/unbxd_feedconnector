<?php
/**
 * Defines list of available search engines.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Model
 */
class Unbxd_Search_Model_Adminhtml_System_Config_Source_Engine
{
    /**
     * Return liste of search engines for config.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $engines = array(
            'catalogsearch/fulltext_engine'  => Mage::helper('adminhtml')->__('MySQL'),
            'unbxd_search/engine_unbxdsearch' => Mage::helper('adminhtml')->__('UnbxdSearch'),
        );
			
        $options = array();
        foreach ($engines as $k => $v) {
            $options[] = array(
                'value' => $k,
                'label' => $v
            );
        }

        return $options;
    }
}
