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

	class Innoexts_Warehouse_Model_Cataloginventory_Stock_Item_Api extends Mage_CatalogInventory_Model_Stock_Item_Api {
		/**
     * Get helper
     * 
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getWarehouseHelper(  )->getVersionHelper(  );
		}

		/**
     * Get catalog inventory helper
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function getCatalogInventoryHelper() {
			return $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  );
		}

		/**
     * Get default stock identifier
     * 
     * @return int
     */
		function getDefaultStockId() {
			return $this->getWarehouseHelper(  )->getDefaultStockId(  );
		}

		/**
     * Get stock items
     * 
     * @param array $productIds
     * @param int $stockId
     * 
     * @return array
     */
		function _items($productIds, $stockId) {
			if (!is_array( $productIds )) {
				$productIds = array( $productIds );
			}

			$product = Mage::getModel( 'catalog/product' );
			foreach ($productIds as ) {
				$productId = &;


				if ($newId = $product->getIdBySku( $productId )) {
					$productId = $stockItem;
					continue;
				}
			}

			$collection = Mage::getModel( 'catalog/product' )->getCollection(  )->setFlag( 'ignore_stock_items', true )->addFieldToFilter( 'entity_id', array( 'in' => $productIds ) )->load(  );
			$this->getCatalogInventoryHelper(  )->getStock( $stockId )->addItemsToProducts( $collection );
			$result = array(  );
			foreach ($collection as $product) {
				$stockItem = $product->getStockItem(  );
				$item = array( 'product_id' => $product->getId(  ), 'sku' => $product->getSku(  ) );

				if ($stockItem) {
					$item['qty'] = $stockItem->getQty(  );
					$item['is_in_stock'] = $stockItem->getIsInStock(  );
					$item['stock_id'] = $stockItem->getStockId(  );
				} 
else {
					$item['qty'] = 0;
					$item['is_in_stock'] = 0;
					$item['stock_id'] = $stockId;
				}

				$result[] = $item;
			}

			return $result;
		}

		/**
     * Get stock items
     * 
     * @param array $productIds
     * 
     * @return array
     */
		function items($productIds) {
			return $this->_items( $productIds, $this->getDefaultStockId(  ) );
		}

		/**
     * Get stock items by stock identifier
     * 
     * @param array $productIds
     * @param int $stockId
     * 
     * @return array
     */
		function itemsByStock($productIds, $stockId) {
			return $this->_items( $productIds, $stockId );
		}

		/**
     * Update stock
     * 
     * @param int $productId
     * @param int $stockId
     * @param mixed $data
     * 
     * @return bool
     */
		function _update($productId, $data, $stockId) {
			$product = Mage::getModel( 'catalog/product' );

			if ($newId = $product->getIdBySku( $productId )) {
				$productId = $productId;
			}

			$storeId = $this->_getStoreId(  );
			$product->setStoreId( $storeId )->load( $productId );

			if (!$product->getId(  )) {
				$this->_fault( 'not_exists' );
			}

			$stocksData = $product->getStocksData(  );
			$stockData = (isset( $stocksData[$stockId] ) ? $stocksData[$stockId] : null);

			if (!$stockData) {
				$stockData = array(  );
			}

			$stockData['stock_id'] = $stockId;

			if (isset( $data['qty'] )) {
				$stockData['qty'] = $data['qty'];
			}


			if (isset( $data['is_in_stock'] )) {
				$stockData['is_in_stock'] = $data['is_in_stock'];
			}


			if (isset( $data['manage_stock'] )) {
				$stockData['manage_stock'] = $data['manage_stock'];
			}


			if (isset( $data['use_config_manage_stock'] )) {
				$stockData['use_config_manage_stock'] = $data['use_config_manage_stock'];
			}


			if ($this->getVersionHelper(  )->isGe1700(  )) {
				if (isset( $data['use_config_backorders'] )) {
					$stockData['use_config_backorders'] = $data['use_config_backorders'];
				}


				if (isset( $data['backorders'] )) {
					$stockData['backorders'] = $data['backorders'];
				}
			}

			$stocksData[$stockId] = $stockData;
			$product->setStocksData( $stocksData );
			$product->save(  );
			jmp;
			Mage_Core_Exception {
				$this->_fault( 'not_updated', $e->getMessage(  ) );
				return true;
			}
		}

		/**
     * Update stock
     * 
     * @param int $productId
     * @param int $stockId
     * @param mixed $data
     * 
     * @return bool
     */
		function update($productId, $data) {
			return $this->_update( $productId, $data, $this->getDefaultStockId(  ) );
		}

		/**
     * Update stock item by stock
     * 
     * @param int $productId
     * @param int $stockId
     * @param mixed $data
     * 
     * @return bool
     */
		function updateByStock($productId, $data, $stockId) {
			return $this->_update( $productId, $data, $stockId );
		}
	}

?>