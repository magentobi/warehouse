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

	class Innoexts_Warehouse_Model_Sales_Order_Observer {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * After collection load
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Observer
     */
		function afterCollectionLoad($observer) {
			$collection = $observer->getEvent(  )->getOrderCollection(  );

			if (( !$collection || !$collection->hasFlag( 'appendStockIds' ) )) {
				return $this;
			}

			$orderIds = array(  );
			foreach ($collection as $order) {
				array_push( $orderIds, $order->getId(  ) );
			}


			if (!count( $orderIds )) {
				return $this;
			}

			$orderItemTable = $collection->getTable( 'sales/order_item' );
			$adapter = $collection->getConnection(  );
			$select = $adapter->select(  )->from( $orderItemTable, array( 'order_id' => 'order_id', 'stock_id' => 'stock_id' ) )->where( $adapter->quoteInto( 'order_id IN (?)', $orderIds ) );
			$query = $adapter->query( $select );
			$stockIds = array(  );

			if ($orderItem = $query->fetch(  )) {
				$stockId = (isset( $orderItem['stock_id'] ) ? (int)$orderItem['stock_id'] : null);
				$orderId = $orderItem['order_id'];

				if ($stockId) {
					$stockIds[$orderId][] = $stockId;
				}
			}

			foreach ($collection as $order) {
				$order->setStockIds( (isset( $stockIds[$order->getId(  )] ) ? $stockIds[$order->getId(  )] : array(  )) );
			}

			return $this;
		}
	}

?>