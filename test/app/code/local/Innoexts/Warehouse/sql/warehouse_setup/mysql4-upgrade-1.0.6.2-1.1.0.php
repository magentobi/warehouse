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

	$installer = $warehouseShippingCarrierTable;
	$connection = $installer->getConnection(  );
	$warehouseHelper = Mage::helper( 'warehouse' );
	$warehouseTable = $installer->getTable( 'warehouse/warehouse' );
	$warehouseStoreTable = $installer->getTable( 'warehouse/warehouse_store' );
	$warehouseShippingCarrierTable = $installer->getTable( 'warehouse/warehouse_shipping_carrier' );
	$storeTable = $installer->getTable( 'core/store' );
	$quoteTable = $installer->getTable( 'sales/quote' );
	$quoteAddressTable = $installer->getTable( 'sales/quote_address' );
	$orderAddressTable = $installer->getTable( 'sales/order_address' );
	$productShelfTable = $installer->getTable( 'catalog/product_shelf' );
	$stockTable = $installer->getTable( 'cataloginventory/stock' );
	$quoteItemTable = $installer->getTable( 'sales/quote_item' );
	$orderItemTable = $installer->getTable( 'sales/order_item' );
	$invoiceItemTable = $installer->getTable( 'sales/invoice_item' );
	$shipmentItemTable = $installer->getTable( 'sales/shipment_item' );
	$creditmemoItemTable = $installer->getTable( 'sales/creditmemo_item' );
	$orderGridWarehouseTable = $installer->getTable( 'warehouse/order_grid_warehouse' );
	$invoiceGridWarehouseTable = $installer->getTable( 'warehouse/invoice_grid_warehouse' );
	$shipmentGridWarehouseTable = $installer->getTable( 'warehouse/shipment_grid_warehouse' );
	$creditmemoGridWarehouseTable = $installer->getTable( 'warehouse/creditmemo_grid_warehouse' );
	$shippingTablerateTable = $installer->getTable( 'shipping/tablerate' );
	$installer->startSetup(  );
	$installer->removeAttribute( 'customer_address', 'warehouse_id' );
	$connection->dropColumn( $quoteAddressTable, 'warehouse_id' );
	$connection->dropColumn( $orderAddressTable, 'warehouse_id' );
	$connection->addColumn( $warehouseTable, 'origin_latitude', 'decimal(18,12) null default null after origin_city' );
	$connection->addColumn( $warehouseTable, 'origin_longitude', 'decimal(18,12) null default null after origin_latitude' );
	$connection->addKey( $warehouseTable, 'IDX_ORIGIN_LATITUDE', 'origin_latitude' );
	$connection->addKey( $warehouseTable, 'IDX_ORIGIN_LONGITUDE', 'origin_longitude' );
	$installer->run( '
DROP TABLE IF EXISTS `' . $warehouseStoreTable . '`;

CREATE TABLE `' . $warehouseStoreTable . '` (
  `warehouse_id` smallint(6) unsigned not null, 
  `store_id` smallint(5) unsigned not null, 
  PRIMARY KEY  (`warehouse_id`, `store_id`), 
  KEY `FK_WAREHOUSE_STORE_WAREHOUSE` (`warehouse_id`), 
  KEY `FK_WAREHOUSE_STORE_STORE` (`store_id`), 
  CONSTRAINT `FK_WAREHOUSE_STORE_WAREHOUSE` FOREIGN KEY (`warehouse_id`) REFERENCES ' . $warehouseTable . ' (`warehouse_id`) ON DELETE CASCADE ON UPDATE CASCADE, 
  CONSTRAINT `FK_WAREHOUSE_STORE_STORE` FOREIGN KEY (`store_id`) REFERENCES ' . $storeTable . ' (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->addColumn( $quoteTable, 'qtys', 'text null default null' );
	$connection->addColumn( $warehouseTable, 'priority', 'smallint(6) unsigned not null default 0 after stock_id' );
	$connection->addColumn( $warehouseTable, 'description', 'text null default null after title' );
	$installer->run( 'UPDATE `' . $warehouseTable . '` SET `priority` = `stock_id`' );
	$installer->run( '
DROP TABLE IF EXISTS `' . $warehouseShippingCarrierTable . '`;

CREATE TABLE `' . $warehouseShippingCarrierTable . '` (
  `warehouse_id` smallint(6) unsigned not null, 
  `shipping_carrier` varchar(255) not null, 
  PRIMARY KEY  (`warehouse_id`, `shipping_carrier`), 
  KEY `FK_WAREHOUSE_SHIPPING_CARRIER_WAREHOUSE` (`warehouse_id`), 
  KEY `IDX_SHIPPING_CARRIER` (`shipping_carrier`), 
  CONSTRAINT `FK_WAREHOUSE_SHIPPING_CARRIER_WAREHOUSE` FOREIGN KEY (`warehouse_id`) REFERENCES ' . $warehouseTable . ' (`warehouse_id`) 
      ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->addColumn( $productShelfTable, 'stock_id', 'smallint(6) unsigned not null after product_id' );
	$connection->addKey( $productShelfTable, 'FK_CATALOG_PRODUCT_SHELF_STOCK', 'stock_id' );
	$warehouseStmt = $connection->query( 'SELECT * FROM ' . $warehouseTable );

	if ($warehouse = $warehouseStmt->fetch(  )) {
		$connection->query( 'UPDATE ' . $productShelfTable . ' SET `stock_id` = ' . $connection->quote( $warehouse['stock_id'] ) . ' WHERE `warehouse_id` = ' . $connection->quote( $warehouse['warehouse_id'] ) );
	}

	$connection->dropColumn( $productShelfTable, 'warehouse_id' );
	$connection->addConstraint( 'FK_CATALOG_PRODUCT_SHELF_STOCK', $productShelfTable, 'stock_id', $stockTable, 'stock_id' );
	$connection->dropColumn( $quoteItemTable, 'warehouse_id' );
	$connection->dropColumn( $orderItemTable, 'warehouse_id' );
	$connection->dropColumn( $invoiceItemTable, 'warehouse_id' );
	$connection->dropColumn( $shipmentItemTable, 'warehouse_id' );
	$connection->dropColumn( $creditmemoItemTable, 'warehouse_id' );
	$connection->addColumn( $orderGridWarehouseTable, 'stock_id', 'smallint(6) unsigned not null' );
	$connection->addColumn( $invoiceGridWarehouseTable, 'stock_id', 'smallint(6) unsigned not null' );
	$connection->addColumn( $shipmentGridWarehouseTable, 'stock_id', 'smallint(6) unsigned not null' );
	$connection->addColumn( $creditmemoGridWarehouseTable, 'stock_id', 'smallint(6) unsigned not null' );
	$connection->addKey( $orderGridWarehouseTable, 'FK_WAREHOUSE_ORDER_GRID_WAREHOUSE_STOCK', 'stock_id' );
	$connection->addKey( $invoiceGridWarehouseTable, 'FK_WAREHOUSE_INVOICE_GRID_WAREHOUSE_STOCK', 'stock_id' );
	$connection->addKey( $shipmentGridWarehouseTable, 'FK_WAREHOUSE_SHIPMENT_GRID_WAREHOUSE_STOCK', 'stock_id' );
	$connection->addKey( $creditmemoGridWarehouseTable, 'FK_WAREHOUSE_CREDITMEMO_GRID_WAREHOUSE_STOCK', 'stock_id' );
	$warehouseStmt = $connection->query( 'SELECT * FROM ' . $warehouseTable );

	if ($warehouse = $warehouseStmt->fetch(  )) {
		$stockId = $connection->quote( $warehouse['stock_id'] );
		$warehouseId = $connection->quote( $warehouse['warehouse_id'] );
		$connection->query( 'UPDATE ' . $orderGridWarehouseTable . ' SET `stock_id` = ' . $stockId . ' WHERE `warehouse_id` = ' . $warehouseId );
		$connection->query( 'UPDATE ' . $invoiceGridWarehouseTable . ' SET `stock_id` = ' . $stockId . ' WHERE `warehouse_id` = ' . $warehouseId );
		$connection->query( 'UPDATE ' . $shipmentGridWarehouseTable . ' SET `stock_id` = ' . $stockId . ' WHERE `warehouse_id` = ' . $warehouseId );
		$connection->query( 'UPDATE ' . $creditmemoGridWarehouseTable . ' SET `stock_id` = ' . $stockId . ' WHERE `warehouse_id` = ' . $warehouseId );
	}

	$connection->addKey( $orderGridWarehouseTable, 'PRIMARY', array( 'entity_id', 'stock_id' ), 'primary' );
	$connection->addKey( $invoiceGridWarehouseTable, 'PRIMARY', array( 'entity_id', 'stock_id' ), 'primary' );
	$connection->addKey( $shipmentGridWarehouseTable, 'PRIMARY', array( 'entity_id', 'stock_id' ), 'primary' );
	$connection->addKey( $creditmemoGridWarehouseTable, 'PRIMARY', array( 'entity_id', 'stock_id' ), 'primary' );
	$connection->dropColumn( $orderGridWarehouseTable, 'warehouse_id' );
	$connection->dropColumn( $invoiceGridWarehouseTable, 'warehouse_id' );
	$connection->dropColumn( $shipmentGridWarehouseTable, 'warehouse_id' );
	$connection->dropColumn( $creditmemoGridWarehouseTable, 'warehouse_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_ORDER_GRID_WAREHOUSE_STOCK', $orderGridWarehouseTable, 'stock_id', $stockTable, 'stock_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_INVOICE_GRID_WAREHOUSE_STOCK', $invoiceGridWarehouseTable, 'stock_id', $stockTable, 'stock_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_SHIPMENT_GRID_WAREHOUSE_STOCK', $shipmentGridWarehouseTable, 'stock_id', $stockTable, 'stock_id' );
	$connection->addConstraint( 'FK_WAREHOUSE_CREDITMEMO_GRID_WAREHOUSE_STOCK', $creditmemoGridWarehouseTable, 'stock_id', $stockTable, 'stock_id' );

	if (Mage::helper( 'innoexts_core/version' )->isGe1600(  )) {
		$shippingTablerateIndexes = $connection->getIndexList( $shippingTablerateTable );
		foreach ($shippingTablerateIndexes as $index) {

			if ($index['INDEX_TYPE'] == INDEX_TYPE_UNIQUE) {
				$connection->dropIndex( $shippingTablerateTable, $index['KEY_NAME'] );
				continue;
			}
		}

		$connection->addIndex( $installer->getTable( 'shipping/tablerate' ), $installer->getIdxName( 'shipping/tablerate', array( 'website_id', 'dest_country_id', 'dest_region_id', 'dest_zip', 'condition_name', 'condition_value', 'warehouse_id' ), INDEX_TYPE_UNIQUE ), array( 'website_id', 'dest_country_id', 'dest_region_id', 'dest_zip', 'condition_name', 'condition_value', 'warehouse_id' ), INDEX_TYPE_UNIQUE );
	}

	$installer->endSetup(  );
?>