<?php

class Mjsi_Orderprint_Model_Orderprint extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('orderprint/orderprint');
    }
}