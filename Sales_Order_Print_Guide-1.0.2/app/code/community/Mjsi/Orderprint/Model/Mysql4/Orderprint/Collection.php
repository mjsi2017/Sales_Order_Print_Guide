<?php

class Mjsi_Orderprint_Model_Mysql4_Orderprint_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('orderprint/orderprint');
    }
}