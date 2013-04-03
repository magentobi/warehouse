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

	$installer = $warehouseTable;
	$connection = $installer->getConnection(  );
	$salesQuoteAddressTable = $installer->getTable( 'sales/quote_address' );
	$salesOrderAddressTable = $installer->getTable( 'sales/order_address' );
	$catalogInventoryStockTable = $installer->getTable( 'cataloginventory/stock' );
	$warehouseTable = $installer->getTable( 'warehouse' );
	$installer->startSetup(  );
	$installer->run( '
DROP TABLE IF EXISTS `' . $warehouseTable . '`;

CREATE TABLE `' . $warehouseTable . '` (
  `warehouse_id` smallint(6) unsigned NOT NULL auto_increment,
  `title` varchar(128) DEFAULT NULL,
  `stock_id` smallint(6) unsigned NOT NULL, 
  `origin_country_id` varchar(2) NOT NULL, 
  `origin_region_id` mediumint(8) NULL, 
  `origin_region` varchar(100) NULL, 
  `origin_postcode` varchar(50) NOT NULL, 
  `origin_city` varchar(100) NOT NULL, 
  `origin_latitude` decimal(18,12) NULL, 
  `origin_longitude` decimal(18,12) NULL, 
  `created_at` datetime NOT NULL default \'0000-00-00 00:00:00\', 
  `updated_at` datetime NOT NULL default \'0000-00-00 00:00:00\', 
  PRIMARY KEY  (`warehouse_id`),
  KEY `FK_WAREHOUSE_STOCK` (`stock_id`),
  KEY `IDX_TITLE` (`title`),
  KEY `IDX_CREATED_AT` (`created_at`),
  KEY `IDX_UPDATED_AT` (`updated_at`),
  CONSTRAINT `FK_WAREHOUSE_STOCK` FOREIGN KEY (`stock_id`) REFERENCES ' . $catalogInventoryStockTable . ' (`stock_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$installer->addAttribute( 'customer_address', 'warehouse_id', array( 'label' => 'Warehouse', 'visible' => '0', 'required' => '0', 'type' => 'int', 'input' => 'hidden', 'sort_order' => '125', 'lines_to_divide' => '0' ) );
	$installer->updateAttribute( 'customer_address', 'warehouse_id', 'multiline_count', '0' );
	$installer->updateAttribute( 'customer_address', 'warehouse_id', 'sort_order', '125' );
	$installer->addAttribute( 'customer_address', 'stock_id', array( 'label' => 'Stock', 'visible' => '0', 'required' => '0', 'type' => 'int', 'input' => 'hidden', 'sort_order' => '126', 'lines_to_divide' => '0' ) );
	$installer->updateAttribute( 'customer_address', 'stock_id', 'multiline_count', '0' );
	$installer->updateAttribute( 'customer_address', 'stock_id', 'sort_order', '126' );
	$connection->addColumn( $salesQuoteAddressTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_QUOTE_ADDRESS_STOCK', $salesQuoteAddressTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesQuoteAddressTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_QUOTE_ADDRESS_WAREHOUSE', $salesQuoteAddressTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$connection->addColumn( $salesOrderAddressTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_ORDER_ADDRESS_STOCK', $salesOrderAddressTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesOrderAddressTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_ORDER_ADDRESS_WAREHOUSE', $salesOrderAddressTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$installer->endSetup(  );
?>