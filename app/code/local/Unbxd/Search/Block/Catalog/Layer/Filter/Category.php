<?php
/**
 * Handles category filtering in layered navigation.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Block
 * @author Antz
 */
class Unbxd_Search_Block_Catalog_Layer_Filter_Category extends Mage_Catalog_Block_Layer_Filter_Abstract
{
    /**
     * Defines specific filter model name.
     *
     * @see Unbxd_Search_Model_Catalog_Layer_Filter_Category
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'unbxd_search/catalog_layer_filter_category';
    }

    /**
     * Adds facet condition to filter.
     *
     * @see Unbxd_Search_Model_Catalog_Layer_Filter_Category::addFacetCondition()
     * @return Unbxd_Search_Block_Catalog_Layer_Filter_Attribute
     */
    public function addFacetCondition()
    {
        $this->_filter->addFacetCondition();

        return $this;
    }
}
