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

	class Innoexts_Warehouse_Model_Mysql4_Sales_Order_Shipment extends Mage_Sales_Model_Mysql4_Order_Shipment {
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
     * @return Innoexts_Warehouse_Model_Mysql4_Sales_Order_Shipment
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

				$shipmentItemTable = $this->getTable( 'sales/shipment_item' );
				$shipmentWarehouseTable = $this->getTable( 'warehouse/shipment_grid_warehouse' );
				$write = $this->_getWriteAdapter(  );
				$write->delete( $shipmentWarehouseTable, 'entity_id IN ' . $write->quoteInto( '(?)', $ids ) );
				$select = $write->select(  )->from( array( 'shipment_item_table' => $shipmentItemTable ), array( 'parent_id', 'stock_id' ) )->where( 'shipment_item_table.parent_id IN(?)', $ids )->distinct( true );
				$write->query( $select->insertFromSelect( $shipmentWarehouseTable, array( 'entity_id', 'stock_id' ), false ) );
			}

			return $this;
		}
	}

?>