<?php
/**
*
* @ This file is created by Decoded 
* @ Decoder + Fix (PHP5 Decoder for ionCube Encoder)
*
* @	Version			:	?.?.?.?
* @	Author			:	Defy
* @	Release on		:	02.04.2013
* @	Official site	:	
*
*/

	$installer = $installer;
	$connection = $installer->getConnection(  );
	$warehouseTable = $installer->getTable( 'warehouse' );
	$catalogProductTable = $installer->getTable( 'catalog/product' );
	$catalogProductShelfTable = $installer->getTable( 'catalog/product_shelf' );
	$installer->startSetup(  );
	$installer->run( '
DROP TABLE IF EXISTS `' . $catalogProductShelfTable . '`;

CREATE TABLE `' . $catalogProductShelfTable . '` (
  `product_id` int(10) unsigned not null, 
  `warehouse_id` smallint(6) unsigned not null, 
  `name` varchar(128) not null default \'\', 
  PRIMARY KEY  (`product_id`, `warehouse_id`, `name`), 
  KEY `FK_CATALOG_PRODUCT_SHELF_PRODUCT` (`product_id`), 
  KEY `FK_CATALOG_PRODUCT_SHELF_WAREHOUSE` (`warehouse_id`), 
  KEY `IDX_NAME` (`name`), 
  CONSTRAINT `FK_CATALOG_PRODUCT_SHELF_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES ' . $catalogProductTable . ' (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE, 
  CONSTRAINT `FK_CATALOG_PRODUCT_SHELF_WAREHOUSE` FOREIGN KEY (`warehouse_id`) REFERENCES ' . $warehouseTable . ' (`warehouse_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$installer->endSetup(  );
?>