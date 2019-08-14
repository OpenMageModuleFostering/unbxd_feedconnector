<?php
/**
 * Handles boolean attribute filtering in layered navigation.
 *
 * @package Unbxd_Search
 * @subpackage Unbxd_Search_Model
 * @author Antz
 */
class Unbxd_Search_Model_Catalog_Layer_Filter_Boolean extends Unbxd_Search_Model_Catalog_Layer_Filter_Attribute
{
    /**
     * Returns facets data of current attribute.
     *
     * @return array
     */
    protected function _getFacets()
    {
        $facets = parent::_getFacets();
        $result = array();
        foreach ($facets as $value => $count) {
            $key = 0; // false by default
            if ($value === 'true' || $value === 'T' || $value === '1' || $value === 1 || $value === true) {
                $key = 1;
            }
            $result[$key] = $count;
        }

        return $result;
    }

    /**
     * Checks if given filter is valid before being applied to product collection.
     *
     * @param string $filter
     * @return bool
     */
    protected function _isValidFilter($filter)
    {
        return $filter === '0' || $filter === '1' || false === $filter || true === $filter;
    }
}
