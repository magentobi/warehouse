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

	class Innoexts_WarehousePlus_Helper_Catalog_Product_Price extends Innoexts_Warehouse_Helper_Catalog_Product_Price {
		/**
     * Get attributes codes
     * 
     * @return array
     */
		function getAttributesCodes() {
			return array( 'price', 'special_price', 'special_from_date', 'special_to_date', 'tier_price' );
		}

		/**
     * Check if store scope is active
     * 
     * @return bool
     */
		function isStoreScope() {
			return ($this->getScope(  ) == SCOPE_STORE ? true : false);
		}

		/**
     * Get attribute scope
     * 
     * @param int $scope
     * 
     * @return int
     */
		function getAttributeScope($scope) {
			$attributeScope = null;
			switch ($scope) {
				case SCOPE_GLOBAL: {
					$attributeScope = SCOPE_GLOBAL;
					break;
				}

				case SCOPE_WEBSITE: {
					$attributeScope = SCOPE_WEBSITE;
					break;
				}

				case SCOPE_STORE: {
					$attributeScope = SCOPE_STORE;
					break;
				}
			}

			$attributeScope = SCOPE_GLOBAL;
			break;
			return $attributeScope;
		}

		/**
     * Set attribute scope
     * 
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param int $scope
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function setAttributeScope($attribute, $scope = null) {
			if (is_null( $scope )) {
				$scope = $this->getScope(  );
			}

			$attribute->setIsGlobal( $this->getAttributeScope( $scope ) );
			return $this;
		}

		/**
     * Change scope
     * 
     * @param int $scope
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function changeScope($scope) {
			$attributeScope = $this->getAttributeScope( $scope );
			$productHelper = $this->getProductHelper(  );
			$attributesCodes = $this->getAttributesCodes(  );
			foreach ($attributesCodes as $attributeCode) {
				$attribute = $productHelper->getAttribute( $attributeCode );
				$attribute->setIsGlobal( $attributeScope );
				$attribute->save(  );
			}

			return $this;
		}

		/**
     * Check if data is ancestor
     * 
     * @param array $data
     * @param mixed $storeId
     * 
     * @return bool
     */
		function isAncestorData($data, $storeId) {
			$websiteId = $this->getWarehouseHelper(  )->getWebsiteIdByStoreId( $storeId );

			if (( !$this->isGlobalScope(  ) && $websiteId != 0 )) {
				if (( ( $this->isWebsiteScope(  ) && (int)$data['website_id'] == 0 ) || ( $this->isStoreScope(  ) && ( (int)$data['website_id'] == 0 || (int)$data['store_id'] == 0 ) ) )) {
					return true;
				}
			}

			return false;
		}

		/**
     * Check if data is inactive
     * 
     * @param array $data
     * @param mixed $storeId
     * 
     * @return bool
     */
		function isInactiveData($data, $storeId) {
			if (( ( $this->isGlobalScope(  ) && ( 0 < $data['website_id'] || 0 < $data['store_id'] ) ) || ( $this->isWebsiteScope(  ) && 0 < $data['store_id'] ) )) {
				return true;
			}

			return false;
		}

		/**
     * Set tier price
     * 
     * @param type $product
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function setTierPrice($product) {
			$attribute = $this->getTierPriceAttribute(  );

			if ($attribute) {
				$backend = $attribute->getBackend(  );

				if ($backend) {
					$helper = $this->getWarehouseHelper(  );
					$productHelper = $this->getProductHelper(  );
					$product->getData( '_edit_mode' );
					$websiteId = null;
					$storeId = null;
					$store = $helper->getCurrentStore(  );

					if ($this->isGlobalScope(  )) {
						$websiteId = null;
						$storeId = null;
					} 
else {
						if (( $this->isWebsiteScope(  ) && $store->getId(  ) )) {
							$websiteId = $helper->getWebsiteIdByStoreId( $store->getId(  ) );
							$storeId = null;
						} 
else {
							if (( $this->isStoreScope(  ) && $store->getId(  ) )) {
								$websiteId = $helper->getWebsiteIdByStoreId( $store->getId(  ) );
								$storeId = $store->getId(  );
							}
						}
					}


					if ($isEditMode) {
						$currency = null;
						$stockId = null;
					} 
else {
						$currency = $store->getCurrentCurrencyCode(  );
						$stockId = $productHelper->getCurrentStockId( $product );
					}

					$typeId = $isEditMode = $product->getTypeId(  );
					$tierPrices = $product->getTierPrices(  );

					if (( !empty( $$tierPrices ) && !$isEditMode )) {
						$tierPrices = $backend->preparePriceData2( $tierPrices, $typeId, $websiteId, $storeId, $currency, $stockId );
					}

					$product->setFinalPrice( null );
					$product->setData( 'tier_price', $tierPrices );
				}
			}

			return $this;
		}

		/**
     * Get store batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $attributeCode
     * @param mixed $storeId
     * 
     * @return array
     */
		function _getStoreBatchPrices($product, $attributeCode, $storeId = null) {
			$helper = $this->getWarehouseHelper(  );
			$productHelper = $this->getProductHelper(  );
			$storeBatchPrices = array(  );

			if (is_null( $storeId )) {
				$storeId = $productHelper->getStoreId( $product );
			}

			$stockIds = $helper->getStockIds(  );
			$currencies = $helper->getCurrencyHelper(  )->getCodes(  );

			if (( count( $stockIds ) && count( $currencies ) )) {
				$batchPrices = $product->getData( $attributeCode );

				if (count( $batchPrices )) {
					foreach ($currencies as $currency) {
						foreach ($stockIds as $stockId) {

							if (!$this->isGlobalScope(  )) {
								if (( ( ( $storeId && isset( $batchPrices[$storeId] ) ) && isset( $batchPrices[$storeId][$currency] ) ) && isset( $batchPrices[$storeId][$currency][$stockId] ) )) {
									$storeBatchPrices[$currency][$stockId] = $batchPrices[$storeId][$currency][$stockId];
								}
							}


							if (( ( ( ( !isset( $storeBatchPrices[$currency] ) || !isset( $storeBatchPrices[$currency][$stockId] ) ) && isset( $batchPrices[0] ) ) && isset( $batchPrices[0][$currency] ) ) && isset( $batchPrices[0][$currency][$stockId] ) )) {
								$storeBatchPrices[$currency][$stockId] = $batchPrices[0][$currency][$stockId];
								continue;
							}
						}
					}
				}
			}

			return $storeBatchPrices;
		}

		/**
     * Set store batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $attributeCode
     * @param mixed $storeId
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function _setStoreBatchPrices($product, $attributeCode, $storeId = null) {
			$storeBatchPrices = $this->_getStoreBatchPrices( $product, $attributeCode, $storeId );
			$product->setData( 'store_' . $attributeCode, $storeBatchPrices );
			return $this;
		}

		/**
     * Get max store batch price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $attributeCode
     * @param mixed $currency
     * 
     * @return mixed
     */
		function _getMaxStoreBatchPrice($product, $attributeCode, $currency) {
			$price = null;
			$batchPrices = $product->getData( 'store_' . $attributeCode );

			if (( ( !is_null( $batchPrices ) && isset( $batchPrices[$currency] ) ) && count( $batchPrices[$currency] ) )) {
				$this->getWarehouseHelper(  );
				$helper->getStockIds(  );
				$currencyBatchPrices = $helper = $batchPrices[$currency];
				$storeId = $stockIds = $product->getStoreId(  );
				foreach ($stockIds as $stockId) {

					if (isset( $currencyBatchPrices[$stockId] )) {
						$batchPrice = (double)$currencyBatchPrices[$stockId];
					} 
else {
						if ($attributeCode == 'special_price') {
							$batchPrice = $this->getDefaultSpecialPrice2( $product, $storeId, $currency, $stockId );
						} 
else {
							$batchPrice = $this->getDefaultPrice2( $product, $storeId, $currency, $stockId );
						}
					}


					if (is_null( $price )) {
						$price = $stockId;
						continue;
					}


					if ($price < $batchPrice) {
						$price = $stockId;
						continue;
					}
				}
			}

			return $price;
		}

		/**
     * Get default price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $attributeCode
     * @param mixed $storeId
     * @param mixed $currency
     * @param mixed $stockId
     * 
     * @return mixed 
     */
		function _getDefaultPrice($product, $attributeCode, $storeId, $currency, $stockId) {
			$price = $product->getPrice(  );

			if (( !$this->isGlobalScope(  ) && $storeId )) {
				$batchPrices = $product->getData( $attributeCode );

				if (( ( isset( $batchPrices[0] ) && isset( $batchPrices[0][$currency] ) ) && isset( $batchPrices[0][$currency][$stockId] ) )) {
					$price = (double)$batchPrices[0][$currency][$stockId];
				}
			}

			return $price;
		}

		/**
     * Get store batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $storeId
     * 
     * @return array
     */
		function getStoreBatchPrices($product, $storeId = null) {
			return $this->_getStoreBatchPrices( $product, 'batch_prices', $storeId );
		}

		/**
     * Set store batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $storeId
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function setStoreBatchPrices($product, $storeId = null) {
			return $this->_setStoreBatchPrices( $product, 'batch_prices', $storeId );
		}

		/**
     * Get max store batch price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $currency
     * 
     * @return mixed 
     */
		function getMaxStoreBatchPrice($product, $currency) {
			return $this->_getMaxStoreBatchPrice( $product, 'batch_prices', $currency );
		}

		/**
     * Set product price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function setPrice($product) {
			$this->setStoreBatchPrices( $product );

				$helper = if (!$product->getData( '_edit_mode' )) {;
				$productHelper = $helper->getProductHelper(  );
				$currencyHelper = $helper->getCurrencyHelper(  );
				$stockId = $productHelper->getCurrentStockId( $product );
				$baseCurrency = $currencyHelper->getCurrentStoreBase(  );
				$currencyHelper->getCurrent(  );
				$currency = $this->getWarehouseHelper(  );
				$batchPrice = null;

				if (( !$product->getInitialPrice(  ) && $product->getPrice(  ) )) {
					$product->setInitialPrice( $product->getPrice(  ) );
				}


				if ($currency) {
					$currencyCode = $currency->getCode(  );

					if ($stockId) {
						$batchPrices = $product->getStoreBatchPrices(  );

						if (( isset( $batchPrices[$currencyCode] ) && isset( $batchPrices[$currencyCode][$stockId] ) )) {
							$batchPrice = (double)$batchPrices[$currencyCode][$stockId];
						}
					} 
else {
						$batchPrice = $this->getMaxStoreBatchPrice( $product, $currencyCode );
					}


					if (!is_null( $batchPrice )) {
						$rate = $baseCurrency->getRate( $currencyCode );

						if ($rate) {
							$batchPrice = $batchPrice / $rate;
						}
					}


					if (( is_null( $batchPrice ) && $product->getInitialPrice(  ) )) {
						$batchPrice = $product->getInitialPrice(  );
					}


					if (!is_null( $batchPrice )) {
						$product->setFinalPrice( null );
						$batchPrice = round( $batchPrice, 4 );
						$product->setPrice( $batchPrice );
					}
				}
			}

			return $this;
		}

		/**
     * Get default price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $storeId
     * @param mixed $currency
     * @param mixed $stockId
     * 
     * @return mixed
     */
		function getDefaultPrice2($product, $storeId, $currency, $stockId) {
			$price = null;

			if (( !$this->isGlobalScope(  ) && $storeId )) {
				$batchPrices = $product->getBatchPrices(  );

				if (( ( isset( $batchPrices[0] ) && isset( $batchPrices[0][$currency] ) ) && isset( $batchPrices[0][$currency][$stockId] ) )) {
					$price = (double)$batchPrices[0][$currency][$stockId];
				}
			}


			if (is_null( $price )) {
				$helper = $this->getWarehouseHelper(  );
				$price = $product->getPrice(  );
				$baseCurrency = $helper->getStoreById( $storeId )->getBaseCurrency(  );
				$price = $baseCurrency->convert( $price, $currency );
			}

			return $price;
		}

		/**
     * Get store batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $storeId
     * 
     * @return array
     */
		function getStoreBatchSpecialPrices($product, $storeId = null) {
			return $this->_getStoreBatchPrices( $product, 'batch_special_prices', $storeId );
		}

		/**
     * Set store batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $storeId
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function setStoreBatchSpecialPrices($product, $storeId = null) {
			return $this->_setStoreBatchPrices( $product, 'batch_special_prices', $storeId );
		}

		/**
     * Get max store batch special price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $attributeCode
     * @param mixed $currency
     * 
     * @return mixed
     */
		function getMaxStoreBatchSpecialPrice($product, $currency) {
			return $this->_getMaxStoreBatchPrice( $product, 'batch_special_prices', $currency );
		}

		/**
     * Set special price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function setSpecialPrice($product) {
			$this->setStoreBatchSpecialPrices( $product );

			if (!$product->getData( '_edit_mode' )) {
				$this->getWarehouseHelper(  );
				$productHelper = $this->getProductHelper(  );
				$currencyHelper = $helper->getCurrencyHelper(  );
				$stockId = $productHelper->getCurrentStockId( $product );
				$baseCurrency = $currencyHelper->getCurrentStoreBase(  );
				$currency = $currencyHelper->getCurrent(  );
				$batchPrice = null;
				$initialSpecialPrice = $helper = $product->getInitialSpecialPrice(  );

				if (is_null( $initialSpecialPrice )) {
					$specialPrice = ($product->getSpecialPrice(  ) ? $product->getSpecialPrice(  ) : '');
					$product->setInitialSpecialPrice( $specialPrice );
				}


				if ($currency) {
					$currencyCode = $currency->getCode(  );

					if ($stockId) {
						$batchPrices = $product->getStoreBatchSpecialPrices(  );

						if (( isset( $batchPrices[$currencyCode] ) && isset( $batchPrices[$currencyCode][$stockId] ) )) {
							$batchPrice = (double)$batchPrices[$currencyCode][$stockId];
						}
					}


					if (!is_null( $batchPrice )) {
						$rate = $baseCurrency->getRate( $currencyCode );

						if ($rate) {
							$batchPrice = $batchPrice / $rate;
						}
					}


					if (is_null( $batchPrice )) {
						$batchPrice = $product->getInitialSpecialPrice(  );
					}


					if (!is_null( $batchPrice )) {
						$product->setFinalPrice( null );
						$batchPrice = round( $batchPrice, 4 );
						$product->setSpecialPrice( $batchPrice );
					} 
else {
						$product->setFinalPrice( null );
						$product->setSpecialPrice( null );
					}
				}
			}

			return $this;
		}

		/**
     * Get default price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $storeId
     * @param mixed $currency
     * @param mixed $stockId
     * 
     * @return mixed
     */
		function getDefaultSpecialPrice2($product, $storeId, $currency, $stockId) {
			$price = null;

			if (( !$this->isGlobalScope(  ) && $storeId )) {
				$batchPrices = $product->getBatchSpecialPrices(  );

				if (( ( isset( $batchPrices[0] ) && isset( $batchPrices[0][$currency] ) ) && isset( $batchPrices[0][$currency][$stockId] ) )) {
					$price = (double)$batchPrices[0][$currency][$stockId];
				}
			}


			if (is_null( $price )) {
				$helper = $this->getWarehouseHelper(  );
				$price = $product->getSpecialPrice(  );

				if ($price) {
					$baseCurrency = $helper->getStoreById( $storeId )->getBaseCurrency(  );
					$price = $baseCurrency->convert( $price, $currency );
				}
			}

			return $price;
		}

		/**
     * Save batch price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $tableName
     * @param string $attributeCode
     * 
     * @return 	Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function _saveBatchPrices($product, $tableName, $attributeCode) {
			if (( !$product || !( $product instanceof Mage_Catalog_Model_Product ) )) {
				return $this;
			}

			$productHelper = $this->getProductHelper(  );
			$productId = $product->getId(  );
			$resource = $product->getResource(  );
			$storeId = $productHelper->getStoreId( $product );
			$table = $resource->getTable( $tableName );
			$adapter = $resource->getWriteConnection(  );
			$_data = $product->getData( $attributeCode );

			if (count( $_data )) {
				$data = array(  );
				$oldData = array(  );
				foreach ($_data as $item) {

					if (( ( isset( $item['currency'] ) && isset( $item['stock_id'] ) ) && isset( $item['price'] ) )) {
						$currency = $item['currency'];
						$stockId = (int)$item['stock_id'];
						$price = (( $item['price'] && 0 < $item['price'] ) ? round( (double)$item['price'], 2 ) : 0);
						$data[$currency][$stockId] = array( 'product_id' => $productId, 'store_id' => $storeId, 'currency' => $currency, 'stock_id' => $stockId, 'price' => $price );
						continue;
					}
				}


				if (!count( $data )) {
					return $this;
				}

				$select = $adapter->select(  )->from( $table )->where( implode( ' AND ', array( ( '(product_id = ' . $adapter->quote( $productId ) . ')' ), ( '(store_id = ' . $adapter->quote( $storeId ) . ')' ) ) ) );
				$query = $adapter->query( $select );

				if ($item = $query->fetch(  )) {
					$currency = $item['currency'];
					$stockId = $item['stock_id'];
					$oldData[$currency][$stockId] = $item;
				}

				foreach ($oldData as $oldCurrencyData) {
					foreach ($oldCurrencyData as $item) {
						$currency = $item['currency'];
						$stockId = (int)$item['stock_id'];

						if (( !isset( $data[$currency] ) || !isset( $data[$currency][$stockId] ) )) {
							$adapter->delete( $table, array( $adapter->quoteInto( 'product_id = ?', $productId ), $adapter->quoteInto( 'store_id = ?', $storeId ), $adapter->quoteInto( 'currency = ?', $currency ), $adapter->quoteInto( 'stock_id = ?', $stockId ) ) );
							continue;
						}
					}
				}

				foreach ($data as $currencyData) {
					foreach ($currencyData as $item) {
						$currency = $item['currency'];
						$stockId = (int)$item['stock_id'];

						if (( !isset( $oldData[$currency] ) || !isset( $oldData[$currency][$stockId] ) )) {
							$adapter->insert( $table, $data[$currency][$stockId] );
							continue;
						}

						$adapter->update( $table, $data[$currency][$stockId], array( $adapter->quoteInto( 'product_id = ?', $productId ), $adapter->quoteInto( 'store_id = ?', $storeId ), $adapter->quoteInto( 'currency = ?', $currency ), $adapter->quoteInto( 'stock_id = ?', $stockId ) ) );
					}
				}
			}

			return $this;
		}

		/**
     * Load batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $tableName
     * @param string $attributeCode
     * @param string $priceSetter
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function _loadBatchPrices($product, $tableName, $attributeCode, $priceSetter) {
			if (( ( !$product || !( $product instanceof Mage_Catalog_Model_Product ) ) || $product->hasData( $attributeCode ) )) {
				return $this;
			}

			$productId = $product->getId(  );
			$product->getResource(  );
			$resource->getTable( $tableName );
			$adapter = $resource->getWriteConnection(  );
			$select = $resource = $adapter->select(  )->from( $table )->where( 'product_id = ?', $productId );
			$query = $table = $adapter->query( $select );
			$data = array(  );

			if ($item = $query->fetch(  )) {
				$storeId = (int)$item['store_id'];
				$currency = $item['currency'];
				$stockId = (int)$item['stock_id'];
				$price = $item['price'];
				$data[$storeId][$currency][$stockId] = $price;
			}

			$product->setData( $attributeCode, $data );
			$this->$priceSetter( $product );
			return $this;
		}

		/**
     * Load collection batch prices
     * 
     * @param Varien_Data_Collection_Db $collection
     * @param string $tableName
     * @param string $attributeCode
     * @param string $priceSetter
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function _loadCollectionBatchPrices($collection, $tableName, $attributeCode, $priceSetter) {
			if (!$collection) {
				return $this;
			}

			$productIds = array(  );
			foreach ($collection as $product) {

				if (!$product->hasData( $attributeCode )) {
					array_push( $productIds, $product->getId(  ) );
					continue;
				}
			}


			if (count( $productIds )) {
				$table = $collection->getTable( $tableName );
				$adapter = $collection->getConnection(  );
				$select = $adapter->select(  )->from( $table )->where( $adapter->quoteInto( 'product_id IN (?)', $productIds ) );
				$query = $adapter->query( $select );
				$productData = array(  );

				if ($item = $query->fetch(  )) {
					$productId = $item['product_id'];
					$storeId = (int)$item['store_id'];
					$currency = $item['currency'];
					$stockId = (int)$item['stock_id'];
					$price = (double)$item['price'];
					$productData[$productId][$storeId][$currency][$stockId] = $price;
				}

				foreach ($collection as $product) {
					$productId = $product->getId(  );
					$data = (isset( $productData[$productId] ) ? $productData[$productId] : array(  ));
					$product->setData( $attributeCode, $data );
					$this->$priceSetter( $product );
				}
			}

			return $this;
		}

		/**
     * Remove batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeCode
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function _removeBatchPrices($product, $attributeCode) {
			if (( !$product || !( $product instanceof Mage_Catalog_Model_Product ) )) {
				return $this;
			}

			$product->unsetData( $attributeCode );
			$product->unsetData( 'store_' . $attributeCode );
			return $this;
		}
	}

?>