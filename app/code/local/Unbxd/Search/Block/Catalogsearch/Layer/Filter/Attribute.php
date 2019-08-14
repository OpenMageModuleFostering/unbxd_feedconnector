<?php
/**
 * Handles attribute filtering in layered navigation in a query search context.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Block
 * @author Antz
 */
class Unbxd_Search_Block_Catalogsearch_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Abstract
{
    /**
     * Defines specific filter model name.
     *
     * @see Unbxd_Search_Model_Catalogsearch_Layer_Filter_Attribute
     */
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'unbxd_search/catalogsearch_layer_filter_attribute';
    }

    /**
     * Prepares filter model.
     *
     * @return Unbxd_Search_Block_Catalogsearch_Layer_Filter_Attribute
     */
    protected function _prepareFilter()
    {
        $this->_filter->setAttributeModel($this->getAttributeModel());

        return $this;
    }

    /**
     * Adds facet condition to filter.
     *
     * @see Unbxd_Search_Model_Catalog_Layer_Filter_Attribute::addFacetCondition()
     * @return Unbxd_Search_Block_Catalogsearch_Layer_Filter_Attribute
     */
    public function addFacetCondition()
    {
        $this->_filter->addFacetCondition();

        return $this;
    }
}
