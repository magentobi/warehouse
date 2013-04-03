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
	$catalogProductTable = $installer->getTable( 'catalog/product' );
	$catalogInventoryStockTable = $installer->getTable( 'cataloginventory/stock' );
	$catalogProductStockPriceTable = $installer->getTable( 'catalog/product_stock_price' );
	$installer->startSetup(  );
	$installer->run( '
DROP TABLE IF EXISTS `' . $catalogProductStockPriceTable . '`;

CREATE TABLE `' . $catalogProductStockPriceTable . '` (
  `product_id` int(10) unsigned not null, 
  `stock_id` smallint(6) unsigned not null, 
  `price` decimal(12,4) NOT NULL default \'0.00\', 
  `price_type` enum(\'fixed\', \'percent\') NOT NULL default \'fixed\', 
  PRIMARY KEY  (`product_id`, `stock_id`), 
  KEY `FK_CATALOG_PRODUCT_STOCK_PRICE_PRODUCT` (`product_id`), 
  KEY `FK_CATALOG_PRODUCT_STOCK_PRICE_STOCK` (`stock_id`), 
  CONSTRAINT `FK_CATALOG_PRODUCT_STOCK_PRICE_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES ' . $catalogProductTable . ' (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE, 
  CONSTRAINT `FK_CATALOG_PRODUCT_STOCK_PRICE_STOCK` FOREIGN KEY (`stock_id`) REFERENCES ' . $catalogInventoryStockTable . ' (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$installer->endSetup(  );
?>