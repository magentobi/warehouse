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

	$installer = $warehousesStocks;
	$connection = $installer->getConnection(  );
	$warehouseTable = $installer->getTable( 'warehouse' );
	$warehouseAreaTable = $installer->getTable( 'warehouse/warehouse_area' );
	$catalogInventoryStockTable = $installer->getTable( 'cataloginventory/stock' );
	$installer->startSetup(  );
	$connection->addColumn( $warehouseTable, 'code', 'varchar(32) not null default \'\' AFTER `warehouse_id`' );
	$connection->addKey( $warehouseTable, 'IDX_CODE', 'code' );
	$connection->addColumn( $warehouseTable, 'notify', 'tinyint(1) unsigned not null default 0 AFTER `stock_id`' );
	$connection->addColumn( $warehouseTable, 'contact_name', 'varchar(64) not null default \'\' AFTER `notify`' );
	$connection->addColumn( $warehouseTable, 'contact_email', 'varchar(64) not null default \'\' AFTER `contact_name`' );
	$installer->run( 'UPDATE `' . $warehouseTable . '` SET `code` = LOWER(`title`)' );
	$installer->run( '
DROP TABLE IF EXISTS `' . $warehouseAreaTable . '`;

CREATE TABLE `' . $warehouseAreaTable . '` (
  `warehouse_area_id` int(10) unsigned not null auto_increment, 
  `warehouse_id` smallint(6) unsigned not null, 
  `country_id` varchar(4) not null default \'0\', 
  `region_id` int(10) not null default \'0\', 
  `zip` varchar(10) not null default \'\', 
  PRIMARY KEY  (`warehouse_area_id`), 
  KEY `FK_WAREHOUSE_AREA_WAREHOUSE` (`warehouse_id`), 
  KEY `IDX_COUNTRY` (`country_id`), 
  KEY `IDX_REGION` (`region_id`), 
  KEY `IDX_ZIP` (`zip`), 
  CONSTRAINT `FK_WAREHOUSE_AREA_WAREHOUSE` FOREIGN KEY (`warehouse_id`) REFERENCES ' . $warehouseTable . ' (`warehouse_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
' );
	$connection->dropColumn( $warehouseTable, 'origin_latitude' );
	$connection->dropColumn( $warehouseTable, 'origin_longitude' );
	$warehousesStocks = array(  );
	$warehousesStmt = $connection->query( 'SELECT * FROM ' . $warehouseTable );
	$hasWarehouses = false;

	if ($warehouse = $warehousesStmt->fetch(  )) {
		$hasWarehouses = true;
		$stockId = null;

		if (( isset( $warehouse['stock_id'] ) && $warehouse['stock_id'] )) {
			$stock = $connection->query( 'SELECT * FROM ' . $catalogInventoryStockTable . ' WHERE `stock_id` = ' . $connection->quote( $warehouse['stock_id'] ) )->fetch(  );

			if (count( $stock )) {
				$stockId = $stock['stock_id'];
			}
		}


		if (( $stockId && !in_array( $stockId, $warehousesStocks ) )) {
			$connection->query( 'UPDATE ' . $catalogInventoryStockTable . ' SET `stock_name` = ' . $connection->quote( $warehouse['title'] ) . ' WHERE `stock_id` = ' . $connection->quote( $stockId ) );
		} 
else {
			$connection->query( 'INSERT INTO ' . $catalogInventoryStockTable . ' (`stock_name`) VALUES (' . $connection->quote( $warehouse['title'] ) . ')' );
			$stockId = $connection->lastInsertId( $catalogInventoryStockTable );
		}


		if ($stockId) {
			if (( isset( $warehouse['stock_id'] ) && $warehouse['stock_id'] != $stockId )) {
				$connection->query( 'UPDATE ' . $warehouseTable . ' SET `stock_id` = ' . $connection->quote( $stockId ) . ' WHERE `warehouse_id` = ' . $connection->quote( $warehouse['warehouse_id'] ) );
			}

			$warehousesStocks[$warehouse['warehouse_id']] = $stockId;
		}
	}


	if (count( $warehousesStocks )) {
		$connection->query( 'DELETE FROM ' . $catalogInventoryStockTable . ' WHERE `stock_id` NOT IN (' . join( ', ', $warehousesStocks ) . ') AND (`stock_id` <> 1)' );
	} 
else {
		$connection->query( 'DELETE FROM ' . $catalogInventoryStockTable . ' WHERE (`stock_id` <> 1)' );
	}


	if (!$hasWarehouses) {
		$connection->query( 'INSERT INTO ' . $warehouseTable . ' (`code`, `title`, `stock_id`) VALUES (' . $connection->quote( 'default' ) . ', ' . $connection->quote( 'Default' ) . ', 1)' );
	}

	$installer->endSetup(  );
?>