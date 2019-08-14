<?php
/**
 * Overrides default layer view process to define custom filter blocks.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Block
 * @author Antz
 */
class Unbxd_Search_Block_Catalogsearch_Enterprise_Layer extends Enterprise_Search_Block_Catalogsearch_Layer
{
    /**
     * Boolean block name.
     *
     * @var string
     */
    protected $_booleanFilterBlockName;

    /**
     * Modifies default block names to specific ones if engine is active.
     */
    protected function _initBlocks()
    {
        parent::_initBlocks();

        if (Mage::helper('unbxd_search')->isActiveEngine()) {
            Mage::unregister('current_layer');
            Mage::register('current_layer', $this->getLayer());
            $this->_categoryBlockName = 'unbxd_search/catalog_layer_filter_category';
            $this->_attributeFilterBlockName = 'unbxd_search/catalogsearch_layer_filter_attribute';
            $this->_priceFilterBlockName = 'unbxd_search/catalog_layer_filter_price';
            $this->_decimalFilterBlockName = 'unbxd_search/catalog_layer_filter_decimal';
            $this->_booleanFilterBlockName   = 'unbxd_search/catalog_layer_filter_boolean';
        }
    }

    /**
     * Prepares layout if engine is active.
     * Difference between parent method is addFacetCondition() call on each created block.
     *
     * @return Unbxd_Search_Block_Catalogsearch_Layer
     */
    protected function _prepareLayout()
    {
        /** @var $helper Unbxd_Search_Helper_Data */
        $helper = Mage::helper('unbxd_search');
        if ($helper->isActiveEngine()) {

            $stateBlock = $this->getLayout()->createBlock($this->_stateBlockName)
                ->setLayer($this->getLayer());

            $categoryBlock = $this->getLayout()->createBlock($this->_categoryBlockName)
                ->setLayer($this->getLayer())
                ->init();

            $this->setChild('layer_state', $stateBlock);
            $this->setChild('category_filter', $categoryBlock->addFacetCondition());

            $filterableAttributes = $this->_getFilterableAttributes();
            $filters = array();
            foreach ($filterableAttributes as $attribute) {
                if ($attribute->getAttributeCode() == 'price') {
                    $filterBlockName = $this->_priceFilterBlockName;
                } elseif ($attribute->getSourceModel() == 'eav/entity_attribute_source_boolean') {
                    $filterBlockName = $this->_booleanFilterBlockName;
                } elseif ($attribute->getBackendType() == 'decimal') {
                    $filterBlockName = $this->_decimalFilterBlockName;
                } else {
                    $filterBlockName = $this->_attributeFilterBlockName;
                }

                $filters[$attribute->getAttributeCode() . '_filter'] = $this->getLayout()->createBlock($filterBlockName)
                    ->setLayer($this->getLayer())
                    ->setAttributeModel($attribute)
                    ->init();
            }

            foreach ($filters as $filterName => $block) {
                $this->setChild($filterName, $block->addFacetCondition());
            }

            $this->getLayer()->apply();
            $this->getLayer()->getProductCollection()->load();
        }else{
            parent::_prepareLayout();
        }

        return $this;
    }

    /**
     * Checks display availability of layer block.
     *
     * @return bool
     */
    public function canShowBlock()
    {
        $this->getLayer()->getProductCollection();
        return ($this->canShowOptions() || count($this->getLayer()->getState()->getFilters()));
    }

    /**
     * Returns current catalog layer.
     *
     * @return Unbxd_Search_Model_Catalogsearch_Layer|Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        /** @var $helper Unbxd_Search_Helper_Data */
        $helper = Mage::helper('unbxd_search');
        if ($helper->isActiveEngine()) {
            return Mage::getSingleton('unbxd_search/catalogsearch_layer');
        }
        return parent::getLayer();
    }
}
