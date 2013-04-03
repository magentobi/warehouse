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

	class Innoexts_Warehouse_Model_Sales_Quote extends Mage_Sales_Model_Quote {
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
     * Clone
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function __clone() {
			$this->cloneAddresses(  );
			$this->cloneItems(  );
			return $this;
		}

		/**
     * Clone addresses
     */
		function cloneAddresses() {
			$addresses = $this->getAllAddresses(  );
			$this->_addresses = clone $this->_addresses;
			$keys = array(  );
			foreach ($this->getAddressesCollection(  ) as $address) {
				array_push( $keys, $key );
			}

			foreach ($keys as $key) {
				$this->getAddressesCollection(  )->removeItemByKey( $key );
			}

			foreach ($addresses as $address) {
				parent::addAddress( clone $address );
			}

		}

		/**
     * Clone items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function cloneItems() {
			$items = $this->getAllVisibleItems(  );
			$this->_items = clone $this->_items;
			$keys = array(  );
			foreach ($this->getItemsCollection(  ) as $key => $item) {
				array_push( $keys, $key );
			}

			foreach ($keys as $key) {
				$this->getItemsCollection(  )->removeItemByKey( $key );
			}

			foreach ($items as $item) {
				$this->cloneItem( $item );
			}

		}

		/**
     * Get stock identifiers
     * 
     * @return array
     */
		function getStockIds() {
			$stockIds = array(  );
			foreach ($this->getAllItems(  ) as $item) {
				$stockId = $item->getStockId(  );

				if ($stockId) {
					array_push( $stockIds, $stockId );
					continue;
				}
			}

			return $stockIds;
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function getStockId() {
			$stockId = null;
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isMultipleMode(  )) {
				$_stockId = $this->getData( 'stock_id' );

				if ($_stockId) {
					$stockId = $config;
				}
			}

			return $stockId;
		}

		/**
     * Set stock identifier
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function setStockId($stockId) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isMultipleMode(  )) {
				$this->setData( 'stock_id', $stockId );
			}

			return $this;
		}

		/**
     * Get warehouses
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouses() {
			$helper = $this->getWarehouseHelper(  );
			$warehouses = array(  );
			foreach ($this->getAllItems(  ) as $item) {
				$stockId = $item->getStockId(  );

				if ($stockId) {
					$warehouse = $helper->getWarehouseByStockId( $stockId );

					if ($warehouse) {
						$warehouses[$warehouse->getId(  )] = $warehouse;
						continue;
					}

					continue;
				}
			}

			return $helper->sortWarehouses( $warehouses );
		}

		/**
     * Check if address is shipping type
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote_Address $address
     * @return bool
     */
		function _isShippingAddressType($address) {
			return ($address->isShippingAddressType(  ) ? true : false);
		}

		/**
     * Remove shipping addresses
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function removeShippingAddresses() {
			foreach ($this->getAddressesCollection(  ) as $address) {

				if ($this->_isShippingAddressType( $address )) {
					$address->isDeleted( true );
					continue;
				}
			}

			return $this;
		}

		/**
     * Get shipping address
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function getShippingAddressByStockId($stockId) {
			$address = null;
			foreach ($this->getAllShippingAddresses(  ) as $shippingAddress) {

				if ($shippingAddress->getStockId(  ) == $stockId) {
					$address = $stockId;
					break;
				}
			}

			return $address;
		}

		/**
     * Get shipping address
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function getShippingAddress2($stockId = null) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( ( $stockId && $config->isMultipleMode(  ) ) && $config->isSplitOrderEnabled(  ) )) {
				return $this->getShippingAddressByStockId( $stockId );
			}

			return $this->getShippingAddress(  );
		}

		/**
     * Add address
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote_Address $address
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function addAddress($address) {
			parent::addAddress( $address );

			if (( $this->_isShippingAddressType( $address ) && !$address->getStockId(  ) )) {
				$this->applyStockAddresses(  );
			}

			return $this;
		}

		/**
     * Collect totals
     *
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function collectTotals() {
			if ($this->getTotalsCollectedFlag(  )) {
				return $this;
			}

			$this->applyStockAddresses(  );
			$this->resetItemsQtys(  );
			parent::collectTotals(  );
			return $this;
		}

		/**
     * Copy address
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote_Address $address1
     * @param Innoexts_Warehouse_Model_Sales_Quote_Address $address2
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function copyAddress($address1, $address2) {
			$addressAttributes = array( 'customer_id', 'save_in_address_book', 'customer_address_id', 'email', 'prefix', 'firstname', 'middlename', 'lastname', 'suffix', 'company', 'street', 'city', 'region', 'region_id', 'postcode', 'country_id', 'telephone', 'fax', 'same_as_billing' );
			foreach ($address1->getData(  ) as $key => $value) {

				if (in_array( $key, $addressAttributes )) {
					$address2->setData( $key, $value );
					continue;
				}
			}

			return $this;
		}

		/**
     * Apply stock addresses
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function applyStockAddresses() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$stockIds = $this->getStockIds(  );

				if (count( $stockIds )) {
					$shippingAddress = clone $this->getShippingAddress(  );
					foreach ($this->getAllShippingAddresses(  ) as $address) {

						if (( !$address->getStockId(  ) || !in_array( $address->getStockId(  ), $stockIds ) )) {
							$address->isDeleted( true );
							continue;
						}
					}

					foreach ($stockIds as $stockId) {
						$address = $this->getShippingAddressByStockId( $stockId );

						if (!$address) {
							$address = Mage::getModel( 'sales/quote_address' );
							$this->addAddress( $address );

							if ($shippingAddress) {
								$this->copyAddress( $shippingAddress, $address );
							}
						}

						$address->setAddressType( TYPE_SHIPPING )->setStockId( $stockId );
					}


					if ($shippingAddress) {
						unset( $$shippingAddress );
					}
				}
			} 
else {
				if (1 < count( $this->getAllShippingAddresses(  ) )) {
					$first = true;
					foreach ($this->getAllShippingAddresses(  ) as $address) {

						if (!$first) {
							$address->isDeleted( true );
						}

						$first = false;
					}
				}
			}

			return $this;
		}

		/**
     * Compare items for similarity
     * 
     * @param $item1 Innoexts_Warehouse_Model_Sales_Quote_Item
     * @param $item2 Innoexts_Warehouse_Model_Sales_Quote_Item
     * 
     * @return bool
     */
		function compareItems($item1, $item2) {
			return $item1->compare( $item2 );
		}

		/**
     * Merge items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function mergeItems() {
			foreach ($this->getAllVisibleItems(  ) as $item) {
				$item->setOrigionalQty( $this->getQty(  ) );
			}

			foreach ($this->getAllVisibleItems(  ) as $index => $item) {
				foreach ($this->getAllVisibleItems(  ) as $_index => $_item) {

					if (( $index < $_index && $this->compareItems( $item, $_item ) )) {
						$qty = (double)$item->getQty(  ) + (double)$_item->getQty(  );
						$item->setData( 'qty', $qty );
						$_item->setIsClone( true );
						$_item->isDeleted( true );

						if (count( $_item->getChildren(  ) )) {
							foreach ($_item->getChildren(  ) as $_childItem) {
								$_childItem->setIsClone( true );
								$_childItem->isDeleted( true );
							}

							continue;
						}

						continue;
					}
				}
			}

			return $this;
		}

		/**
     * Get origional item
     * 
     * @param $item Innoexts_Warehouse_Model_Sales_Quote_Item
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function getOrigionalItem($item) {
			$origionalItem = null;
			foreach ($this->getAllVisibleItems(  ) as $_item) {

				if ($item->compare( $_item )) {
					$origionalItem = $item;
					break;
				}
			}


			if (is_null( $origionalItem )) {
				$origionalItem = $origionalItem;
			}

			return $origionalItem;
		}

		/**
     * Apply stock items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function applyStockItems() {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$assignmentMethodHelper = $helper->getAssignmentMethodHelper(  );

			if ($config->isSplitQtyEnabled(  )) {
				$this->mergeItems(  );
			}

			$assignmentMethodHelper->applyQuoteStockItems( $this );
			return $this;
		}

		/**
     * Apply stocks
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function applyStocks() {
			if (( $this->isItemsQtysChanged(  ) && !$this->isStockIdStatic(  ) )) {
				$this->applyStockItems(  );
				$this->applyStockAddresses(  );
				$this->resetItemsQtys(  );
			}

			return $this;
		}

		/**
     * Reapply stocks
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function reapplyStocks() {
			$this->setItemsQtys( array(  ) );
			$this->applyStocks(  );
			return $this;
		}

		/**
     * Checking availability of items with decimal qty
     * 
     * @return bool
     */
		function hasItemsWithDecimalQty() {
			foreach ($this->getAllItems(  ) as $item) {
				$stockItem = $item->getStockItem(  );

				if (( $stockItem && $stockItem->getIsQtyDecimal(  ) )) {
					return true;
				}
			}

			return false;
		}

		/**
     * Get items quantities indexed by product identifiers
     * 
     * @return array
     */
		function _getItemsQtys() {
			$qtys = array(  );
			foreach ($this->getAllVisibleItems(  ) as $item) {

				if ($item->getParentItem(  )) {
					continue;
				}

				$productId = $item->getProductId(  );

				if (!isset( $qtys[$productId] )) {
					$qtys[$productId] = 0;
				}

				$qtys += $item = (double)$item->getQty(  );
			}

			return $qtys;
		}

		/**
     * Set items quantities
     * 
     * @param array $qtys
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function setItemsQtys($qtys) {
			if (!empty( $$qtys )) {
				$qtys = serialize( $qtys );
			} 
else {
				$qtys = null;
			}

			$this->setData( 'items_qtys', $qtys );
			return $this;
		}

		/**
     * Get items quantities
     * 
     * @return array
     */
		function getItemsQtys() {
			$qtys = $this->getData( 'items_qtys' );

			if (!empty( $$qtys )) {
				$qtys = @unserialize( $qtys );
			}


			if (!empty( $$qtys )) {
				return $qtys;
			}

			return array(  );
		}

		/**
     * Reset quantities
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function resetItemsQtys() {
			$this->setItemsQtys( $this->_getItemsQtys(  ) );
			return $this;
		}

		/**
     * Check if items quantities changed
     * 
     * @return bool
     */
		function isItemsQtysChanged() {
			$qtys = $this->getItemsQtys(  );
			$_qtys = $this->_getItemsQtys(  );

			if (count( $qtys ) == count( $_qtys )) {
				foreach ($qtys as $productId => $qty) {

					if (( !isset( $_qtys[$productId] ) || $_qtys[$productId] != $qty )) {
						return true;
					}
				}

				return null;
			}

			return true;
		}

		/**
     * Clone item
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote_Item $item
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function cloneItem($item) {
			clone $item;
			$_item = ;
			$this->addItem( $_item );

			if (count( $item->getChildren(  ) )) {
				foreach ($item->getChildren(  ) as $childItem) {
					clone $childItem;
					$_childItem = ;
					$this->addItem( $_childItem );
					$_childItem->setParentItem( $_item );
				}
			}

			return $_item;
		}

		/**
     * Get all totals
     * 
     * @return array
     */
		function getTotals() {
			if ($this->isVirtual(  )) {
				return $this->getBillingAddress(  )->getTotals(  );
			}

			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$totals = array(  );
				foreach ($this->getAllShippingAddresses(  ) as $address) {
					foreach ($address->getTotals(  ) as $code => $total) {

						if (isset( $totals[$code] )) {
							$totals[$code]->merge( $total );
							continue;
						}

						$totals[$code] = $total;
					}
				}
			} 
else {
				$totals = $this->getShippingAddress(  )->getTotals(  );
			}

			foreach ($this->getAddressesCollection(  ) as $address) {

				if (( $address->isDeleted(  ) || $address->isShippingAddressType(  ) )) {
					continue;
				}

				foreach ($address->getTotals(  ) as $code => $total) {

					if (isset( $totals[$code] )) {
						$totals[$code]->merge( $total );
						continue;
					}

					$totals[$code] = $total;
				}
			}

			$sortedTotals = array(  );
			foreach ($this->getBillingAddress(  )->getTotalModels(  ) as $total) {

				if (isset( $totals[$total->getCode(  )] )) {
					$sortedTotals[$total->getCode(  )] = $totals[$total->getCode(  )];
					continue;
				}
			}

			return $sortedTotals;
		}

		/**
     * Check if stock identifier is static
     * 
     * @return bool
     */
		function isStockIdStatic() {
			$isStatic = false;

			if ($this->getIsStockIdStatic(  )) {
				return true;
			}

			foreach ($this->getAllItems(  ) as $item) {

				if ($item->isStockIdStatic(  )) {
					$isStatic = true;
					break;
				}
			}

			return $isStatic;
		}

		/**
     * Get product identifiers counter
     * 
     * @return array
     */
		function getProductIdsCounter() {
			$productIdsCounter = array(  );
			foreach ($this->getAllVisibleItems(  ) as $item) {
				$productId = $item->getProductId(  );

				if (!isset( $productIdsCounter[$productId] )) {
					$productIdsCounter[$productId] = 1;
					continue;
				}

				++$productIdsCounter[$productId];
			}

			return $productIdsCounter;
		}

		/**
     * Get duplicated product identifiers
     * 
     * @return array
     */
		function getDuplicatedProductIds() {
			$productIdsCounter = array(  );
			$duplicatedProductIds = array(  );
			foreach ($this->getAllItems(  ) as $item) {
				$productId = $item->getProductId(  );

				if (!isset( $productIdsCounter[$productId] )) {
					$productIdsCounter[$productId] = 1;
					continue;
				}

				++$productIdsCounter[$productId];
			}

			foreach ($productIdsCounter as $productId => $qty) {

				if (1 < $qty) {
					array_push( $duplicatedProductIds, $productId );
					continue;
				}
			}

			return $duplicatedProductIds;
		}

		/**
     * Check if product is duplicated in cart
     * 
     * @param mixed $productId 
     * 
     * @return bool
     */
		function isDuplicatedProductId($productId) {
			$duplicatedProductIds = $this->getDuplicatedProductIds(  );
			return (in_array( $productId, $duplicatedProductIds ) ? true : false);
		}

		/**
     * Get item key
     * 
     * @param int $productId
     * @param int $number
     * @param int $stockId
     * 
     * @return string
     */
		function getItemKey($productId, $number = null, $stockId = null) {
			$itemKeyParts = array( $productId );

			if (is_null( $number )) {
				$number = 163;
			}

			array_push( $itemKeyParts, $number );

			if (!is_null( $stockId )) {
				array_push( $itemKeyParts, $stockId );
			}

			return implode( '-', $itemKeyParts );
		}

		/**
     * Parse item key
     * 
     * @param string $itemKey
     * 
     * @return array
     */
		function parseItemKey($itemKey) {
			$itemKeyParts = explode( '-', $itemKey );

			if (!isset( $itemKeyParts[1] )) {
				$itemKeyParts[1] = 1;
			}


			if (!isset( $itemKeyParts[2] )) {
				$itemKeyParts[2] = null;
			}

			return $itemKeyParts;
		}

		/**
     * Trim item key stock id
     * 
     * @param string $itemKey
     * 
     * @return string
     */
		function trimItemKeyStockId($itemKey) {
			$itemKeyParts = $this->parseItemKey( $itemKey );
			return $this->getItemKey( $itemKeyParts[0], $itemKeyParts[1] );
		}

		/**
     * Get all visible origional items
     * 
     * @return array of Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function getAllVisibleOrigionalItems() {
			$items = array(  );
			foreach ($this->getAllVisibleItems(  ) as $item) {

				if (!$item->getIsClone(  )) {
					array_push( $items, $item );
					continue;
				}
			}

			return $items;
		}

		/**
     * Get items keys
     * 
     * @return array
     */
		function getItemsKeys() {
			$itemsKeys = array(  );
			$items = $this->getAllVisibleOrigionalItems(  );

			if (count( $items )) {
				$_productIdsCounter = array(  );
				$productIdsCounter = $this->getProductIdsCounter(  );
				foreach ($items as $number) {
					$productId = $item->getProductId(  );

					if (!isset( $_productIdsCounter[$productId] )) {
						$_productIdsCounter[$productId] = 1;
						continue;
					} 
else {
						++$_productIdsCounter[$productId];
					}

					$_productIdsCounter[$productId];
					$itemKey = $index = $this->getItemKey( $productId, $number );
					$itemsKeys[$index] = $itemKey;
				}
			}

			return $itemsKeys;
		}

		/**
     * Get item by item key
     * 
     * @param string $itemKey
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Item
     */
		function getItemByItemKey($itemKey) {
			$item = null;
			$itemsKeys = $this->getItemsKeys(  );

			if (count( $itemsKeys )) {
				$index = null;
				foreach ($itemsKeys as $_index => $_itemKey) {

					if ($_itemKey == $itemKey) {
						$index = $_index;
						break;
					}
				}


				if (!is_null( $index )) {
					$items = $this->getAllVisibleOrigionalItems(  );

					if (isset( $items[$index] )) {
						$item = $items[$index];
					}
				}
			}

			return $item;
		}

		/**
     * Get stock data
     * 
     * @param bool $splitQty
     * @param bool $forceCartNoBackorders
     * @param bool $forceCartItemNoBackorders
     * 
     * @return array of Varien_Object
     */
		function getStockData($splitQty = false, $forceCartNoBackorders = false, $forceCartItemNoBackorders = false) {
			$stockData = array(  );
			$itemsKeys = $this->getItemsKeys(  );

			if (count( $itemsKeys )) {
				foreach ($this->getAllVisibleOrigionalItems(  ) as $index => $item) {

					if (isset( $itemsKeys[$index] )) {
						$itemKey = $itemsKeys[$index];
						$itemStockData = $item->getStockData(  );

						if (!$itemStockData->getIsInStock(  )) {
							if ($splitQty) {
								$_stockData = $item->getSplittedStockData(  );
								$itemKeyParts = $this->parseItemKey( $itemKey );

								if (count( $_stockData )) {
									foreach ($_stockData as $itemStockData) {
										$stockId = $itemStockData->getStockId(  );
										$_itemKey = $this->getItemKey( $itemKeyParts[0], $itemKeyParts[1], $stockId );
										$stockData[$_itemKey] = $itemStockData;
									}

									continue;
								}

								$stockData[$itemKey] = $itemStockData;
								continue;
							}

							$stockData[$itemKey] = $itemStockData;
							continue;
						}


						if (( $forceCartNoBackorders || $forceCartItemNoBackorders )) {
							$_itemStockData = $item->getStockData( null, true );

							if (( $forceCartItemNoBackorders && !$_itemStockData->getIsInStock(  ) )) {
								$_itemStockData = $forceCartItemNoBackorders;
							}

							$itemStockData = $itemKey;
						}

						$stockData[$itemKey] = $itemStockData;
						continue;
					}
				}
			}

			return $stockData;
		}

		/**
     * Check stock data
     * 
     * @param array of Varien_Object $stockData
     * 
     * @return bool
     */
		function checkStockData($stockData = null) {
			$isValid = true;
			$itemsKeys = $this->getItemsKeys(  );

			if (count( $itemsKeys )) {
				if (is_null( $stockData )) {
					$stockData = $this->getStockData(  );
				}

				foreach ($this->getAllVisibleOrigionalItems(  ) as $index => $item) {

					if (isset( $itemsKeys[$index] )) {
						$itemKey = $itemsKeys[$index];
						$isItemValid = false;
						foreach ($stockData as $_itemKey => $itemStockData) {
							$__itemKey = $this->trimItemKeyStockId( $itemKey );

							if ($itemKey == $__itemKey) {
								if ($itemStockData->getIsInStock(  )) {
									$isItemValid = true;
									continue;
								}

								$isItemValid = false;
								break;
							}
						}


						if (!$isItemValid) {
							$isValid = false;
							break;
						}

						continue;
					}
				}
			} 
else {
				$isValid = false;
			}

			return $isValid;
		}

		/**
     * Apply stock items combinations
     * 
     * @param array of Varien_Object $stockData
     * @param array $combination
     * @param bool $collectTotals
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function applyStockItemsCombination($stockData = null, $combination, $collectTotals = false) {
			$hasItemCloned = false;

			if (is_null( $stockData )) {
				$stockData = $this->getStockData(  );
			}

			foreach ($this->getAllVisibleItems(  ) as $item) {
				$item->setIsStockIdApplied( false );
			}

			foreach ($combination as $itemKey => $stockId) {

				if (isset( $stockData[$itemKey] )) {
					$itemStockData = $stockData[$itemKey];
					$item = $this->getItemByItemKey( $itemKey );

					if (is_null( $item )) {
						$_itemKey = $this->trimItemKeyStockId( $itemKey );
						$_item = $this->getItemByItemKey( $_itemKey );

						if (!is_null( $_item )) {
							if ($_item->getIsStockIdApplied(  )) {
								$item = $this->cloneItem( $_item );
								$item->setIsClone( true );
								$_item->setIsCloned( true );
								$hasItemCloned = true;
							} 
else {
								$item = $hasItemCloned;
							}
						}
					}


					if (!is_null( $item )) {
						$item->setStockId( $stockId );
						$item->setData( 'qty', $itemStockData->getQty(  ) );
						$item->setIsStockIdApplied( true );

						if ($item->isParentItem(  )) {
							foreach ($item->getChildren(  ) as $childItem) {
								$childItem->setStockId( $stockId );
								$childItem->setIsStockIdApplied( true );
							}

							continue;
						}

						continue;
					}

					continue;
				}
			}

			foreach ($this->getAllVisibleItems(  ) as $item) {

				if (!$item->getIsStockIdApplied(  )) {
					$item->isDeleted( true );

					if (count( $item->getChildren(  ) )) {
						foreach ($item->getChildren(  ) as $childItem) {
							$childItem->isDeleted( true );
						}

						continue;
					}

					continue;
				}
			}

			$this->setStockItemsCombination( $combination );

			if ($collectTotals) {
				$this->applyStockAddresses(  );
				foreach ($this->getAllShippingAddresses(  ) as $shippingAddress) {
					$shippingAddress->collectTotals(  );
					$shippingAddress->setCollectShippingRates( true );
					$shippingAddress->collectShippingRates(  );
				}

				$this->setTotalsCollectedFlag( false );
				$this->collectTotals(  );
			}

			return $this;
		}

		/**
     * Remove errors
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function removeErrors() {
			foreach ($this->getAllItems(  ) as $item) {

				if (count( $item->getQtyOptions(  ) )) {
					foreach ($item->getQtyOptions(  ) as $option) {
						$option->setHasError( false );
					}
				}

				$item->setHasError( false );
			}

			$this->setHasError( false );
			return $this;
		}

		/**
     * Updates quote item with new configuration
     *
     * @param int $itemId
     * @param Varien_Object $buyRequest
     * @param null|array|Varien_Object $params
     * @return Mage_Sales_Model_Quote_Item
     * 
     * @see Mage_Catalog_Helper_Product::addParamsToBuyRequest()
     */
		function updateItem($itemId, $buyRequest, $params = null) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

				$item = if ($config->isSplitQtyEnabled(  )) {;

				if (!$item) {
					Mage::throwException( Mage::helper( 'sales' )->__( 'Wrong quote item id to update configuration.' ) );
				}

				$productId = $item->getProduct(  )->getId(  );
				Mage::getModel( 'catalog/product' )->setStoreId( $this->getStore(  )->getId(  ) )->load( $productId );
				$product = $this->getItemById( $itemId );

				if (!$params) {
					$params = new Varien_Object(  );
				} 
else {
					if (is_array( $params )) {
						$params = new Varien_Object( $params );
					}
				}

				$params->setCurrentConfig( $item->getBuyRequest(  ) );
				$buyRequest = Mage::helper( 'catalog/product' )->addParamsToBuyRequest( $buyRequest, $params );
				$buyRequest->setResetCount( true );
				$resultItem = $this->addProduct( $product, $buyRequest );

				if (is_string( $resultItem )) {
					Mage::throwException( $resultItem );
				}


				if ($resultItem->getParentItem(  )) {
					$resultItem = $resultItem->getParentItem(  );
				}
			} 
else {
				return parent::updateItem( $itemId, $buyRequest, $params );
			}

			return $resultItem;
		}

		/**
     * Debug object
     * 
     * @param Varien_Object $object
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function debugObject($object) {
			$data = array(  );
			foreach ($object->getData(  ) as $key => $value) {
			}

			echo var_export( $data, true );
		}

		/**
     * Debug
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function _debug() {
			foreach ($this->getItemsCollection(  ) as $item) {
				$this->debugObject( $item );
			}

			echo '---<br/>';
			return $this;
		}
	}

?>