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

	$installer = $productStockPriorityTable;
	$connection = $installer->getConnection(  );
	$helper = Mage::helper( 'warehouse' );
	$stockTable = $installer->getTable( 'cataloginventory/stock' );
	$productTable = $installer->getTable( 'catalog/product' );
	$productStockPriorityTable = $installer->getTable( 'catalog/product_stock_priority' );
	$productStockShippingCarrierTable = $installer->getTable( 'catalog/product_stock_shipping_carrier' );
	$installer->startSetup(  );
	$installer->run( '
-- DROP TABLE IF EXISTS `' . $productStockPriorityTable . '`;

CREATE TABLE `' . $productStockPriorityTable . '` (
  `product_id` int(10) unsigned not null, 
  `stock_id` smallint(6) unsigned not null, 
  `priority` smallint(6) unsigned not null default 0, 
  PRIMARY KEY  (`product_id`, `stock_id`), 
  KEY `FK_CATALOG_PRODUCT_STOCK_PRIORITY_PRODUCT` (`product_id`), 
  KEY `FK_CATALOG_PRODUCT_STOCK_PRIORITY_STOCK` (`stock_id`), 
  CONSTRAINT `FK_CATALOG_PRODUCT_STOCK_PRIORITY_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES ' . $productTable . ' (`entity_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE, 
  CONSTRAINT `FK_CATALOG_PRODUCT_STOCK_PRIORITY_STOCK` FOREIGN KEY (`stock_id`) REFERENCES ' . $stockTable . ' (`stock_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$installer->run( '
-- DROP TABLE IF EXISTS `' . $productStockShippingCarrierTable . '`;

CREATE TABLE `' . $productStockShippingCarrierTable . '` (
  `product_id` int(10) unsigned not null, 
  `stock_id` smallint(6) unsigned not null, 
  `shipping_carrier` varchar(255) not null, 
  PRIMARY KEY  (`product_id`, `stock_id`, `shipping_carrier`), 
  KEY `FK_CATALOG_PRODUCT_STOCK_SHIPPING_CARRIER_PRODUCT` (`product_id`), 
  KEY `FK_CATALOG_PRODUCT_STOCK_SHIPPING_CARRIER_STOCK` (`stock_id`), 
  KEY `IDX_SHIPPING_CARRIER` (`shipping_carrier`), 
  CONSTRAINT `FK_CATALOG_PRODUCT_STOCK_SHIPPING_CARRIER_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES ' . $productTable . ' (`entity_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE, 
  CONSTRAINT `FK_CATALOG_PRODUCT_STOCK_SHIPPING_CARRIER_STOCK` FOREIGN KEY (`stock_id`) REFERENCES ' . $stockTable . ' (`stock_id`) 
    ON DELETE CASCADE ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$installer->endSetup(  );
?>