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

	class Innoexts_Warehouse_Model_Sales_Quote_Address_Item extends Mage_Sales_Model_Quote_Address_Item {
		private $_stockItem = null;

		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get default stock identifier
     */
		function getDefaultStockId() {
			return $this->getWarehouseHelper(  )->getDefaultStockId(  );
		}

		/**
     * Retrieve stock identifier
     * 
     * @return int
     */
		function getStockId() {
			$quote = $this->getQuote(  );

			if ($quote) {
				$address = $quote->getAddress(  );

				if (( $address && $address->getStockId(  ) )) {
					return $address->getStockId(  );
				}

				return $this->getDefaultStockId(  );
			}

			return $this->getDefaultStockId(  );
		}

		/**
     * Get stock item
     * 
     * @return Mage_CatalogInventory_Model_Stock_Item
     */
		function getStockItem() {
			if (!$this->_stockItem) {
				$this->_stockItem = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockItem( $this->getStockId(  ) );
				$this->_stockItem->assignProduct( $this->getProduct(  ) );
			}

			return $this->_stockItem;
		}
	}

?>