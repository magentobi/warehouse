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

	$installer = $salesInvoiceItemTable;
	$connection = $installer->getConnection(  );
	$salesQuoteItemTable = $installer->getTable( 'sales/quote_item' );
	$salesOrderItemTable = $installer->getTable( 'sales/order_item' );
	$salesOrderGridTable = $installer->getTable( 'sales/order_grid' );
	$salesInvoiceItemTable = $installer->getTable( 'sales/invoice_item' );
	$salesInvoiceGridTable = $installer->getTable( 'sales/invoice_grid' );
	$salesShipmentItemTable = $installer->getTable( 'sales/shipment_item' );
	$salesShipmentGridTable = $installer->getTable( 'sales/shipment_grid' );
	$salesCreditmemoItemTable = $installer->getTable( 'sales/creditmemo_item' );
	$salesCreditmemoGridTable = $installer->getTable( 'sales/creditmemo_grid' );
	$catalogInventoryStockTable = $installer->getTable( 'cataloginventory/stock' );
	$warehouseTable = $installer->getTable( 'warehouse' );
	$warehouseOrderGridWarehouseTable = $installer->getTable( 'warehouse/order_grid_warehouse' );
	$warehouseInvoiceGridWarehouseTable = $installer->getTable( 'warehouse/invoice_grid_warehouse' );
	$warehouseShipmentGridWarehouseTable = $installer->getTable( 'warehouse/shipment_grid_warehouse' );
	$warehouseCreditmemoGridWarehouseTable = $installer->getTable( 'warehouse/creditmemo_grid_warehouse' );
	$shippingTablerateTable = $installer->getTable( 'shipping/tablerate' );
	$installer->startSetup(  );
	$connection->addColumn( $salesQuoteItemTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_QUOTE_ITEM_STOCK', $salesQuoteItemTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesQuoteItemTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_QUOTE_ITEM_WAREHOUSE', $salesQuoteItemTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$connection->addColumn( $salesOrderItemTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_ORDER_ITEM_STOCK', $salesOrderItemTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesOrderItemTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_ORDER_ITEM_WAREHOUSE', $salesOrderItemTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$connection->addColumn( $salesInvoiceItemTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_INVOICE_ITEM_STOCK', $salesInvoiceItemTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesInvoiceItemTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_INVOICE_ITEM_WAREHOUSE', $salesInvoiceItemTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$connection->addColumn( $salesShipmentItemTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_SHIPMENT_ITEM_STOCK', $salesShipmentItemTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesShipmentItemTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_SHIPMENT_ITEM_WAREHOUSE', $salesShipmentItemTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$connection->addColumn( $salesCreditmemoItemTable, 'stock_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_CREDITMEMO_ITEM_STOCK', $salesCreditmemoItemTable, 'stock_id', $catalogInventoryStockTable, 'stock_id', 'set null' );
	$connection->addColumn( $salesCreditmemoItemTable, 'warehouse_id', 'smallint(6) unsigned null default null' );
	$connection->addConstraint( 'FK_SALES_CREDITMEMO_ITEM_WAREHOUSE', $salesCreditmemoItemTable, 'warehouse_id', $warehouseTable, 'warehouse_id', 'set null' );
	$installer->run( '
-- DROP TABLE IF EXISTS `' . $warehouseOrderGridWarehouseTable . '`;
CREATE TABLE `' . $warehouseOrderGridWarehouseTable . '` (
  `entity_id` int(10) unsigned not null, 
  `warehouse_id` smallint(6) unsigned not null, 
  PRIMARY KEY  (`entity_id`, `warehouse_id`), 
  KEY `IDX_ENTITY_ID` (`entity_id`), 
  KEY `IDX_WAREHOUSE_ID` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->addConstraint( 'FK_WAREHOUSE_ORDER_GRID_ENTITY_ID', $warehouseOrderGridWarehouseTable, 'entity_id', $salesOrderGridTable, 'entity_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_ORDER_GRID_WAREHOUSE_ID', $warehouseOrderGridWarehouseTable, 'warehouse_id', $warehouseTable, 'warehouse_id' );
	$installer->run( '
-- DROP TABLE IF EXISTS `' . $warehouseShipmentGridWarehouseTable . '`;
CREATE TABLE `' . $warehouseShipmentGridWarehouseTable . '` (
  `entity_id` int(10) unsigned not null, 
  `warehouse_id` smallint(6) unsigned not null, 
  PRIMARY KEY  (`entity_id`, `warehouse_id`), 
  KEY `IDX_ENTITY_ID` (`entity_id`), 
  KEY `IDX_WAREHOUSE_ID` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->addConstraint( 'FK_WAREHOUSE_SHIPMENT_GRID_ENTITY_ID', $warehouseShipmentGridWarehouseTable, 'entity_id', $salesShipmentGridTable, 'entity_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_SHIPMENT_GRID_WAREHOUSE_ID', $warehouseShipmentGridWarehouseTable, 'warehouse_id', $warehouseTable, 'warehouse_id' );
	$installer->run( '
-- DROP TABLE IF EXISTS `' . $warehouseCreditmemoGridWarehouseTable . '`;
CREATE TABLE `' . $warehouseCreditmemoGridWarehouseTable . '` (
  `entity_id` int(10) unsigned not null, 
  `warehouse_id` smallint(6) unsigned not null, 
  PRIMARY KEY  (`entity_id`, `warehouse_id`), 
  KEY `IDX_ENTITY_ID` (`entity_id`), 
  KEY `IDX_WAREHOUSE_ID` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->addConstraint( 'FK_WAREHOUSE_CREDITMEMO_GRID_ENTITY_ID', $warehouseCreditmemoGridWarehouseTable, 'entity_id', $salesCreditmemoGridTable, 'entity_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_CREDITMEMO_GRID_WAREHOUSE_ID', $warehouseCreditmemoGridWarehouseTable, 'warehouse_id', $warehouseTable, 'warehouse_id' );
	$installer->run( '
-- DROP TABLE IF EXISTS `' . $warehouseInvoiceGridWarehouseTable . '`;
CREATE TABLE `' . $warehouseInvoiceGridWarehouseTable . '` (
  `entity_id` int(10) unsigned not null, 
  `warehouse_id` smallint(6) unsigned not null, 
  PRIMARY KEY  (`entity_id`, `warehouse_id`), 
  KEY `IDX_ENTITY_ID` (`entity_id`), 
  KEY `IDX_WAREHOUSE_ID` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->addConstraint( 'FK_WAREHOUSE_INVOICE_GRID_ENTITY_ID', $warehouseInvoiceGridWarehouseTable, 'entity_id', $salesInvoiceGridTable, 'entity_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_INVOICE_GRID_WAREHOUSE_ID', $warehouseInvoiceGridWarehouseTable, 'warehouse_id', $warehouseTable, 'warehouse_id' );
	$connection->addColumn( $shippingTablerateTable, 'warehouse_id', 'smallint(6) unsigned NOT NULL default \'0\'' );
	$connection->addKey( $shippingTablerateTable, 'dest_country', array( 'website_id', 'dest_country_id', 'dest_region_id', 'dest_zip', 'condition_name', 'condition_value', 'warehouse_id' ), 'unique' );
	$installer->endSetup(  );
?>