<?php
/**
 * Overrides default layer model to handle custom product collection filtering.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Model
 * @author Antz
 */
class Unbxd_Search_Model_Catalog_Layer extends Mage_Catalog_Model_Layer
{
    /**
     * Returns product collection for current category.
     *
     * @return Unbxd_Search_Model_Resource_Catalog_Product_Collection
     */
    public function getProductCollection()
    {
        /** @var $category Mage_Catalog_Model_Category */
        $category = $this->getCurrentCategory();
        /** @var $collection Unbxd_Search_Model_Resource_Catalog_Product_Collection */
        if (isset($this->_productCollections[$category->getId()])) {
            $collection = $this->_productCollections[$category->getId()];
        } else {
            $collection = Mage::getResourceModel('unbxd_search/engine_unbxdsearch')
                ->getResultCollection()
                ->setStoreId($category->getStoreId())
                ->addCategoryId($category->getId())
                ->setQueryType('browse')
                ->addFqFilter(array('store_id' => $category->getStoreId()));
                
            $this->prepareProductCollection($collection);
            $this->_productCollections[$category->getId()] = $collection;
        }

        return $collection;
    }
}
