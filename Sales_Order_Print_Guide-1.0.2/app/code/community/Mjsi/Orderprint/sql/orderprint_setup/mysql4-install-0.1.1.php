<?php

Mage::log("Orderprint installer -- install 0.1.1");

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('orderprint')};
CREATE TABLE {$this->getTable('orderprint')} (
  `orderprint_id` int(11) unsigned NOT NULL auto_increment,
  `order_id` int(250) NOT NULL,
  `date_printed` datetime NULL,
  PRIMARY KEY (`orderprint_id`),
  KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 