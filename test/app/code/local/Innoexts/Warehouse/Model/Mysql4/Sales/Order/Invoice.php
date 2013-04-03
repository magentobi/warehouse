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

	class Innoexts_Warehouse_Model_Mysql4_Sales_Order_Invoice extends Mage_Sales_Model_Mysql4_Order_Invoice {
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
     * @return Innoexts_Warehouse_Model_Mysql4_Sales_Order_Invoice
     */
		function updateGridRecords($ids) {
			parent::updateGridRecords( $ids );

			if ($this->_grid) {
				if (!is_array( $ids )) {
					$ids = array( $ids );
				}


				if (( $this->_eventPrefix && $this->_eventObject )) {
					$proxy = ;
					$proxy->setIds( $ids )->setData( $this->_eventObject, $this );
					Mage::dispatchEvent( $this->_eventPrefix . '_update_grid_records', array( 'proxy' => $proxy ) );
					$proxy->getIds(  );
					$ids = new Varien_Object(  );
				}


				if (empty( $$ids )) {
					return $this;
				}

				$invoiceItemTable = $this->getTable( 'sales/invoice_item' );
				$invoiceWarehouseTable = $this->getTable( 'warehouse/invoice_grid_warehouse' );
				$write = $this->_getWriteAdapter(  );
				$write->delete( $invoiceWarehouseTable, 'entity_id IN ' . $write->quoteInto( '(?)', $ids ) );
				$select = $write->select(  )->from( array( 'invoice_item_table' => $invoiceItemTable ), array( 'parent_id', 'stock_id' ) )->where( 'invoice_item_table.parent_id IN(?)', $ids )->distinct( true );
				$write->query( $select->insertFromSelect( $invoiceWarehouseTable, array( 'entity_id', 'stock_id' ), false ) );
			}

			return $this;
		}
	}

?>