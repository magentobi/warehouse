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

	class Innoexts_Warehouse_Model_Sales_Quote_Item extends Mage_Sales_Model_Quote_Item {
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
     * Get catalog inventory helper
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function getCatalogInventoryHelper() {
			return $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  );
		}

		/**
     * Clone quote item
     *
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function __clone() {
			parent::__clone(  );
			$this->_stockItem = null;
			$this->_stockItems = null;
			$this->_inStockStockItems = null;
			$this->setData( 'product', clone $this->_getData( 'product' ) );
			return $this;
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

			if ($warehouse) {
				return $warehouse->getTitle(  );
			}

		}

		/**
     * Get warehouse description
     * 
     * @return string
     */
		function getWarehouseDescription() {
			$warehouse = $this->getWarehouse(  );

			if ($warehouse) {
				return $warehouse->getDescription(  );
			}

		}

		/**
     * Get product
     * 
     * @return Mage_Catalog_Model_Product
     */
		function _getProduct() {
			return $this->_getData( 'product' );
		}

		/**
     * Set product
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function _setProduct($product) {
			$this->setData( 'product', $product );
			return $this;
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function _getStockId() {
			return $this->_getData( 'stock_id' );
		}

		/**
     * Set stock identifier
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function _setStockId($stockId) {
			$this->setData( 'stock_id', $stockId );
			return $this;
		}

		/**
     * Get stock item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function _getStockItem() {
			return $this->_stockItem;
		}

		/**
     * Set stock item
     * 
     * @param Innoexts_Warehouse_Model_Cataloginventory_Stock_Item $stockItem
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function _setStockItem($stockItem) {
			$this->_stockItem = $stockItem;
			return $this;
		}

		/**
     * Unset stock item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function _unsetStockItem() {
			if (!is_null( $this->_stockItem )) {
				$this->_stockItem = null;
			}

			return $this;
		}

		/**
     * Set product
     * 
     * @param   Mage_Catalog_Model_Product $product
     * 
     * @return  Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function setProduct($product) {
			if (( $this->getQuote(  ) && $this->getQuote(  )->isDuplicatedProductId( $product->getId(  ) ) )) {
				clone $product;
				$product = ;
			}

			$this->_unsetStockItem(  );
			$this->_setProduct( $product );
			$this->getStockItem(  );
			parent::setProduct( $product );
			return $this;
		}

		/**
     * Set stock identifier
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function setStockId($stockId) {
			$this->_unsetStockItem(  );
			$this->_setStockId( $stockId );
			$this->getStockItem(  );
			return $this;
		}

		/**
     * Get stock item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function getStockItem() {
			$stockItem = $this->_getStockItem(  );

			if (!$stockItem) {
				$stockId = $this->_getStockId(  );

				if (!$stockId) {
					$product = $this->_getProduct(  );

					if ($product) {
						$stockItem = $product->getStockItem(  );

						if ($stockItem) {
							$this->setStockItem( $stockItem );
							return $this->_getStockItem(  );
						}

						return null;
					}

					return null;
				}

				$stockItem = $this->getCatalogInventoryHelper(  )->getStockItem( $stockId );
				$this->setStockItem( $stockItem );
				return $this->_getStockItem(  );
			}

			return $this->_getStockItem(  );
		}

		/**
     * Set stock item
     * 
     * @param Innoexts_Warehouse_Model_Cataloginventory_Stock_Item $stockItem
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function setStockItem($stockItem) {
			if (( $stockItem && $stockItem->getStockId(  ) )) {
				$stockId = $stockItem->getStockId(  );
				$this->_setStockId( $stockId );
				$this->_setStockItem( $stockItem );
				$product = $this->_getProduct(  );

				if ($product) {
					$stockItem->assignProduct( $product );
				}
			}

			return $this;
		}

		/**
     * Check quote item for availability by stock item
     * 
     * @param Innoexts_Warehouse_Model_Cataloginventory_Stock_Item | null $stockItem
     * 
     * @return Varien_Object
     */
		function checkQty($stockItem = null) {
			$result = new Varien_Object(  );
			$result->setHasError( false );

			if (( !$this->getProductId(  ) || !$this->getQuote(  ) )) {
				$result->setHasError( true );
				return $result;
			}


			if ($this->getQuote(  )->getIsSuperMode(  )) {
				$result->setHasError( false );
				return $result;
			}

			$product = $this->getProduct(  );

			if (!$stockItem) {
				$stockItem = $this->getStockItem(  );
			} 
else {
				if (!$stockItem->getProduct(  )) {
					$stockItem->setProduct( $product );
				}
			}

			$qty = $this->getQty(  );
			$this->getQtyOptions(  );

			if (( $options =  && 0 < $qty )) {
				$qty = $product->getTypeInstance( true )->prepareQuoteItemQty( $qty, $product );

				if ($stockItem) {
					$result = $stockItem->checkQtyIncrements( $qty );

					if ($result->getHasError(  )) {
						return $result;
					}
				}

				foreach ($options as $option) {

					if ($stockItem) {
						$option->setStockId( $stockItem->getStockId(  ) );
					}

					$optionQty = $qty * $option->getValue(  );
					$increaseOptionQty = ($this->getQtyToAdd(  ) ? $this->getQtyToAdd(  ) : $qty) * $option->getValue(  );
					$option->unsetStockItem(  );
					$stockItem = $option->getStockItem(  );

					if (!( $stockItem instanceof Mage_CatalogInventory_Model_Stock_Item )) {
						return false;
					}

					$stockItem->setOrderedItems( 0 );
					$stockItem->setIsChildItem( true );
					$stockItem->setSuppressCheckQtyIncrements( true );
					$qtyForCheck = $result;
					$optionResult = $stockItem->checkQuoteItemQty( $optionQty, $qtyForCheck, $option->getValue(  ) );
					$stockItem->unsIsChildItem(  );

					if (!$optionResult->getHasError(  )) {
						if ($optionResult->getHasQtyOptionUpdate(  )) {
							$result->setHasQtyOptionUpdate( true );
						}


						if ($optionResult->getItemIsQtyDecimal(  )) {
							$result->setItemIsQtyDecimal( true );
						}


						if ($optionResult->getItemQty(  )) {
							$result->setItemQty( floatval( $result->getItemQty(  ) ) + $optionResult->getItemQty(  ) );
						}


						if ($optionResult->getOrigQty(  )) {
							$result->setOrigQty( floatval( $result->getOrigQty(  ) ) + $optionResult->getOrigQty(  ) );
						}


						if ($optionResult->getItemUseOldQty(  )) {
							$result->setItemUseOldQty( true );
						}


						if ($optionResult->getItemBackorders(  )) {
							$result->setItemBackorders( floatval( $result->getItemBackorders(  ) ) + $optionResult->getItemBackorders(  ) );
							continue;
						}

						continue;
					}

					return $optionResult;
				}
			} 
else {
				if (!( $stockItem instanceof Mage_CatalogInventory_Model_Stock_Item )) {
					$result->setHasError( true );
					return $result;
				}

				$rowQty = $increaseQty = 208;

				if (!$this->getParentItem(  )) {
					$increaseQty = ($this->getQtyToAdd(  ) ? $this->getQtyToAdd(  ) : $qty);
					$rowQty = $rowQty;
				} 
else {
					$rowQty = $this->getParentItem(  )->getQty(  ) * $qty;
				}

				$qtyForCheck = $option;
				$productTypeCustomOption = $product->getCustomOption( 'product_type' );

				if (!is_null( $productTypeCustomOption )) {
					if ($productTypeCustomOption->getValue(  ) == TYPE_CODE) {
						$stockItem->setIsChildItem( true );
					}
				}

				$result = $stockItem->checkQuoteItemQty( $rowQty, $qtyForCheck, $qty );

				if ($stockItem->hasIsChildItem(  )) {
					$stockItem->unsIsChildItem(  );
				}
			}

			return $result;
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
     * Get shipping address
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function getShippingAddress() {
			$quote = $this->getQuote(  );

			if ($quote) {
				return $quote->getShippingAddress2( $this->getStockId(  ) );
			}

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
     * Get available warehouses
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse
     */
		function getInStockWarehouses() {
			$warehouses = array(  );
			$stocksIds = $this->getInStockStockIds(  );

			if (count( $stocksIds )) {
				$warehouses = $this->getWarehouseHelper(  )->getWarehousesByStockIds( $stocksIds );
			}

			return $warehouses;
		}

		/**
     * Clear order object data
     *
     * @param string $key data key
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order
     */
		function unsetData($key = null) {
			parent::unsetData( $key );

			if (is_null( $key )) {
				$this->_stockItem = null;
				$this->unsetStockItems(  );
				$this->unsetInStockStockItems(  );
			}

			return $this;
		}

		/**
     * Check if item is parent
     * 
     * @return bool
     */
		function isParentItem() {
			return (count( $this->getChildren(  ) ) ? true : false);
		}

		/**
     * Get child item
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function getChild() {
			if ($this->isParentItem(  )) {
				$children = $this->getChildren(  );

				if (count( $children )) {
					return current( $children );
				}

				return null;
			}

		}

		/**
     * Check if stock identifier is static
     * 
     * @return bool
     */
		function isStockIdStatic() {
			return ($this->getIsStockIdStatic(  ) ? true : false);
		}

		/**
     * Sort stock identifiers
     * 
     * @param int $stockId1
     * @param int $stockId2
     * 
     * @return int
     */
			$productHelper = function sortStockIds($stockId1, $stockId2) {;
			$product = $this->getProduct(  );
			$priority1 = $productHelper->getStockPriority( $product, $stockId1 );
			$productHelper->getStockPriority( $product, $stockId2 );
			$priority2 = $this->getWarehouseHelper(  )->getProductHelper(  );

			if ($priority1 != $priority2) {
				return ($priority1 < $priority2 ? -1 : 1);
			}

			return 0;
		}

		/**
     * Get complex item splitted stock quantities
     * 
     * @param array $children
     * @param string $childQtyMethod
     * 
     * @return array
     */
		function _getContainerItemSplittedStockQtys($children, $childQtyMethod) {
			$stockQtys = array(  );
			$qty = $this->getQty(  );
			foreach ($children as $childItem) {
				$childProductId = $childItem->getProductId(  );
				$childQty = $childItem->$childQtyMethod(  );

				if ($childQty <= 0) {
					$childQty = 183;
				}

				$totalQty = $qty * $childQty;
				foreach ($childItem->getStockItems(  ) as $stockId => $stockItem) {
					$stockQty = $stockItem->getMaxStockQty( $totalQty );

					if (( $stockQty !== false && 0 < $stockQty )) {
						$stockQtys[$stockId][$childProductId] = floor( $stockQty / $childQty );
						continue;
					}

					$stockQtys[$stockId][$childProductId] = null;
				}
			}

			$_stockQtys = $childProductId;
			$stockQtys = array(  );
			$stockIds = $this->getStockIds(  );
			foreach ($_stockQtys as $stockId => $_qtys) {
				$_qty = null;

				if (in_array( $stockId, $stockIds )) {
					foreach ($children as $childItem) {
						$childProductId = $childItem->getProductId(  );

						if (( !isset( $_qtys[$childProductId] ) && is_null( $_qtys[$childProductId] ) )) {
							$_qty = null;
							break;
						}


						if (( is_null( $_qty ) || $_qtys[$childProductId] < $_qty )) {
							$_qty = $_qtys[$childProductId];
							continue;
						}
					}
				}

				$stockQtys[$stockId] = $_qty;
			}

			$_stockQtys = $childProductId;
			$stockQtys = array(  );
			$totalQty = $this->getQty(  );
			$stockIds = array(  );
			foreach ($_stockQtys as $stockId => $_qty) {
				array_push( $stockIds, $stockId );
			}

			usort( $stockIds, array( $this, 'sortStockIds' ) );
			foreach ($stockIds as $stockId) {

				if (isset( $_stockQtys[$stockId] )) {
					$_qty = $_stockQtys[$stockId];

					if (!is_null( $_qty )) {
						if ($_qty < $totalQty) {
							$stockQtys[$stockId] = $_qty;
							$totalQty -= $childQtyMethod;
							continue;
						}

						$stockQtys[$stockId] = $totalQty;
						$totalQty = 182;
						break;
					}

					continue;
				}
			}


			if (0 < $totalQty) {
				$stockQtys = array(  );
			}

			return $stockQtys;
		}

		/**
     * Get complex item splitted stock quantities
     * 
     * @return array
     */
		function getContainerItemSplittedStockQtys() {
			$stockQtys = array(  );

			if ($this->isParentItem(  )) {
				$stockQtys = $this->_getContainerItemSplittedStockQtys( $this->getChildren(  ), 'getQty' );
			}

			return $stockQtys;
		}

		/**
     * Get simple item splitted stock quantities
     * 
     * @return array
     */
		function getSimpleItemSplittedStockQtys() {
			$stockQtys = array(  );

			if (!count( $this->getQtyOptions(  ) )) {
				$totalQty = $this->getTotalQty(  );
				$stockItems = $this->getStockItems(  );
				$stockIds = $this->getStockIds(  );
				usort( $stockIds, array( $this, 'sortStockIds' ) );
				foreach ($stockIds as $stockId) {

					if (isset( $stockItems[$stockId] )) {
						$stockItem = $stockItems[$stockId];
						$stockQty = $stockItem->getMaxStockQty( $totalQty );

						if (( $stockQty !== false && 0 < $stockQty )) {
							$stockQtys[$stockId] = $stockQty;
							$totalQty -= $this;

							if ($totalQty <= 0) {
								break;
								continue;
							}

							continue;
						}

						continue;
					}
				}


				if (0 < $totalQty) {
					$stockQtys = array(  );
				}
			} 
else {
				$stockQtys = $this->_getContainerItemSplittedStockQtys( $this->getQtyOptions(  ), 'getValue' );
			}

			return $stockQtys;
		}

		/**
     * Get splitted stock quantities
     * 
     * @return array
     */
		function getSplittedStockQtys() {
			$stockQtys = array(  );

			if ($this->isParentItem(  )) {
				$stockQtys = $this->getContainerItemSplittedStockQtys(  );
			} 
else {
				$stockQtys = $this->getSimpleItemSplittedStockQtys(  );
			}

			return $stockQtys;
		}

		/**
     * Get splitted stock data
     * 
     * @return array of Varien_Object
     */
		function getSplittedStockData() {
			$stockData = array(  );
			$stockQtys = $this->getSplittedStockQtys(  );

			if (count( $stockQtys )) {
				$productId = $this->getProductId(  );
				foreach ($stockQtys as $stockId) {
					$stockIds = array( $stockId => $stockId );
					$stockItems = array(  );
					foreach ($this->getStockItems(  ) as $_stockId => $stockItem) {

						if ($_stockId == $stockId) {
							$stockItems[$stockId] = $stockItem;
							break;
						}
					}

					$itemStockData = $qty = new Varien_Object(  );
					$itemStockData->setProductId( $productId );
					$itemStockData->setProduct( $this->getProduct(  ) );
					$itemStockData->setStockItems( $stockItems );
					$itemStockData->setStockIds( $stockIds );
					$itemStockData->setStockId( $stockId );
					$itemStockData->setIsInStock( (count( $stockIds ) ? true : false) );
					$itemStockData->setQty( $qty );

					if ($this->isParentItem(  )) {
						$children = array(  );
						foreach ($this->getChildren(  ) as $childItem) {
							$childItemStockData = $childItem->getStockData( $stockIds );
							$children[$childItem->getProductId(  )] = $childItemStockData;
						}
					} 
else {
						$children = null;
					}

					$itemStockData->setChildren( $children );
					$itemStockData->setParent( (count( $children ) ? true : false) );
					$stockData[] = $itemStockData;
				}
			}

			return $stockData;
		}

		/**
     * Get stock data
     * 
     * @param array $stockIds
     * @param bool $forceNoBackorders
     * 
     * @return Varien_Object
     */
		function getStockData($stockIds = null, $forceNoBackorders = false) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$productHelper = $helper->getProductHelper(  );
			$product = $this->getProduct(  );
			$stockData = new Varien_Object(  );
			$stockData->setProductId( $this->getProductId(  ) );
			$stockData->setProduct( $product );

			if (( !is_null( $stockIds ) && count( $stockIds ) )) {
				$_stockIds = $this->getStockIds(  );
				$_stockItems = $this->getStockItems(  );
				$__stockIds = array(  );
				$__stockItems = array(  );
				foreach ($_stockIds as $_stockId) {

					if (in_array( $_stockId, $stockIds )) {
						$__stockIds[$_stockId] = $_stockId;
						$__stockItems[$_stockId] = $_stockItems[$_stockId];
						continue;
					}
				}

				$_stockIds = $config;
				$_stockItems = $productHelper;
			} 
else {
				$_stockIds = $this->getInStockStockIds(  );
				$_stockItems = $this->getInStockStockItems(  );

				if ($forceNoBackorders) {
					$__stockIds = array(  );
					$__stockItems = array(  );
					foreach ($_stockIds as $_stockId) {

						if (( isset( $_stockItems[$_stockId] ) && !$_stockItems[$_stockId]->getItemBackorders(  ) )) {
							$__stockIds[$_stockId] = $_stockId;
							$__stockItems[$_stockId] = $_stockItems[$_stockId];
							continue;
						}
					}

					$_stockIds = $config;
					$_stockItems = $productHelper;
				}
			}


			if ($this->isParentItem(  )) {
				$children = array(  );
				foreach ($this->getChildren(  ) as $childItem) {
					$childItemStockData = $childItem->getStockData( $stockIds, $forceNoBackorders );
					$childStockIds = $childItemStockData['stock_ids'];
					foreach ($_stockIds as $_stockId) {

						if (( !isset( $childStockIds[$_stockId] ) || !$childStockIds[$_stockId] )) {
							unset( $_stockIds[$stockId] );
							unset( $_stockItems[$stockId] );
							continue;
						}
					}

					$children[$childItem->getProductId(  )] = $childItemStockData;
				}
			} 
else {
				$children = null;
			}

			$stockData->setStockItems( $_stockItems );
			$stockData->setStockIds( $_stockIds );

			if ($config->isAllowAdjustment(  )) {
				$sessionStockId = $productHelper->getSessionStockId( $product );

				if (( $sessionStockId && in_array( $sessionStockId, $_stockIds ) )) {
					$stockData->setSessionStockId( $sessionStockId );
				}
			}

			$stockData->setIsInStock( (count( $_stockIds ) ? true : false) );
			$stockData->setQty( $this->getQty(  ) );
			$stockData->setChildren( $children );
			$stockData->setParent( (count( $children ) ? true : false) );
			return $stockData;
		}

		/**
     * Check if item is splitted
     * 
     * @return boolean
     */
		function isSplitted() {
			return (( $this->getIsClone(  ) || $this->getIsCloned(  ) ) ? true : false);
		}
	}

?>