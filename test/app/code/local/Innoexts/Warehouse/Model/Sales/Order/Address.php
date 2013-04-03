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

	class Innoexts_Warehouse_Model_Sales_Order_Address extends Mage_Sales_Model_Order_Address {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get warehouse
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			$warehouse = null;

			if ($stockId = $this->getStockId(  )) {
				$warehouse = $this->getWarehouseHelper(  )->getWarehouseByStockId( $stockId );
			}

			return $warehouse;
		}

		/**
     * Get warehouse title
     * 
     * @return string
     */
		function getWarehouseTitle() {
			$warehouse = $this->getWarehouse(  );
			return ($warehouse ? $warehouse->getTitle(  ) : null);
		}
	}

?>