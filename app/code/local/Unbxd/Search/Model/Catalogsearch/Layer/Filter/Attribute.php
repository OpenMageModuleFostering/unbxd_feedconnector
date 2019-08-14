<?php

class Unbxd_Search_Model_Catalogsearch_Layer_Filter_Attribute extends Unbxd_Search_Model_Catalog_Layer_Filter_Attribute
{
    protected function _getIsFilterableAttribute($attribute)
    {
        return $attribute->getIsFilterableInSearch();
    }
}
