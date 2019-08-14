<?php
/**
 * Handles boolean attribute filtering in layered navigation.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Block
 * @author Antz
 */
class Unbxd_Search_Block_Catalog_Layer_Filter_Boolean extends Unbxd_Search_Block_Catalog_Layer_Filter_Attribute
{
    /**
     * Defines specific filter model name.
     *
     * @see Unbxd_Search_Model_Catalog_Layer_Filter_Boolean
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'unbxd_search/catalog_layer_filter_boolean';
    }
}
