<?php
/**
 * Handles decimal attribute filtering in layered navigation.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Block
 * @author Antz
 */
class Unbxd_Search_Block_Catalog_Layer_Filter_Decimal extends Mage_Catalog_Block_Layer_Filter_Abstract
{
    /**
     * Defines specific filter model name.
     *
     * @see Unbxd_Search_Model_Catalog_Layer_Filter_Decimal
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'unbxd_search/catalog_layer_filter_decimal';
    }

    /**
     * Prepares filter model.
     *
     * @return Unbxd_Search_Block_Catalog_Layer_Filter_Decimal
     */
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());

        return $this;
    }

    /**
     * Adds facet condition to filter.
     *
     * @see Unbxd_Search_Model_Catalog_Layer_Filter_Decimal::addFacetCondition()
     * @return Unbxd_Search_Block_Catalog_Layer_Filter_Decimal
     */
    public function addFacetCondition()
    {
        $this->_filter->addFacetCondition();

        return $this;
    }
}
