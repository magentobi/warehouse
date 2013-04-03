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

	class Innoexts_Warehouse_Block_Warehouse extends Mage_Core_Block_Template {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Check if block is enabled
     * 
     * @return bool
     */
		function isEnabled() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( !$config->isMultipleMode(  ) && $config->isAllowAdjustment(  ) )) {
				return true;
			}

			return false;
		}

		/**
     * Get warehouses
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouses() {
			return $this->getWarehouseHelper(  )->getWarehouses(  );
		}

		/**
     * Get current stock id
     * 
     * @return int
     */
		function getCurrentStockId() {
			return $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId(  );
		}

		/**
     * Get customer address stock distance string
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getCustomerAddressStockDistanceString($stockId) {
			return $this->getWarehouseHelper(  )->getCustomerAddressStockDistanceString( $stockId );
		}
	}

?>