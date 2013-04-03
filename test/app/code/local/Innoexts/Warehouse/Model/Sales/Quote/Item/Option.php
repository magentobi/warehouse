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

	class Innoexts_Warehouse_Model_Sales_Quote_Item_Option extends Mage_Sales_Model_Quote_Item_Option {
		private $_stockItem = null;
		private $_stockItems = null;
		private $_inStockStockItems = null;

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
     * Clone quote item option
     *
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item_Option
     */
		function __clone() {
			parent::__clone(  );
			$this->_stockItem = null;
			$this->_stockItems = null;
			$this->_inStockStockItems = null;
			return $this;
		}

		/**
     * Retrieve stock identifier
     * 
     * @return int
     */
		function getStockId() {
			$stockId = $this->_getData( 'stock_id' );

			if (!$stockId) {
				$item = $this->getItem(  );

				if ($item) {
					return $item->getStockId(  );
				}

				return $this->getDefaultStockId(  );
			}

			return $stockId;
		}

		/**
     * Get stock item
     *
     * @return  Mage_CatalogInventory_Model_Stock_Item
     */
		function getStockItem() {
			$stockId = $this->getStockId(  );

			if ($stockId) {
				if (( $this->_stockItem && $this->_stockItem->getStockId(  ) != $stockId )) {
					$this->_stockItem = null;
				}


				if (!$this->_stockItem) {
					if ($this->getProduct(  )) {
						$this->_stockItem = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockItem( $stockId );
						$this->_stockItem->assignProduct( $this->getProduct(  ) );
					}
				}
			}

			return $this->_stockItem;
		}

		/**
     * Unset stock item
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item_Option
     */
		function unsetStockItem() {
			$this->_stockItem = null;
			return $this;
		}

		/**
     * Get stock items collection
     * 
     * @return Mage_CatalogInventory_Model_Mysql4_Stock_Item_Collection
     */
		function getStockItemsCollection() {
			return $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockItemCollection( $this->getProductId(  ), true );
		}

		/**
     * Get stock items
     * 
     * @return array of Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function getStockItems() {
			if (is_null( $this->_stockItems )) {
				$stockItems = array(  );
				foreach ($this->getStockItemsCollection(  ) as $stockItem) {
					$stockItems[$stockItem->getStockId(  )] = $stockItem;
				}

				$this->_stockItems = $stockItems;
			}

			return $this->_stockItems;
		}

		/**
     * Unset stock items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function unsetStockItems() {
			$this->_stockItems = null;
		}

		/**
     * Get stock identifiers
     */
		function getStockIds() {
			$stockIds = array(  );
			foreach ($this->getStockItems(  ) as $stockId => $stockItem) {
				$stockIds[$stockId] = $stockId;
			}

			return $stockIds;
		}

		/**
     * Get in stock stock items
     * 
     * @return array of Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function getInStockStockItems() {
			if ($this->getLastCheckQty(  ) != $this->getQty(  )) {
				$stockItems = array(  );
				foreach ($this->getStockItems(  ) as $stockItem) {
					$result = $this->checkQty( $stockItem );

					if (!$result->getHasError(  )) {
						$stockItem->setItemBackorders( $result->getItemBackorders(  ) );
						$stockItems[$stockItem->getStockId(  )] = $stockItem;
						continue;
					}
				}

				$this->_inStockStockItems = $stockItems;
				$this->setLastCheckQty( $this->getQty(  ) );
			}

			return $this->_inStockStockItems;
		}

		/**
     * Unset in stock stock items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function unsetInStockStockItems() {
			$this->_inStockStockItems = null;
		}

		/**
     * Get in stock stock identifiers
     * 
     * @return array
     */
		function getInStockStockIds() {
			$stockIds = array(  );
			foreach ($this->getInStockStockItems(  ) as $stockItem) {
				$stockId = $stockItem->getStockId(  );
				$stockIds[$stockId] = $stockId;
			}

			return $stockIds;
		}

		/**
     * Clear order object data
     *
     * @param string $key data key
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item_Option
     */
		function unsetData($key = null) {
			parent::unsetData( $key );

			if (is_null( $key )) {
				$this->unsetStockItem(  );
				$this->unsetStockItems(  );
				$this->unsetInStockStockItems(  );
			}

			return $this;
		}
	}

?>