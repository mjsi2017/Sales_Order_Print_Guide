<?php 

Mage::log("Orderprint installer -- upgrade from 0.1.1 to 0.1.2");

$installer = $this;
/* @var $installer Mage_Sales_Model_Mysql4_Setup */

$installer->startSetup();

$sql = 'SELECT entity_type_id FROM '.$this->getTable('eav_entity_type').' WHERE entity_type_code="order"';
$row = Mage::getSingleton('core/resource')
         ->getConnection('core_read')
	     ->fetchRow($sql);

$c = array (
  'entity_type_id'  => $row['entity_type_id'],
  'attribute_code'  => 'mjsi_orderprint',
  'backend_type'    => 'varchar',     
  'is_global'       => '1',
  'is_visible'      => '1',
  'is_required'     => '0',
  'is_user_defined' => '0',
);
$attribute = new Mage_Eav_Model_Entity_Attribute();
$attribute->loadByCode($c['entity_type_id'], $c['attribute_code'])
          ->setStoreId(0)
          ->addData($c);
$attribute->save();

$installer->endSetup();