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

	$installer = $orderItemTable;
	$connection = $installer->getConnection(  );
	$installer->startSetup(  );
	$helper = Mage::helper( 'warehouse' );
	$defaultStockId = $helper->getCatalogInventoryHelper(  )->getDefaultStockId(  );
	$orderTable = $installer->getTable( 'sales/order' );
	$orderItemTable = $installer->getTable( 'sales/order_item' );
	$orderGridWarehouseTable = $installer->getTable( 'warehouse/order_grid_warehouse' );
	$installer->run( 'UPDATE `' . $orderItemTable . '` SET `stock_id` = ' . $defaultStockId . ' WHERE `stock_id` IS NULL' );
	$orders = array(  );
	$ordersStmt = $connection->query( 'SELECT * FROM ' . $orderTable );
	$orderIds = array(  );

	if ($order = $ordersStmt->fetch(  )) {
		if (isset( $order['entity_id'] )) {
			array_push( $orderIds, $order['entity_id'] );
		}
	}


	if (count( $orderIds )) {
		foreach ($orderIds as $orderId) {
			$row = $connection->query( 'SELECT * FROM ' . $orderGridWarehouseTable . ' WHERE `entity_id` = ' . $connection->quote( $orderId ) )->fetch(  );

			if (empty( $$row )) {
				$connection->query( 'INSERT INTO ' . $orderGridWarehouseTable . ' (`entity_id`, `stock_id`) VALUES (' . $connection->quote( $orderId ) . ', ' . $connection->quote( $defaultStockId ) . ')' );
				continue;
			}
		}
	}

	$invoiceTable = $installer->getTable( 'sales/invoice' );
	$invoiceItemTable = $installer->getTable( 'sales/invoice_item' );
	$invoiceGridWarehouseTable = $installer->getTable( 'warehouse/invoice_grid_warehouse' );
	$installer->run( 'UPDATE `' . $invoiceItemTable . '` SET `stock_id` = ' . $defaultStockId . ' WHERE `stock_id` IS NULL' );
	$invoices = array(  );
	$invoicesStmt = $connection->query( 'SELECT * FROM ' . $invoiceTable );
	$invoiceIds = array(  );

	if ($invoice = $invoicesStmt->fetch(  )) {
		if (isset( $invoice['entity_id'] )) {
			array_push( $invoiceIds, $invoice['entity_id'] );
		}
	}


	if (count( $invoiceIds )) {
		foreach ($invoiceIds as $invoiceId) {
			$row = $connection->query( 'SELECT * FROM ' . $invoiceGridWarehouseTable . ' WHERE `entity_id` = ' . $connection->quote( $invoiceId ) )->fetch(  );

			if (empty( $$row )) {
				$connection->query( 'INSERT INTO ' . $invoiceGridWarehouseTable . ' (`entity_id`, `stock_id`) VALUES (' . $connection->quote( $invoiceId ) . ', ' . $connection->quote( $defaultStockId ) . ')' );
				continue;
			}
		}
	}

	$shipmentTable = $installer->getTable( 'sales/shipment' );
	$shipmentItemTable = $installer->getTable( 'sales/shipment_item' );
	$shipmentGridWarehouseTable = $installer->getTable( 'warehouse/shipment_grid_warehouse' );
	$installer->run( 'UPDATE `' . $shipmentItemTable . '` SET `stock_id` = ' . $defaultStockId . ' WHERE `stock_id` IS NULL' );
	$shipments = array(  );
	$shipmentsStmt = $connection->query( 'SELECT * FROM ' . $shipmentTable );
	$shipmentIds = array(  );

	if ($shipment = $shipmentsStmt->fetch(  )) {
		if (isset( $shipment['entity_id'] )) {
			array_push( $shipmentIds, $shipment['entity_id'] );
		}
	}


	if (count( $shipmentIds )) {
		foreach ($shipmentIds as $shipmentId) {
			$row = $connection->query( 'SELECT * FROM ' . $shipmentGridWarehouseTable . ' WHERE `entity_id` = ' . $connection->quote( $shipmentId ) )->fetch(  );

			if (empty( $$row )) {
				$connection->query( 'INSERT INTO ' . $shipmentGridWarehouseTable . ' (`entity_id`, `stock_id`) VALUES (' . $connection->quote( $shipmentId ) . ', ' . $connection->quote( $defaultStockId ) . ')' );
				continue;
			}
		}
	}

	$creditmemoTable = $installer->getTable( 'sales/creditmemo' );
	$creditmemoItemTable = $installer->getTable( 'sales/creditmemo_item' );
	$creditmemoGridWarehouseTable = $installer->getTable( 'warehouse/creditmemo_grid_warehouse' );
	$installer->run( 'UPDATE `' . $creditmemoItemTable . '` SET `stock_id` = ' . $defaultStockId . ' WHERE `stock_id` IS NULL' );
	$creditmemos = array(  );
	$creditmemosStmt = $connection->query( 'SELECT * FROM ' . $creditmemoTable );
	$creditmemoIds = array(  );

	if ($creditmemo = $creditmemosStmt->fetch(  )) {
		if (isset( $creditmemo['entity_id'] )) {
			array_push( $creditmemoIds, $creditmemo['entity_id'] );
		}
	}


	if (count( $creditmemoIds )) {
		foreach ($creditmemoIds as $creditmemoId) {
			$row = $connection->query( 'SELECT * FROM ' . $creditmemoGridWarehouseTable . ' WHERE `entity_id` = ' . $connection->quote( $creditmemoId ) )->fetch(  );

			if (empty( $$row )) {
				$connection->query( 'INSERT INTO ' . $creditmemoGridWarehouseTable . ' (`entity_id`, `stock_id`) VALUES (' . $connection->quote( $creditmemoId ) . ', ' . $connection->quote( $defaultStockId ) . ')' );
				continue;
			}
		}
	}

	$installer->endSetup(  );
?>