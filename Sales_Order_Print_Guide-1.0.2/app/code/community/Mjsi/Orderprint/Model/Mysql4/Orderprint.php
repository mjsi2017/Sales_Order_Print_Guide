<?php

class Mjsi_Orderprint_Model_Mysql4_Orderprint extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the orderprint_id refers to the key field in your database table.
        $this->_init('orderprint/orderprint', 'orderprint_id');
    }
}