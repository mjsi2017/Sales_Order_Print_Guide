<?php
class Mjsi_Orderprint_Block_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View {
	
	public function __construct() {
		parent::__construct();
		$this->_addButton('order_print', array(
                'label'     => Mage::helper('sales')->__('Print Orders'),
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/*/print') . '\')',
            ), 200);
	} 
}
