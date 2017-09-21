<?php
class Mage_Adminhtml_Block_Sales_Order_Printed extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
	{
	public function render(Varien_Object $row)
		{
		$value =  $row->getData();
		$value = $value['entity_id'];

		$resource = Mage::getSingleton('core/resource');
         
        /**
         * Retrieve the read connection
         */
        $readConnection = $resource->getConnection('core_read');
     
        /**
         * Retrieve our table name
         */
        $table = $resource->getTableName('orderprint/orderprint');
         
         
        $query = 'SELECT orderprint_id FROM ' . $table . ' WHERE order_id = '
                 . $value . ' LIMIT 1';
         
        /**
         * Execute the query and store the result in $sku
         */
        $value = $readConnection->fetchOne($query);
        //return $value;


		if($value) {return '<span style="font-weight:bold;">Yes</span>';}
		else {return '<span style="color:#900; font-weight:bold;">No</span>';}
		///return '<span style="color:#900;">'.$value.'</span>';
		}
	}