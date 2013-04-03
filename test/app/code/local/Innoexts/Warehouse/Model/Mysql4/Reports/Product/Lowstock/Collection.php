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

	class Innoexts_Warehouse_Model_Mysql4_Reports_Product_Lowstock_Collection extends Mage_Reports_Model_Mysql4_Product_Lowstock_Collection {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Join catalog inventory stock item table for further stock_item values filters
     *
     * @return Mage_Reports_Model_Mysql4_Product_Collection
     */
		function joinInventoryItem($fields = array(  )) {
			return $this;
		}

		/**
     * Add Use Manage Stock Condition to collection
     *
     * @param int|null $storeId
     * @return Mage_Reports_Model_Mysql4_Product_Collection
     */
		function useManageStockFilter($storeId = null) {
			return $this;
		}

		/**
     * Add Notify Stock Qty Condition to collection
     *
     * @param int $storeId
     * @return Mage_Reports_Model_Mysql4_Product_Collection
     */
		function useNotifyStockQtyFilter($storeId = null) {
			return $this;
		}
	}

?>