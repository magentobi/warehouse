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

	class Innoexts_Warehouse_Model_Cataloginventory_Observer extends Mage_CatalogInventory_Model_Observer {
		private $_qtys = array(  );
		private $_checkedQuoteItems2 = array(  );

		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
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
     * Get product helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function getProductHelper() {
			return $this->getWarehouseHelper(  )->getProductHelper(  );
		}

		/**
     * Throw exception
     * 
     * @param string $message
     * @param string $helper
     */
		function throwException($message, $helper = 'cataloginventory') {
			Mage::throwException( Mage::helper( $helper )->__( $message ) );
		}

		/**
     * Get predefined stock identifier
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return int
     */
		function getStockId($quote = null) {
			return $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId( $quote );
		}

		/**
     * Get product qty includes information from all quote items
     * 
     * @param int   $productId
     * @param int   $stockId
     * @param int   $quoteItemId
     * @param float $itemQty
     * 
     * @return int
     */
		function _getQuoteItemQtyForCheck2($productId, $stockId, $quoteItemId, $itemQty) {
			$qty = $productId;

			if (!$stockId) {
				$stockId = 244;
			}


			if (( ( ( isset( $this->_checkedQuoteItems2[$productId] ) && isset( $this->_checkedQuoteItems2[$productId][$stockId] ) ) && isset( $this->_checkedQuoteItems2[$productId][$stockId]['qty'] ) ) && !in_array( $quoteItemId, $this->_checkedQuoteItems2[$productId][$stockId]['items'] ) )) {
				$qty += $this->_checkedQuoteItems2[$productId][$stockId]['qty'];
			}

			$this->_checkedQuoteItems2[$productId][$stockId]['qty'] = $qty;
			$this->_checkedQuoteItems2[$productId][$stockId]['items'][] = $quoteItemId;
			return $qty;
		}

		/**
     * Add stock information to product
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function addInventoryData($observer) {
			$product = $observer->getEvent(  )->getProduct(  );

			if (!( $product instanceof Mage_Catalog_Model_Product )) {
				return $this;
			}

			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$stockId = null;

			if ($config->isMultipleMode(  )) {
				$stockId = null;
			} 
else {
				$stockId = $this->getStockId( $product->getQuote(  ) );
			}

			$stockItem = $this->getCatalogInventoryHelper(  )->getStockItemCached( intval( $product->getId(  ) ), $stockId );

			if ($stockId) {
				$stockItem->assignProduct( $product );
			} 
else {
				$stockItem->assignAvailableProduct( $product );
			}

			return $this;
		}

		/**
     * Remove stock information
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function removeInventoryData($observer) {
			$product = $observer->getEvent(  )->getProduct(  );

			if (( !( $product instanceof Mage_Catalog_Model_Product ) || !$product->getId(  ) )) {
				return $this;
			}

			$this->getCatalogInventoryHelper(  )->unsetStockItemCached( intval( $product->getId(  ) ) );
			return $this;
		}

		/**
     * Add stock status to collection
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function addStockStatusToCollection($observer) {
			$productCollection = $observer->getEvent(  )->getCollection(  );

			if ($productCollection->hasFlag( 'ignore_stock_items' )) {
				return $this;
			}

			$stockId = $this->getStockId(  );

			if ($productCollection->hasFlag( 'require_stock_items' )) {
				$this->getCatalogInventoryHelper(  )->getStock( $stockId )->addItemsToProducts( $productCollection );
			} 
else {
				$this->getCatalogInventoryHelper(  )->getStockStatus( $stockId )->addStockStatusToProducts( $productCollection, null, $stockId );
			}

			return $this;
		}

		/**
     * Add stock items to collection
     *
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function addInventoryDataToCollection($observer) {
			$collection = $observer->getEvent(  )->getProductCollection(  );

			if (!count( $collection )) {
				return $this;
			}

			$stockId = $this->getStockId(  );
			$this->getCatalogInventoryHelper(  )->getStock( $stockId )->addItemsToProducts( $collection );
			return $this;
		}

		/**
     * Add stock status limitation to catalog product select
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Mage_CatalogInventory_Model_Observer
     */
		function prepareCatalogProductIndexSelect($observer) {
			$select = $observer->getEvent(  )->getSelect(  );
			$entity = $observer->getEvent(  )->getEntityField(  );
			$website = $observer->getEvent(  )->getWebsiteField(  );
			$stock = $observer->getEvent(  )->getStockField(  );
			$this->getCatalogInventoryHelper(  )->getStockStatusSingleton(  )->prepareCatalogProductIndexSelect2( $select, $entity, $website, $stock );
			return $this;
		}

		/**
     * Apply stock items for quote
     * 
     * @param Mage_Sales_Model_Quote $quote
     * 
     * @return Mage_CatalogInventory_Model_Observer
     */
		function applyQuoteStockItems($quote) {
			$quote->applyStocks(  );
			return $this;
		}

		/**
     * Whether quote item needs to be checked or not
     * 
     * @param $quoteItem Innoexts_Warehouse_Model_Sales_Quote_Item
     * 
     * @return bool
     */
		function isCheckQuoteItemQty($quoteItem) {
			if (( ( ( !$quoteItem || !$quoteItem->getProductId(  ) ) || !$quoteItem->getQuote(  ) ) || $quoteItem->getQuote(  )->getIsSuperMode(  ) )) {
				return false;
			}

			return true;
		}

		/**
     * Check product inventory data with qty options
     * 
     * @param  $quoteItem Innoexts_Warehouse_Model_Sales_Quote_Item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function checkQuoteItemQtyWithOptions($quoteItem) {
			$quote = $quoteItem->getQuote(  );
			$stockItem = $quoteItem->getStockItem(  );
			$product = $quoteItem->getProduct(  );
			$options = $quoteItem->getQtyOptions(  );
			$qty = $product->getTypeInstance( true )->prepareQuoteItemQty( $quoteItem->getQty(  ), $product );
			$quoteItem->setData( 'qty', $qty );

			if ($stockItem) {
				$result = $stockItem->checkQtyIncrements( $qty );

				if ($result->getHasError(  )) {
					$quoteItem->setHasError( true )->setMessage( $result->getMessage(  ) );
					$quote->setHasError( true )->addMessage( $result->getQuoteMessage(  ), $result->getQuoteMessageIndex(  ) );
				}
			}

			foreach ($options as $option) {

				if ($stockItem) {
					$option->setStockId( $stockItem->getStockId(  ) );
				}

				$optionQty = $qty * $option->getValue(  );
				$increaseOptionQty = ($quoteItem->getQtyToAdd(  ) ? $quoteItem->getQtyToAdd(  ) : $qty) * $option->getValue(  );
				$option->unsetStockItem(  );
				$stockItem = $option->getStockItem(  );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					if ($quoteItem->getProductType(  ) == TYPE_CONFIGURABLE) {
						$stockItem->setProductName( $quoteItem->getName(  ) );
					}
				}


				if (!( $stockItem instanceof Mage_CatalogInventory_Model_Stock_Item )) {
					$this->throwException( 'The stock item for Product in option is not valid.' );
				}

				$stockItem->setOrderedItems( 0 );
				$stockItem->setIsChildItem( true );
				$stockItem->setSuppressCheckQtyIncrements( true );
				$qtyForCheck = $this->_getQuoteItemQtyForCheck2( $option->getProduct(  )->getId(  ), $stockItem->getStockId(  ), $quoteItem->getId(  ), $increaseOptionQty );

				if ($optionQty < $qtyForCheck) {
					$qtyForCheck = $quote;
				}

				$result = $stockItem->checkQuoteItemQty( $optionQty, $qtyForCheck, $option->getValue(  ) );

				if (!is_null( $result->getItemIsQtyDecimal(  ) )) {
					$option->setIsQtyDecimal( $result->getItemIsQtyDecimal(  ) );
				}


				if ($result->getHasQtyOptionUpdate(  )) {
					$option->setHasQtyOptionUpdate( true );
					$quoteItem->updateQtyOption( $option, $result->getOrigQty(  ) );
					$option->setValue( $result->getOrigQty(  ) );
					$quoteItem->setData( 'qty', intval( $qty ) );
				}


				if (!is_null( $result->getMessage(  ) )) {
					$option->setMessage( $result->getMessage(  ) );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$quoteItem->setMessage( $result->getMessage(  ) );
					}
				}


				if (!is_null( $result->getItemBackorders(  ) )) {
					$option->setBackorders( $result->getItemBackorders(  ) );
				}


				if ($result->getHasError(  )) {
					$option->setHasError( true );
					$quoteItem->setHasError( true )->setMessage( $result->getQuoteMessage(  ) );
					$quote->setHasError( true )->addMessage( $result->getQuoteMessage(  ), $result->getQuoteMessageIndex(  ) );
				}

				$stockItem->unsIsChildItem(  );
			}

			return $this;
		}

		/**
     * Check product inventory data without qty options
     * 
     * @param $quoteItem Innoexts_Warehouse_Model_Sales_Quote_Item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function checkQuoteItemQtyWithoutOptions($quoteItem) {
			$quote = $quoteItem->getQuote(  );
			$quoteItem->getStockItem(  );
			$product = $quoteItem->getProduct(  );
			$qty = $quoteItem->getQty(  );

			if (!( $stockItem instanceof Mage_CatalogInventory_Model_Stock_Item )) {
				$this->throwException( 'The stock item for Product is not valid.' );
			}


			if ($quoteItem->getParentItem(  )) {
				$rowQty = $quoteItem->getParentItem(  )->getQty(  ) * $qty;
				$qtyForCheck = $this->_getQuoteItemQtyForCheck2( $product->getId(  ), $stockItem->getStockId(  ), $quoteItem->getId(  ), 0 );
			} 
else {
				$increaseQty = ($quoteItem->getQtyToAdd(  ) ? $quoteItem->getQtyToAdd(  ) : $qty);
				$rowQty = $result;
				$qtyForCheck = $this->_getQuoteItemQtyForCheck2( $product->getId(  ), $stockItem->getStockId(  ), $quoteItem->getId(  ), $increaseQty );
			}

			$productTypeCustomOption = $product->getCustomOption( 'product_type' );

			if (!is_null( $productTypeCustomOption )) {
				if ($productTypeCustomOption->getValue(  ) == TYPE_CODE) {
					$stockItem->setIsChildItem( true );
				}
			}


			if ($rowQty < $qtyForCheck) {
				$qtyForCheck = $this;
			}

			$result = $stockItem = $stockItem->checkQuoteItemQty( $rowQty, $qtyForCheck, $qty );

			if ($stockItem->hasIsChildItem(  )) {
				$stockItem->unsIsChildItem(  );
			}


			if (!is_null( $result->getItemIsQtyDecimal(  ) )) {
				$quoteItem->setIsQtyDecimal( $result->getItemIsQtyDecimal(  ) );

				if ($quoteItem->getParentItem(  )) {
					$quoteItem->getParentItem(  )->setIsQtyDecimal( $result->getItemIsQtyDecimal(  ) );
				}
			}


			if (( $result->getHasQtyOptionUpdate(  ) && ( !$quoteItem->getParentItem(  ) || $quoteItem->getParentItem(  )->getProduct(  )->getTypeInstance( true )->getForceChildItemQtyChanges( $quoteItem->getParentItem(  )->getProduct(  ) ) ) )) {
				$quoteItem->setData( 'qty', $result->getOrigQty(  ) );
			}


			if (!is_null( $result->getItemUseOldQty(  ) )) {
				$quoteItem->setUseOldQty( $result->getItemUseOldQty(  ) );
			}


			if (!is_null( $result->getMessage(  ) )) {
				$quoteItem->setMessage( $result->getMessage(  ) );

				if ($quoteItem->getParentItem(  )) {
					$quoteItem->getParentItem(  )->setMessage( $result->getMessage(  ) );
				}
			}


			if (!is_null( $result->getItemBackorders(  ) )) {
				$quoteItem->setBackorders( $result->getItemBackorders(  ) );
			}


			if ($result->getHasError(  )) {
				$quoteItem->setHasError( true );
				$quote->setHasError( true )->addMessage( $result->getQuoteMessage(  ), $result->getQuoteMessageIndex(  ) );
			}

			return $this;
		}

		/**
     * Check product inventory data
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Mage_CatalogInventory_Model_Observer
     */
		function checkQuoteItemQty($observer) {
			$quoteItem = $observer->getEvent(  )->getItem(  );

			if (!$this->isCheckQuoteItemQty( $quoteItem )) {
				return $this;
			}

			$this->applyQuoteStockItems( $quoteItem->getQuote(  ) );

			if ($quoteItem->isDeleted(  )) {
				return $this;
			}


			if (( $quoteItem->getQtyOptions(  ) && 0 < $quoteItem->getQty(  ) )) {
				$this->checkQuoteItemQtyWithOptions( $quoteItem );
			} 
else {
				$this->checkQuoteItemQtyWithoutOptions( $quoteItem );
			}

			return $this;
		}

		/**
     * Saving product inventory data. Product qty calculated dynamically.
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function saveInventoryData($observer) {
			$inventoryHelper = $this->getCatalogInventoryHelper(  );
			$product = $observer->getEvent(  )->getProduct(  );

			if (is_null( $product->getStocksData(  ) )) {
				if (( $product->getIsChangedWebsites(  ) || $product->dataHasChangedFor( 'status' ) )) {
					foreach ($inventoryHelper->getStockIds(  ) as $stockId) {
						$inventoryHelper->getStockStatusSingleton( $stockId )->updateStatus( $product->getId(  ) );
					}
				}

				return $this;
			}

			$data = $product->getStocksData(  );

			if (count( $data )) {
				$keys = $inventoryHelper->getConfigItemOptions(  );
				foreach ($inventoryHelper->getStockIds(  ) as $stockId) {
					$item = $inventoryHelper->getStockItem( $stockId )->loadByProduct( $product );
					$isEmpty = true;
					foreach ($data as $dataItem) {

						if (( isset( $dataItem['stock_id'] ) && $stockId == (int)$dataItem['stock_id'] )) {
							foreach ($keys as $key) {
								$useConfigKey = 'use_config_' . $key;

								if (( isset( $dataItem[$useConfigKey] ) && $dataItem[$useConfigKey] )) {
									$dataItem[$useConfigKey] = 1;
									continue;
								}

								$dataItem[$useConfigKey] = 0;
							}

							$item->addData( $dataItem );
							$isEmpty = false;
							break;
							continue;
						}
					}


					if ($isEmpty) {
						continue;
					}

					$item->setProduct( $product );
					foreach ($keys as $key) {

						if (is_null( $item->getData( $key ) )) {
							$item->setData( 'use_config_' . $key, 1 );
							continue;
						}
					}

					$originalQty = $item->getData( 'original_inventory_qty' );

					if (0 < strlen( $originalQty )) {
						$item->setQtyCorrection( $item->getQty(  ) - $originalQty );
					}

					$item->save(  );
				}
			}

			return $this;
		}

		/**
     * Update items stock status and low stock date.
     *
     * @param Varien_Event_Observer $observer
     * 
     * @return  Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function updateItemsStockUponConfigChange($observer) {
			$inventoryHelper = $this->getCatalogInventoryHelper(  );
			foreach ($inventoryHelper->getStockIds(  ) as $stockId) {
				$stockResourceSingleton = $inventoryHelper->getStockResourceSingleton( $stockId );
				$stockResourceSingleton->updateSetOutOfStock(  );
				$stockResourceSingleton->updateSetInStock(  );
				$stockResourceSingleton->updateLowStockDate(  );
			}

			return $this;
		}

		/**
     * Cancel order item
     *
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function cancelOrderItem($observer) {
			$item = $observer->getEvent(  )->getItem(  );
			$children = $item->getChildrenItems(  );
			$qty = $item->getQtyOrdered(  ) - max( $item->getQtyShipped(  ), $item->getQtyInvoiced(  ) ) - $item->getQtyCanceled(  );
			$item->getProductId(  );

			if (( ( ( $item->getId(  ) && $productId =  ) && empty( $$children ) ) && $qty )) {
				$this->getCatalogInventoryHelper(  )->getStockSingleton( $item->getStockId(  ) )->backItemQty( $productId, $qty );
			}

			return $this;
		}

		/**
     * Return creditmemo items qty to stock
     *
     * @param Varien_Event_Observer $observer
     */
		function refundOrderInventory($observer) {
			$inventoryHelper = $this->getCatalogInventoryHelper(  );
			$creditmemo = $observer->getEvent(  )->getCreditmemo(  );
			$items = array(  );
			$isAutoReturnEnabled = Mage::helper( 'cataloginventory' )->isAutoReturnEnabled(  );
			foreach ($creditmemo->getAllItems(  ) as $item) {
				$return = false;

				if ($item->hasBackToStock(  )) {
					if (( $item->getBackToStock(  ) && $item->getQty(  ) )) {
						$return = true;
					}
				} 
else {
					if ($isAutoReturnEnabled) {
						$return = true;
					}
				}


				if ($return) {
					$orderItem = $item->getOrderItem(  );
					$productId = $item->getProductId(  );
					$stockId = ($orderItem ? $orderItem->getStockId(  ) : $inventoryHelper->getDefaultStockId(  ));

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$parentOrderId = $item->getOrderItem(  )->getParentItemId(  );
						$parentItem = ($parentOrderId ? $creditmemo->getItemByOrderId( $parentOrderId ) : false);
						$qty = ($parentItem ? $parentItem->getQty(  ) * $item->getQty(  ) : $item->getQty(  ));

						if (( isset( $items[$productId] ) && isset( $items[$productId][$stockId] ) )) {
							$items[$productId][$stockId] += 'qty' = $qty;
							continue;
						}

						$items[$productId][$stockId] = array( 'qty' => $qty, 'item' => null );
						continue;
					}


					if (( isset( $items[$productId] ) && isset( $items[$productId][$stockId] ) )) {
						$items[$productId][$stockId] += 'qty' = $item->getQty(  );
						continue;
					}

					$items[$productId][$stockId] = array( 'qty' => $item->getQty(  ), 'item' => null );
					continue;
				}
			}

			$inventoryHelper->getStockSingleton(  )->revertProductsSale( $items );
			return $this;
		}

		/**
     * Adds stock item qty to $items
     *
     * @param Mage_Sales_Model_Quote_Item $quoteItem
     * 
     * @param array &$items
     */
		function _addItemToQtyArray(&$quoteItem, $items) {
			$productId = $quoteItem->getProductId(  );

			if (!$productId) {
				return null;
			}

			$stockItem = null;

			if ($quoteItem->getProduct(  )) {
				$stockItem = $quoteItem->getStockItem(  );
			}

			$stockId = ($stockItem ? $stockItem->getStockId(  ) : 0);

			if (( isset( $items[$productId] ) && isset( $items[$productId][$stockId] ) )) {
				$items[$productId][$stockId] += 'qty' = $quoteItem->getTotalQty(  );
			} 
else {
				$items[$productId][$stockId] = array( 'item' => $stockItem, 'qty' => $quoteItem->getTotalQty(  ) );
			}

			return $this;
		}

		/**
     * Update Only product status observer
     *
     * @deprecated
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function productStatusUpdate($observer) {
			$inventoryHelper = $this->getCatalogInventoryHelper(  );
			$productId = $observer->getEvent(  )->getProductId(  );
			foreach ($inventoryHelper->getStockIds(  ) as $stockId) {
				$inventoryHelper->getStockStatusSingleton( $stockId )->updateStatus( $productId );
			}

			return $this;
		}

		/**
     * Catalog Product website update
     *
     * @deprecated
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function catalogProductWebsiteUpdate($observer) {
			$inventoryHelper = $this->getCatalogInventoryHelper(  );
			$websiteIds = $observer->getEvent(  )->getWebsiteIds(  );
			$productIds = $observer->getEvent(  )->getProductIds(  );
			foreach ($websiteIds as $websiteId) {
				foreach ($productIds as $productId) {
					foreach ($inventoryHelper->getStockIds(  ) as $stockId) {
						$inventoryHelper->getStockStatusSingleton( $stockId )->updateStatus( $productId, null, $websiteId );
					}
				}
			}

			return $this;
		}

		/**
     * Add stock status to prepare index select
     * 
     * @deprecated
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Observer
     */
		function addStockStatusToPrepareIndexSelect($observer) {
			$inventoryHelper = $this->getCatalogInventoryHelper(  );
			$website = $observer->getEvent(  )->getWebsite(  );
			$select = $observer->getEvent(  )->getSelect(  );
			$inventoryHelper->getStockStatusSingleton( $this->getStockId(  ) )->addStockStatusToSelect( $select, $website );
			return $this;
		}
	}

?>