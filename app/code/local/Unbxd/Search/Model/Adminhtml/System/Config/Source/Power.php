<?php
/**
 * Defines list of available search engines.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Model
 */
class Unbxd_Search_Model_Adminhtml_System_Config_Source_Power
{
    /**
     * Return liste of search engines for config.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $engines = array(
	    'All' => 'All',
            'navigation' => 'Navigation',
            'search' => 'Search'
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
