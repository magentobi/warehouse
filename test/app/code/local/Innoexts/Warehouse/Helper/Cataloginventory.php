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

	class Innoexts_Warehouse_Helper_Cataloginventory extends Mage_Core_Helper_Abstract {
		private $_stocks = null;
		private $_stockItemCache = null;
		private $_stockItemsCache = null;

		/**
     * Get default stock identifier
     * 
     * @return int
     */
		function getDefaultStockId() {
			return DEFAULT_STOCK_ID;
		}

		/**
     * Get stock
     *
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Stock
     */
		function getStock($stockId = null) {
			$stock = Mage::getModel( 'cataloginventory/stock' );

			if ($stockId) {
				$stock->setStockId( $stockId );
			}

			return $stock;
		}

		/**
     * Get stock singleton
     *
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Stock
     */
		function getStockSingleton($stockId = null) {
			$stock = Mage::getSingleton( 'cataloginventory/stock' );

			if ($stockId) {
				$stock->setStockId( $stockId );
			}

			return $stock;
		}

		/**
     * Get stock resource
     * 
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Mysql4_Stock
     */
		function getStockResource($stockId = null) {
			$stock = Mage::getResourceModel( 'cataloginventory/stock' );

			if ($stockId) {
				$stock->setStockId( $stockId );
			}

			return $stock;
		}

		/**
     * Get stock resource singleton
     * 
     * @param   int $stockId
     * 
     * @return  Mage_Cataloginventory_Model_Mysql4_Stock
     */
		function getStockResourceSingleton($stockId = null) {
			$stock = Mage::getResourceSingleton( 'cataloginventory/stock' );

			if ($stockId) {
				$stock->setStockId( $stockId );
			}

			return $stock;
		}

		/**
     * Get stock item
     *
     * @param   int $stockId
     * 
     * @return  Mage_Cataloginventory_Model_Stock_Item
     */
		function getStockItem($stockId = null) {
			$stockItem = Mage::getModel( 'cataloginventory/stock_item' );

			if ($stockId) {
				$stockItem->setStockId( $stockId );
			}

			return $stockItem;
		}

		/**
     * Get stock item singleton
     *
     * @param   int $stockId
     * 
     * @return  Mage_Cataloginventory_Model_Stock_Item
     */
		function getStockItemSingleton($stockId = null) {
			$stockItem = Mage::getSingleton( 'cataloginventory/stock_item' );

			if ($stockId) {
				$stockItem->setStockId( $stockId );
			}

			return $stockItem;
		}

		/**
     * Get stock item cached
     * 
     * @param int $productId
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Stock_Item
     */
		function getStockItemCached($productId, $stockId = null) {
			$stockId = ($stockId ? $stockId : 0);

			if (( !isset( $this->_stockItemCache[$productId] ) || !isset( $this->_stockItemCache[$productId][$stockId] ) )) {
				$this->_stockItemCache[$productId][$stockId] = $this->getStockItem( $stockId );
			}

			return $this->_stockItemCache[$productId][$stockId];
		}

		/**
     * Unset stock item cached 
     * 
     * @param int $productId
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function unsetStockItemCached($productId) {
			if (isset( $this->_stockItemCache[$productId] )) {
				unset( $this->_stockItemCache[$productId] );
			}

			return $this;
		}

		/**
     * Get stock items cached
     * 
     * @param int $productId 
     * 
     * @return array of Mage_Cataloginventory_Model_Stock_Item
     */
		function getStockItemsCached($productId) {
			if (!isset( $this->_stockItemsCache[$productId] )) {
				$this->_stockItemsCache[$productId] = array(  );
				foreach ($this->getStockItemCollection( $productId, true ) as $stockItem) {
					$stockId = (int)$stockItem->getStockId(  );
					$this->_stockItemsCache[$productId][$stockId] = $stockItem;
				}
			}

			return $this->_stockItemsCache[$productId];
		}

		/**
     * Unset stock items cached 
     * 
     * @param int $productId
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function unsetStockItemsCached($productId) {
			if (isset( $this->_stockItemsCache[$productId] )) {
				unset( $this->_stockItemsCache[$productId] );
			}

			return $this;
		}

		/**
     * Get stock status
     *
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Stock_Status
     */
		function getStockStatus($stockId = null) {
			$stockStatus = Mage::getModel( 'cataloginventory/stock_status' );

			if ($stockId) {
				$stockStatus->setStockId( $stockId );
			}

			return $stockStatus;
		}

		/**
     * Get stock status singleton
     *
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Stock_Status
     */
		function getStockStatusSingleton($stockId = null) {
			$stockStatus = Mage::getSingleton( 'cataloginventory/stock_status' );

			if ($stockId) {
				$stockStatus->setStockId( $stockId );
			}

			return $stockStatus;
		}

		/**
     * Get stock collection
     * 
     * @return Mage_CatalogInventory_Model_Mysql4_Stock_Collection
     */
		function getStockCollection() {
			return $this->getStockSingleton(  )->getCollection(  );
		}

		/**
     * Get stock item collection
     * 
     * @param int|null $productId
     * @param bool $inStockOnly
     * 
     * @return Mage_CatalogInventory_Model_Mysql4_Stock_Item_Collection
     */
		function getStockItemCollection($productId = null, $inStockOnly = false) {
			$collection = $this->getStockItemSingleton(  )->getCollection(  );

			if (!is_null( $productId )) {
				$collection->addProductsFilter( array( $productId ) );
			}


			if ($inStockOnly) {
				$collection->addInStockFilter( $this->getManageStock(  ) );
			}

			return $collection;
		}

		/**
     * Get stocks
     * 
     * @return array of Mage_Cataloginventory_Model_Stock
     */
		function getStocks() {
			if (is_null( $this->_stocks )) {
				$stocks = array(  );
				foreach ($this->getStockCollection(  ) as $stock) {
					$stocks[$stock->getId(  )] = $stock;
				}

				$this->_stocks = $stocks;
			}

			return $this->_stocks;
		}

		/**
     * Get stock ids
     * 
     * @return array
     */
		function getStockIds() {
			return array_keys( $this->getStocks(  ) );
		}

		/**
     * Check if stock id exists
     * 
     * @param int $stockId
     * 
     * @return bool
     */
		function isStockIdExists($stockId) {
			$stockIds = $this->getStockIds(  );
			return in_array( $stockId, $stockIds );
		}

		/**
     * Get manage stock config option value
     * 
     * @return int
     */
		function getManageStock() {
			return (int)Mage::getStoreConfig( XML_PATH_MANAGE_STOCK );
		}

		/**
     * Get notify stock qty config option value
     * 
     * @return int
     */
		function getNotifyStockQty() {
			return (int)Mage::getStoreConfig( XML_PATH_NOTIFY_STOCK_QTY );
		}

		/**
     * Get stock item options (used in config)
     *
     * @return array
     */
		function getConfigItemOptions() {
			return Mage::helper( 'cataloginventory' )->getConfigItemOptions(  );
		}
	}

?>