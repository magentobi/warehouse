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

	class Innoexts_Warehouse_Model_Sales_Order_Shipment_Item extends Mage_Sales_Model_Order_Shipment_Item {
		private $_warehouse = null;

		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve warehouse
     *
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			if (is_null( $this->_warehouse )) {
				if ($this->getStockId(  )) {
					$this->_warehouse = $this->getWarehouseHelper(  )->getWarehouseByStockId( $this->getStockId(  ) );
				}
			}

			return $this->_warehouse;
		}

		/**
     * Get warehouse title
     * 
     * @return string
     */
		function getWarehouseTitle() {
			$warehouse = $this->getWarehouse(  );

			if ($warehouse) {
				return $warehouse->getTitle(  );
			}

		}

		/**
     * Clear invoice object data
     *
     * @param string $key data key
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Shipment_Item
     */
		function unsetData($key = null) {
			parent::unsetData( $key );

			if (is_null( $key )) {
				$this->_warehouse = null;
			}

			return $this;
		}
	}

?>