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

	class Innoexts_Warehouse_Model_Mysql4_Sales_Order extends Mage_Sales_Model_Mysql4_Order {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Update records in grid table
     *
     * @param array|int $ids
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Sales_Order
     */
		function updateGridRecords($ids) {
			parent::updateGridRecords( $ids );

			if ($this->_grid) {
				if (!is_array( $ids )) {
					$ids = array( $ids );
				}


				if (( $this->_eventPrefix && $this->_eventObject )) {
					$proxy = new Varien_Object(  );
					$proxy->setIds( $ids )->setData( $this->_eventObject, $this );
					Mage::dispatchEvent( $this->_eventPrefix . '_update_grid_records', array( 'proxy' => $proxy ) );
					$ids = $proxy->getIds(  );
				}


				if (empty( $$ids )) {
					return $this;
				}

				$orderItemTable = ;
				$orderWarehouseTable = $this->getTable( 'warehouse/order_grid_warehouse' );
				$write = $this->_getWriteAdapter(  );
				$write->delete( $orderWarehouseTable, 'entity_id IN ' . $write->quoteInto( '(?)', $ids ) );
				$write->select(  )->from( array( 'order_item_table' => $orderItemTable ), array( 'entity_id' => 'order_id', 'stock_id' => 'stock_id' ) )->where( 'order_item_table.order_id IN(?)', $ids )->distinct( true );
				$select = $this->getTable( 'sales/order_item' );
				$write->query( $select->insertFromSelect( $orderWarehouseTable, array( 'entity_id', 'stock_id' ), false ) );
			}

			return $this;
		}
	}

?>