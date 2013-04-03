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

	class Innoexts_Warehouse_Helper_Catalog_Product_Price extends Mage_Core_Helper_Abstract {
		private $_tierPriceAttribute = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get product helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function getProductHelper() {
			return Mage::helper( 'warehouse/catalog_product' );
		}

		/**
     * Get indexer helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function getIndexerHelper() {
			return Mage::helper( 'warehouse/catalog_product_price_indexer' );
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
     * Check if group price is fixed
     * 
     * @param string $productTypeId
     * 
     * @return bool
     */
		function isGroupPriceFixed($productTypeId) {
			$price = Mage::getSingleton( 'catalog/product_type' )->priceFactory( $productTypeId );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				return $price->isGroupPriceFixed(  );
			}

			return $price->isTierPriceFixed(  );
		}

		/**
     * Get scope
     * 
     * @return int
     */
		function getScope() {
			return Mage::helper( 'catalog' )->getPriceScope(  );
		}

		/**
     * Check if global scope is active
     * 
     * @return bool 
     */
		function isGlobalScope() {
			return ($this->getScope(  ) == SCOPE_GLOBAL ? true : false);
		}

		/**
     * Check if website scope is active
     * 
     * @return bool
     */
		function isWebsiteScope() {
			return ($this->getScope(  ) == SCOPE_WEBSITE ? true : false);
		}

		/**
     * Check if data is inactive
     * 
     * @param array $data
     * @param mixed $websiteId
     * 
     * @return bool
     */
		function isInactiveData($data, $websiteId) {
			if (( $this->isGlobalScope(  ) && 0 < $data['website_id'] )) {
				return true;
			}

			return false;
		}

		/**
     * Check if data is ancestor
     * 
     * @param array $data
     * @param mixed $websiteId
     * 
     * @return bool
     */
		function isAncestorData($data, $websiteId) {
			if (( !$this->isGlobalScope(  ) && $websiteId != 0 )) {
				if (( $this->isWebsiteScope(  ) && (int)$data['website_id'] == 0 )) {
					return true;
				}
			}

			return false;
		}

		/**
     * Get tier price attribute
     * 
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
		function getTierPriceAttribute() {
			if (is_null( $this->_tierPriceAttribute )) {
				$attribute = $this->getProductHelper(  )->getTierPriceAttribute(  );

				if ($attribute) {
					$this->_tierPriceAttribute = $attribute;
				}
			}

			return $this->_tierPriceAttribute;
		}

		/**
     * Save batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $tableName
     * @param string $attributeCode
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function _saveBatchPrices($product, $tableName, $attributeCode) {
			if (( !$product || !( $product instanceof Mage_Catalog_Model_Product ) )) {
				return $this;
			}

			$productHelper = $this->getProductHelper(  );
			$productId = $product->getId(  );
			$resource = $product->getResource(  );
			$websiteId = $productHelper->getWebsiteId( $product );
			$table = $resource->getTable( $tableName );
			$adapter = $resource->getWriteConnection(  );
			$_data = $product->getData( $attributeCode );

			if (count( $_data )) {
				$data = array(  );
				$oldData = array(  );
				foreach ($_data as $item) {

					if (( isset( $item['stock_id'] ) && isset( $item['price'] ) )) {
						$stockId = (int)$item['stock_id'];
						$price = (( $item['price'] && 0 < $item['price'] ) ? round( (double)$item['price'], 2 ) : 0);
						$data[$stockId] = array( 'product_id' => $productId, 'stock_id' => $stockId, 'website_id' => $websiteId, 'price' => $price );
						continue;
					}
				}


				if (!count( $data )) {
					return $this;
				}

				$select = $adapter->select(  )->from( $table )->where( implode( ' AND ', array( ( '(product_id = ' . $adapter->quote( $productId ) . ')' ), ( '(website_id = ' . $adapter->quote( $websiteId ) . ')' ) ) ) );
				$query = $adapter->query( $select );

				if ($item = $query->fetch(  )) {
					$stockId = (int)$item['stock_id'];
					$oldData[$stockId] = $item;
				}

				foreach ($oldData as $item) {
					$stockId = (int)$item['stock_id'];

					if (!isset( $data[$stockId] )) {
						$adapter->delete( $table, array( $adapter->quoteInto( 'product_id = ?', $productId ), $adapter->quoteInto( 'stock_id = ?', $stockId ), $adapter->quoteInto( 'website_id = ?', $websiteId ) ) );
						continue;
					}
				}

				foreach ($data as $item) {
					$stockId = (int)$item['stock_id'];

					if (!isset( $oldData[$stockId] )) {
						$adapter->insert( $table, $item );
						continue;
					}

					$adapter->update( $table, $item, array( $adapter->quoteInto( 'product_id = ?', $productId ), $adapter->quoteInto( 'stock_id = ?', $stockId ), $adapter->quoteInto( 'website_id = ?', $websiteId ) ) );
				}
			}

			return $this;
		}

		/**
     * Save batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function saveBatchPrices($product) {
			return $this->_saveBatchPrices( $product, 'catalog/product_batch_price', 'batch_prices' );
		}

		/**
     * Save batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function saveBatchSpecialPrices($product) {
			return $this->_saveBatchPrices( $product, 'catalog/product_batch_special_price', 'batch_special_prices' );
		}

		/**
     * Load batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $tableName
     * @param string $attributeCode
     * @param string $priceSetter
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function _loadBatchPrices($product, $tableName, $attributeCode, $priceSetter) {
			if (( ( !$product || !( $product instanceof Mage_Catalog_Model_Product ) ) || $product->hasData( $attributeCode ) )) {
				return $this;
			}

			$productId = $product->getId(  );
			$resource = $product->getResource(  );
			$table = $resource->getTable( $tableName );
			$adapter = $resource->getWriteConnection(  );
			$select = $adapter->select(  )->from( $table )->where( 'product_id = ?', $productId );
			$query = $adapter->query( $select );
			$data = array(  );

			if ($item = $query->fetch(  )) {
				$stockId = (int)$item['stock_id'];
				$price = $item['price'];
				$websiteId = (int)$item['website_id'];
				$data[$websiteId][$stockId] = $price;
			}

			$product->setData( $attributeCode, $data );
			$this->$priceSetter( $product );
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
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function loadBatchPrices($product) {
			return $this->_loadBatchPrices( $product, 'catalog/product_batch_price', 'batch_prices', 'setPrice' );
		}

		/**
     * Load batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $tableName
     * @param string $attributeCode
     * @param string $priceSetter
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function loadBatchSpecialPrices($product) {
			return $this->_loadBatchPrices( $product, 'catalog/product_batch_special_price', 'batch_special_prices', 'setSpecialPrice' );
		}

		/**
     * Load collection batch prices
     * 
     * @param Varien_Data_Collection_Db $collection
     * @param string $tableName
     * @param string $attributeCode
     * @param string $priceSetter
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
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
					$price = $item['price'];
					$websiteId = (int)$item['website_id'];
					$item['product_id'];
					$productId = $stockId = (int)$item['stock_id'];
					$productData[$productId][$websiteId][$stockId] = $price;
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
     * Load collection batch prices
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function loadCollectionBatchPrices($collection) {
			return $this->_loadCollectionBatchPrices( $collection, 'catalog/product_batch_price', 'batch_prices', 'setPrice' );
		}

		/**
     * Load collection batch special prices
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function loadCollectionBatchSpecialPrices($collection) {
			return $this->_loadCollectionBatchPrices( $collection, 'catalog/product_batch_special_price', 'batch_special_prices', 'setSpecialPrice' );
		}

		/**
     * Remove batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeCode
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function _removeBatchPrices($product, $attributeCode) {
			if (( !$product || !( $product instanceof Mage_Catalog_Model_Product ) )) {
				return $this;
			}

			$product->unsetData( $attributeCode );
			$product->unsetData( 'website_' . $attributeCode );
			return $this;
		}

		/**
     * Remove batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function removeBatchPrices($product) {
			return $this->_removeBatchPrices( $product, 'batch_prices' );
		}

		/**
     * Remove batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function removeBatchSpecialPrices($product) {
			return $this->_removeBatchPrices( $product, 'batch_special_prices' );
		}

		/**
     * Load tier price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function loadTierPrice($product) {
			if ($product->hasData( 'tier_price' )) {
				return $this;
			}

			$attribute = $this->getTierPriceAttribute(  );

			if (!$attribute) {
				return $this;
			}

			$backend = $attribute->getBackend(  );

			if (!$backend) {
				return $this;
			}

			$backend->afterLoad( $product );
			return $this;
		}

		/**
     * Set tier price
     * 
     * @param type $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function setTierPrice($product) {
			$attribute = $this->getTierPriceAttribute(  );

			if (!$attribute) {
				return $this;
			}

			$backend = $attribute->getBackend(  );

			if (!$backend) {
				return $this;
			}

			$isEditMode = $productHelper = $helper = $this->getWarehouseHelper(  );
			$storeId = $helper->getCurrentStore(  )->getId(  );
			$websiteId = null;

			if ($this->isGlobalScope(  )) {
				$websiteId = 151;
			} 
else {
				if ($storeId) {
					$websiteId = $helper->getWebsiteIdByStoreId( $storeId );
				}
			}


			if ($isEditMode) {
				$stockId = null;
			} 
else {
				$stockId = $productHelper->getCurrentStockId( $product );
			}

			$product->getTypeId(  );
			$typeId = $this->getProductHelper(  );
			$product->getTierPrices(  );
			$tierPrices = $product->getData( '_edit_mode' );

			if (( !empty( $$tierPrices ) && !$isEditMode )) {
				$tierPrices = $backend->preparePriceData2( $tierPrices, $typeId, $websiteId, $stockId );
			}

			$product->setFinalPrice( null );
			$product->setData( 'tier_price', $tierPrices );
			return $this;
		}

		/**
     * Get website batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $websiteId
     * 
     * @return array
     */
		function getWebsiteBatchPrices($product, $websiteId = null) {
			$helper = $this->getWarehouseHelper(  );
			$websiteBatchPrices = array(  );

			if (is_null( $websiteId )) {
				$websiteId = $this->getProductHelper(  )->getWebsiteId( $product );
			}

			$stockIds = $helper->getStockIds(  );

			if (count( $stockIds )) {
				$batchPrices = $product->getBatchPrices(  );

				if (count( $batchPrices )) {
					foreach ($stockIds as $stockId) {

						if ($helper->getProductPriceHelper(  )->isWebsiteScope(  )) {
							if (( ( $websiteId && isset( $batchPrices[$websiteId] ) ) && isset( $batchPrices[$websiteId][$stockId] ) )) {
								$websiteBatchPrices[$stockId] = $batchPrices[$websiteId][$stockId];
							}
						}


						if (( ( !isset( $websiteBatchPrices[$stockId] ) && isset( $batchPrices[0] ) ) && isset( $batchPrices[0][$stockId] ) )) {
							$websiteBatchPrices[$stockId] = $batchPrices[0][$stockId];
							continue;
						}
					}
				}
			}

			return $websiteBatchPrices;
		}

		/**
     * Set website batch prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $websiteId
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function setWebsiteBatchPrices($product, $websiteId = null) {
			if (!$product->hasWebsiteBatchPrices(  )) {
				$product->setWebsiteBatchPrices( $this->getWebsiteBatchPrices( $product, $websiteId ) );
			}

			return $this;
		}

		/**
     * Get website batch price by stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $stockId
     * 
     * @return mixed 
     */
		function getWebsiteBatchPriceByStockId($product, $stockId) {
			$batchPrice = null;
			$batchPrices = $product->getWebsiteBatchPrices(  );

			if (isset( $batchPrices[$stockId] )) {
				$batchPrice = (double)$batchPrices[$stockId];
			}

			return $batchPrice;
		}

		/**
     * Get max website batch price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return mixed 
     */
		function getMaxWebsiteBatchPrice($product) {
			$product->getWebsiteBatchPrices(  );
			$batchPrices = $price = null;

			if (( !is_null( $batchPrices ) && count( $batchPrices ) )) {
				foreach ($batchPrices as $batchPrice) {

					if (is_null( $price )) {
						$price = $batchPrice;
						continue;
					}


					if ($price < $batchPrice) {
						$price = $batchPrice;
						continue;
					}
				}
			}

			return $price;
		}

		/**
     * Set product price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function setPrice($product) {
			$this->setWebsiteBatchPrices( $product );

			if (!$product->getData( '_edit_mode' )) {
				if (( !$product->getInitialPrice(  ) && $product->getPrice(  ) )) {
					$product->setInitialPrice( $product->getPrice(  ) );
				}

				$batchPrice = null;
				$stockId = $this->getProductHelper(  )->getCurrentStockId( $product );

				if ($stockId) {
					$batchPrices = $product->getWebsiteBatchPrices(  );

					if (isset( $batchPrices[$stockId] )) {
						$batchPrice = (double)$batchPrices[$stockId];
					}
				} 
else {
					$batchPrice = $this->getMaxWebsiteBatchPrice( $product );
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

			return $this;
		}

		/**
     * Get stock price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * 
     * @return float
     */
		function getStockPrice($product, $stockId) {
			$price = $this->getWebsiteBatchPriceByStockId( $product, $stockId );

			if (is_null( $price )) {
				$price = $product->getInitialPrice(  );
			}


			if (is_null( $price )) {
				$price = $product->getPrice(  );
			}

			return $price;
		}

		/**
     * Get default price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $websiteId
     * @param mixed $stockId
     * 
     * @return mixed 
     */
		function getDefaultPrice($product, $websiteId, $stockId) {
			$price = $product->getPrice(  );
			$helper = $this->getWarehouseHelper(  );

			if (( $helper->getProductPriceHelper(  )->isWebsiteScope(  ) && $websiteId )) {
				$batchPrices = $product->getBatchPrices(  );

				if (( isset( $batchPrices[0] ) && isset( $batchPrices[0][$stockId] ) )) {
					$price = (double)$batchPrices[0][$stockId];
				}
			}

			return $price;
		}

		/**
     * Get website batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $websiteId
     * 
     * @return array
     */
		function getWebsiteBatchSpecialPrices($product, $websiteId = null) {
			$helper = $this->getWarehouseHelper(  );
			$websiteBatchSpecialPrices = array(  );

			if (is_null( $websiteId )) {
				$websiteId = $this->getProductHelper(  )->getWebsiteId( $product );
			}

			$stockIds = $helper->getStockIds(  );

			if (count( $stockIds )) {
				$batchSpecialPrices = $product->getBatchSpecialPrices(  );

				if (count( $batchSpecialPrices )) {
					foreach ($stockIds as $stockId) {

						if ($helper->getProductPriceHelper(  )->isWebsiteScope(  )) {
							if (( ( $websiteId && isset( $batchSpecialPrices[$websiteId] ) ) && isset( $batchSpecialPrices[$websiteId][$stockId] ) )) {
								$websiteBatchSpecialPrices[$stockId] = $batchSpecialPrices[$websiteId][$stockId];
							}
						}


						if (( ( !isset( $websiteBatchSpecialPrices[$stockId] ) && isset( $batchSpecialPrices[0] ) ) && isset( $batchSpecialPrices[0][$stockId] ) )) {
							$websiteBatchSpecialPrices[$stockId] = $batchSpecialPrices[0][$stockId];
							continue;
						}
					}
				}
			}

			return $websiteBatchSpecialPrices;
		}

		/**
     * Set website batch special prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $websiteId
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function setWebsiteBatchSpecialPrices($product, $websiteId = null) {
			if (!$product->hasWebsiteBatchSpecialPrices(  )) {
				$product->setWebsiteBatchSpecialPrices( $this->getWebsiteBatchSpecialPrices( $product, $websiteId ) );
			}

			return $this;
		}

		/**
     * Get max website batch special price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return mixed 
     */
		function getMaxWebsiteBatchSpecialPrice($product) {
			$price = null;
			$batchSpecialPrices = $product->getWebsiteBatchSpecialPrices(  );

			if (( !is_null( $batchSpecialPrices ) && count( $batchSpecialPrices ) )) {
				foreach ($batchSpecialPrices as $batchSpecialPrice) {

					if (is_null( $price )) {
						$price = $batchSpecialPrice;
						continue;
					}


					if ($price < $batchSpecialPrice) {
						$price = $batchSpecialPrice;
						continue;
					}
				}
			}

			return $price;
		}

		/**
     * Set special price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function setSpecialPrice($product) {
			$this->setWebsiteBatchSpecialPrices( $product );

			if (!$product->getData( '_edit_mode' )) {
				if (is_null( $product->getInitialSpecialPrice(  ) )) {
					$specialPrice = ($product->getSpecialPrice(  ) ? $product->getSpecialPrice(  ) : false);
					$product->setInitialSpecialPrice( $specialPrice );
				}

				$batchSpecialPrice = null;
				$stockId = $this->getProductHelper(  )->getCurrentStockId( $product );

				if ($stockId) {
					$batchSpecialPrices = $product->getWebsiteBatchSpecialPrices(  );

					if (isset( $batchSpecialPrices[$stockId] )) {
						$batchSpecialPrice = (double)$batchSpecialPrices[$stockId];
					}
				}


				if (( is_null( $batchSpecialPrice ) && $product->getInitialSpecialPrice(  ) )) {
					$batchSpecialPrice = $product->getInitialSpecialPrice(  );
				}


				if (!is_null( $batchSpecialPrice )) {
					$product->setFinalPrice( null );
					$batchSpecialPrice = round( $batchSpecialPrice, 4 );
					$product->setSpecialPrice( $batchSpecialPrice );
				} 
else {
					$product->setFinalPrice( null );
					$product->setSpecialPrice( null );
				}
			}

			return $this;
		}

		/**
     * Get default special price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param mixed $websiteId
     * @param mixed $stockId
     * 
     * @return mixed 
     */
		function getDefaultSpecialPrice($product, $websiteId, $stockId) {
			$price = $product->getSpecialPrice(  );
			$helper = $this->getWarehouseHelper(  );

			if (( $helper->getProductPriceHelper(  )->isWebsiteScope(  ) && $websiteId )) {
				$batchPrices = $product->getBatchSpecialPrices(  );

				if (( isset( $batchPrices[0] ) && isset( $batchPrices[0][$stockId] ) )) {
					$price = (double)$batchPrices[0][$stockId];
				}
			}

			return $price;
		}

		/**
     * Load price
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function loadPrice($product) {
			$this->loadBatchPrices( $product );
			$this->loadBatchSpecialPrices( $product );
			$this->loadTierPrice( $product );
		}

		/**
     * Apply Prices
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function applyPrice($product) {
			$this->loadPrice( $product );
			$this->setPrice( $product );
			$this->setSpecialPrice( $product );
			$this->setTierPrice( $product );
			return $this;
		}

		/**
     * Get escaped price
     * 
     * @param float $price
     * 
     * @return float
     */
		function getEscapedPrice($price) {
			if (!is_numeric( $price )) {
				return null;
			}

			return number_format( $price, 2, null, '' );
		}
	}

?>