<?php

class Unbxd_Datafeeder_Model_Mysql4_Product_Collection extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection{

    protected function _construct()
    {
        parent::_construct();
    }

    public function isEnabledFlat()
    {
        return false;
    }
}

?>