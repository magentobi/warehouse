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

	$installer = $connection;
	$connection = $installer->getConnection(  );
	$warehouseAreaTable = $installer->getTable( 'warehouse/warehouse_area' );
	$installer->startSetup(  );
	$connection->changeColumn( $warehouseAreaTable, 'zip', 'zip', 'varchar(21) null default null' );
	$connection->addColumn( $warehouseAreaTable, 'is_zip_range', 'tinyint(1) unsigned not null default 0 after `zip`' );
	$connection->addColumn( $warehouseAreaTable, 'from_zip', 'int(10) unsigned null default null after `is_zip_range`' );
	$connection->addKey( $warehouseAreaTable, 'IDX_WAREHOUSE_AREA_FROM_ZIP', array( 'from_zip' ), 'index' );
	$connection->addColumn( $warehouseAreaTable, 'to_zip', 'int(10) unsigned null default null after `from_zip`' );
	$connection->addKey( $warehouseAreaTable, 'IDX_WAREHOUSE_AREA_TO_ZIP', array( 'to_zip' ), 'index' );
	$installer->endSetup(  );
?>