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

	class Innoexts_Warehouse_Helper_Catalog_Product extends Mage_Core_Helper_Abstract {
		private $_quotes = array(  );

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get price helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function getPriceHelper() {
			return Mage::helper( 'warehouse/catalog_product_price' );
		}

		/**
     * Get configuration helper
     * 
     * @return Mage_Catalog_Helper_Product_Configuration
     */
		function getConfigurationHelper() {
			return Mage::helper( 'catalog/product_configuration' );
		}

		/**
     * Get bundle configuration helper
     * 
     * @return Mage_Bundle_Helper_Catalog_Product_Configuration
     */
		function getBundleConfigurationHelper() {
			return Mage::helper( 'bundle/catalog_product_configuration' );
		}

		/**
     * Get downloadable configuration helper
     * 
     * @return Mage_Bundle_Helper_Catalog_Product_Configuration
     */
		function getDownloadableConfigurationHelper() {
			return Mage::helper( 'downloadable/catalog_product_configuration' );
		}

		/**
     * Get model helper
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function getModelHelper() {
			return $this->getWarehouseHelper(  )->getModelHelper(  );
		}

		/**
     * Get product attribute by code
     *
     * @param string $code
     * 
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
		function getAttribute($code) {
			return Mage::getSingleton( 'eav/config' )->getAttribute( ENTITY, $code );
		}

		/**
     * Get tier price attribute
     * 
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
		function getTierPriceAttribute() {
			return $this->getAttribute( 'tier_price' );
		}

		/**
     * Clone Product
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Mage_Catalog_Model_Product
     */
		function cloneProduct($product) {
			clone $product;
			$newProduct = ;
			foreach (array_keys( $newProduct->getData(  ) ) as $key) {

				if (substr( $key, 0, 15 ) == '_cache_instance') {
					$newProduct->unsetData( $key );
					continue;
				}
			}

			return $newProduct;
		}

		/**
     * Check if product is simple
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return boolean
     */
		function isSimple($product) {
			return ($product->getTypeId(  ) == TYPE_SIMPLE ? true : false);
		}

		/**
     * Check if product is bundle
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return boolean
     */
		function isBundle($product) {
			return ($product->getTypeId(  ) == TYPE_BUNDLE ? true : false);
		}

		/**
     * Check if product is configurable
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return boolean
     */
		function isConfigurable($product) {
			return ($product->getTypeId(  ) == TYPE_CONFIGURABLE ? true : false);
		}

		/**
     * Check if product is grouped
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return boolean
     */
		function isGrouped($product) {
			return ($product->getTypeId(  ) == TYPE_GROUPED ? true : false);
		}

		/**
     * Check if product is virtual
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return boolean
     */
		function isVirtual($product) {
			return ($product->getTypeId(  ) == TYPE_VIRTUAL ? true : false);
		}

		/**
     * Check if product is downloadable
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return boolean
     */
		function isDownloadable($product) {
			return ($product->getTypeId(  ) == TYPE_DOWNLOADABLE ? true : false);
		}

		/**
     * Check if group price is fixed
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return bool
     */
		function isGroupPriceFixed($product) {
			return $this->getPriceHelper(  )->isGroupPriceFixed( $product->getTypeId(  ) );
		}

		/**
     * Get website id by store id
     * 
     * @param int $storeId
     * 
     * @return int 
     */
		function getWebsiteIdByStoreId($storeId) {
			$websiteId = null;

			if ($this->getPriceHelper(  )->isWebsiteScope(  )) {
				$websiteId = $this->getWarehouseHelper(  )->getWebsiteIdByStoreId( $storeId );
			} 
else {
				$websiteId = 119;
			}

			return $websiteId;
		}

		/**
     * Get website id
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getWebsiteId($product) {
			return $this->getWebsiteIdByStoreId( (int)$product->getStoreId(  ) );
		}

		/**
     * Get stock items
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array of Mage_Cataloginventory_Model_Stock_Item
     */
		function getStockItems($product) {
			if (!$product->hasData( 'stock_items' )) {
				$productId = (int)$product->getId(  );

				if ($productId) {
					$stockItems = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockItemsCached( $productId );
					$product->setData( 'stock_items', $stockItems );
				}
			}

			return $product->getData( 'stock_items' );
		}

		/**
     * Get in stock stock items
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array of Mage_Cataloginventory_Model_Stock_Item
     */
		function getInStockStockItems($product) {
			if (!$product->hasData( 'in_stock_stock_items' )) {
				$productId = (int)$product->getId(  );

				if ($productId) {
					$inStockStockItems = array(  );
					$stockItems = $this->getStockItems( $product );

					if (count( $stockItems )) {
						foreach ($stockItems as $stockItem) {

							if ($stockItem->getIsInStock(  )) {
								$inStockStockItems[$stockItem->getStockId(  )] = $stockItem;
								continue;
							}
						}
					}

					$product->setData( 'in_stock_stock_items', $inStockStockItems );
				}
			}

			return $product->getData( 'in_stock_stock_items' );
		}

		/**
     * Check if stock items in stock
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return bool
     */
		function isStockItemsInStock($product) {
			$stockItems = $this->getInStockStockItems( $product );

			if (count( $stockItems )) {
				return true;
			}

			return false;
		}

		/**
     * Get in stock stock identifiers
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getInStockStockIds($product) {
			$stockIds = array(  );
			$stockItems = $this->getInStockStockItems( $product );

			if (count( $stockItems )) {
				foreach ($stockItems as $stockItem) {
					$stockId = (int)$stockItem->getStockId(  );

					if ($stockId) {
						array_push( $stockIds, $stockId );
						continue;
					}
				}
			}

			return $stockIds;
		}

		/**
     * Get stock item
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * 
     * @return Mage_Cataloginventory_Model_Stock_Item
     */
		function getStockItem($product, $stockId) {
			$stockItem = null;
			$stockItems = $this->getStockItems( $product );

			if (isset( $stockItems[$stockId] )) {
				$stockItem = $stockItems[$stockId];
			}

			return $stockItem;
		}

		/**
     * Set session stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function setSessionStockId($product, $stockId) {
			$session = $this->getWarehouseHelper(  )->getSession(  );
			$productId = (int)$product->getId(  );
			$session->setProductStockId( $productId, $stockId );
			return $this;
		}

		/**
     * Get session stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getSessionStockId($product) {
			$session = $this->getWarehouseHelper(  )->getSession(  );
			$productId = (int)$product->getId(  );
			return $session->getProductStockId( $productId );
		}

		/**
     * Remove session stock ids
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function removeSessionStockIds() {
			$session = $this->getWarehouseHelper(  )->getSession(  );
			$session->removeProductStockIds(  );
			return $this;
		}

		/**
     * Get stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getStockId($product) {
			return $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getProductStockId( $product );
		}

		/**
     * Get current stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getCurrentStockId($product) {
			$stockId = null;
			$stockItem = $product->getStockItem(  );

			if ($stockItem) {
				if ($stockItem->getStockId(  )) {
					$stockId = (int)$stockItem->getStockId(  );
				}
			}

			return $stockId;
		}

		/**
     * Get stock priorities
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array 
     */
		function getStockPriorities($product) {
			if (!$product->hasStockPriorities(  )) {
				$productId = (int)$product->getId(  );

				if ($productId) {
					$this->loadStockPriorities( $product );
				}
			}

			return $product->getStockPriorities(  );
		}

		/**
     * Get stock priority
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * 
     * @return int
     */
		function getStockPriority($product, $stockId) {
			$stockPriority = null;
			$stockPriorities = ($product ? $this->getStockPriorities( $product ) : array(  ));

			if (isset( $stockPriorities[$stockId] )) {
				$stockPriority = (int)$stockPriorities[$stockId];
			}


			if (is_null( $stockPriority )) {
				$warehouse = $this->getWarehouseHelper(  )->getWarehouseByStockId( $stockId );

				if ($warehouse) {
					$stockPriority = (int)$warehouse->getPriority(  );
				}
			}

			return $stockPriority;
		}

		/**
     * Get min priority stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $stockIds
     * 
     * @return int
     */
		function getMinPriorityStockId($product, $stockIds) {
			$minStockId = null;
			$minStockPriority = null;
			foreach ($stockIds as $stockId) {
				$stockPriority = $this->getStockPriority( $product, $stockId );

				if (( !is_null( $stockPriority ) && ( is_null( $minStockPriority ) || $stockPriority < $minStockPriority ) )) {
					$minStockPriority = $minStockId;
					$minStockId = $stockIds;
					continue;
				}
			}

			return $minStockId;
		}

		/**
     * Get stock shipping carriers
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * 
     * @return array
     */
		function getStockShippingCarriers($product, $stockId = null) {
			$helper = $this->getWarehouseHelper(  );
			$shippingCarriers = array(  );
			$stockShippingCarriers = $product->getStockShippingCarriers(  );

			if (is_null( $stockId )) {
				$shippingCarriers = null;
				foreach ($helper->getStockIds(  ) as $shippingCarriers) {
					$_shippingCarriers = array(  );

					if (!isset( $stockShippingCarriers[$stockId] )) {
						$warehouse = $helper->getWarehouseByStockId( $stockId );

						if ($warehouse) {
							$_shippingCarriers = $warehouse->getShippingCarriers(  );
							continue;
						}
					} 
else {
						$_shippingCarriers = $stockShippingCarriers[$stockId];
					}


					if (is_null( $shippingCarriers )) {
						$shippingCarriers = $stockId;
						continue;
					}

					array_intersect( $shippingCarriers, $_shippingCarriers );
				}


				if (is_null( $shippingCarriers )) {
					$shippingCarriers = array(  );
				}
			} 
else {
				if (!isset( $stockShippingCarriers[$stockId] )) {
					$warehouse = $helper->getWarehouseByStockId( $stockId );

					if ($warehouse) {
						$shippingCarriers = $warehouse->getShippingCarriers(  );
					}
				} 
else {
					$shippingCarriers = $stockShippingCarriers[$stockId];
				}
			}

			return $shippingCarriers;
		}

		/**
     * Save child data
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataKeyAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function saveChildData($product, $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			$this->getModelHelper(  )->saveChildData2( $product, 'Mage_Catalog_Model_Product', 'product_id', $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType );
			return $this;
		}

		/**
     * Add child data
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $array
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addChildData($product, $array, $dataAttributeCode) {
			$this->getModelHelper(  )->addChildData( $product, 'Mage_Catalog_Model_Product', $array, $dataAttributeCode );
			return $this;
		}

		/**
     * Load child data
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataKeyAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadChildData($product, $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			$this->getModelHelper(  )->loadChildData2( $product, 'Mage_Catalog_Model_Product', 'product_id', $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType );
			return $this;
		}

		/**
     * Load collection child data
     * 
     * @param Varien_Data_Collection_Db $collection
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCollectionChildData($collection, $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			$this->getModelHelper(  )->loadCollectionChildData2( $collection, 'product_id', $dataTableName, $dataAttributeCode, $dataKeyAttributeCode, $dataValueAttributeCode, $dataValueType );
			return $this;
		}

		/**
     * Remove child data
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeChildData($product, $dataAttributeCode) {
			$this->getModelHelper(  )->removeChildData( $product, 'Mage_Catalog_Model_Product', $dataAttributeCode );
			return $this;
		}

		/**
     * Save stock shelves
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function saveStockShelves($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShelvesEnabled(  )) {
				return $this;
			}

			$this->saveChildData( $product, 'catalog/product_shelf', 'shelves', 'stock_id', 'name', 'string' );
			return $this;
		}

		/**
     * Add data stock shelves
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $array
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataStockShelves($product, $array) {
			$this->addChildData( $product, $array, 'shelves' );
			return $this;
		}

		/**
     * Load stock shelves
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function loadStockShelves($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShelvesEnabled(  )) {
				return $this;
			}

			$this->loadChildData( $product, 'catalog/product_shelf', 'shelves', 'stock_id', 'name', 'string' );
			return $this;
		}

		/**
     * Remove stock shelves
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function removeStockShelves($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShelvesEnabled(  )) {
				return $this;
			}

			$this->removeChildData( $product, 'shelves' );
			return $this;
		}

		/**
     * Save stock shipping carriers
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function saveStockShippingCarriers($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			$this->saveChildData( $product, 'catalog/product_stock_shipping_carrier', 'stock_shipping_carriers', 'stock_id', 'shipping_carrier', 'array' );
			return $this;
		}

		/**
     * Add data stock shipping carriers
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $array
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataStockShippingCarriers($product, $array) {
			$this->addChildData( $product, $array, 'stock_shipping_carriers' );
			return $this;
		}

		/**
     * Load stock shipping carriers
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function loadStockShippingCarriers($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			$this->loadChildData( $product, 'catalog/product_stock_shipping_carrier', 'stock_shipping_carriers', 'stock_id', 'shipping_carrier', 'array' );
			return $this;
		}

		/**
     * Load collection stock shipping carriers
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function loadCollectionStockShippingCarriers($collection) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			$this->loadCollectionChildData( $collection, 'catalog/product_stock_shipping_carrier', 'stock_shipping_carriers', 'stock_id', 'shipping_carrier', 'array' );
			return $this;
		}

		/**
     * Remove stock shipping carriers
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function removeStockShippingCarriers($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			$this->removeChildData( $product, 'stock_shipping_carriers' );
			return $this;
		}

		/**
     * Save stock priorities
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function saveStockPriorities($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isPriorityEnabled(  )) {
				return $this;
			}

			$this->saveChildData( $product, 'catalog/product_stock_priority', 'stock_priorities', 'stock_id', 'priority', 'int' );
			return $this;
		}

		/**
     * Add data stock priorities
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $array
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataStockPriorities($product, $array) {
			$this->addChildData( $product, $array, 'stock_priorities' );
			return $this;
		}

		/**
     * Load stock priorities
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function loadStockPriorities($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isPriorityEnabled(  )) {
				return $this;
			}

			$this->loadChildData( $product, 'catalog/product_stock_priority', 'stock_priorities', 'stock_id', 'priority', 'int' );
			return $this;
		}

		/**
     * Load collection stock priorities
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function loadCollectionStockPriorities($collection) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isPriorityEnabled(  )) {
				return $this;
			}

			$this->loadCollectionChildData( $collection, 'catalog/product_stock_priority', 'stock_priorities', 'stock_id', 'priority', 'int' );
			return $this;
		}

		/**
     * Remove stock priorities
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function removeStockPriorities($product) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (!$config->isPriorityEnabled(  )) {
				return $this;
			}

			$this->removeChildData( $product, 'stock_priorities' );
			return $this;
		}

		/**
     * Get options
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getCustomOptions($product) {
			if (( $product->getHasOptions(  ) && count( $product->getOptions(  ) ) == 0 )) {
				foreach ($product->getProductOptionsCollection(  ) as $option) {
					$option->setProduct( $product );
					$product->addOption( $option );
				}
			}

			return $product->getOptions(  );
		}

		/**
     * Get custom option default value
     * 
     * @param Mage_Catalog_Model_Product_Option $option
     * 
     * @return mixed
     */
		function getCustomOptionDefaultValue($option) {
			$value = null;
			$optionGroup = $option->getGroupByType(  );
			$optionGroupDate = OPTION_GROUP_DATE;
			$optionGroupSelect = OPTION_GROUP_SELECT;

			if ($optionGroup == $optionGroupSelect) {
				foreach ($option->getValues(  ) as $value) {
					$value = $value->getId(  );
					break;
				}
			} 
else {
				if ($optionGroup == $optionGroupDate) {
					$value = array( 'month' => (int)date( 'm' ), 'day' => (int)date( 'd' ), 'year' => (int)date( 'Y' ), 'minute' => (int)date( 'S' ), 'hour' => (int)date( 'g' ), 'day_part' => (int)date( 'a' ) );
				} 
else {
					$value = 'Enabled';
				}
			}

			return $value;
		}

		/**
     * Get default qty
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return float
     */
		function getDefaultQty($product) {
			$qty = 133;

			if ($product->getDefaultQty(  )) {
				$qty = $product->getDefaultQty(  );
			}

			return $qty;
		}

		/**
     * Get default custom options
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getDefaultCustomOptions($product) {
			$values = array(  );
			$options = $this->getCustomOptions( $product );
			foreach ($options as $value) {

				if (!$option->getIsRequire(  )) {
					continue;
				}

				$optionId = $option->getId(  );
				$this->getCustomOptionDefaultValue( $option );
				$values[$optionId] = $value;
			}

			return $values;
		}

		/**
     * Get default configurable options
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getDefaultConfigurableOptions($product) {
			$values = array(  );
			$typeInstance = $product->getTypeInstance(  );
			$childProducts = $typeInstance->getUsedProducts( null, $product );
			$attributes = $typeInstance->getConfigurableAttributes( $product );
			foreach ($childProducts as $childProduct) {
				foreach ($attributes as $attribute) {
					$productAttribute = $attribute->getProductAttribute(  );
					$productAttributeId = $productAttribute->getId(  );
					$attributeValue = $childProduct->getData( $productAttribute->getAttributeCode(  ) );
					$values[$productAttributeId] = $attributeValue;
				}

				break;
			}

			return $values;
		}

		/**
     * Get default bundle options
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getDefaultBundleOptions($product) {
			$values = array(  );
			$typeInstance = $product->getTypeInstance(  );
			$typeInstance->setStoreFilter( $product->getStoreId(  ), $product );
			$optionCollection = $typeInstance->getOptionsCollection( $product );
			$selectionCollection = $typeInstance->getSelectionsCollection( $typeInstance->getOptionsIds( $product ), $product );

			if ($this->getWarehouseHelper(  )->getVersionHelper(  )->isGe1700(  )) {
				$options = $optionCollection->appendSelections( $selectionCollection, false, Mage::helper( 'catalog/product' )->getSkipSaleableCheck(  ) );
			} 
else {
				$options = $optionCollection->appendSelections( $selectionCollection, false, false );
			}

			foreach ($options as $option) {

				if (!$option->getSelections(  )) {
					continue;
				}

				$optionId = ;
				$isMultipleOption = $option->getId(  );
				$option->getRequired(  );
				$isRequired = $option->isMultiSelection(  );
				$selectionId = null;
				foreach ($option->getSelections(  ) as $selection) {

					if ($selection->getIsDefault(  )) {
						$selectionId = (int)$selection->getSelectionId(  );
						break;
					}
				}


				if (( !$selectionId && $isRequired )) {
					foreach ($option->getSelections(  ) as $selection) {
						$selectionId = (int)$selection->getSelectionId(  );
						break;
					}
				}


				if ($isMultipleOption) {
					$values[$optionId] = array( $selectionId );
					continue;
				}

				$values[$optionId] = $selectionId;
			}

			return $values;
		}

		/**
     * Get default grouped options
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getDefaultGroupedOptions($product) {
			$typeInstance = $product->getTypeInstance(  );
			$typeInstance->getAssociatedProducts( $product );
			$associatedProducts = $values = array(  );
			foreach ($associatedProducts as $associatedProduct) {
				$values[$associatedProduct->getId(  )] = 1;
			}

			return $values;
		}

		/**
     * Get default downloadable options
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return array
     */
		function getDefaultDownloadableOptions($product) {
			$values = array(  );
			$typeInstance = $product->getTypeInstance(  );
			$links = $typeInstance->getLinks( $product );
			foreach ($links as $link) {
				$values[] = (int)$link->getId(  );
				break;
			}

			return $values;
		}

		/**
     * Get default buy request
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Varien_Object
     */
		function getDefaultBuyRequest($product) {
			$key = 'default_buy_request';

			if (!$product->hasData( $key )) {
				$buyRequest = new Varien_Object(  );
				$buyRequest->setProduct( $product->getId(  ) );
				$buyRequest->setQty( $this->getDefaultQty( $product ) );
				$buyRequest->setOptions( $this->getDefaultCustomOptions( $product ) );

				if ($this->isConfigurable( $product )) {
					$buyRequest->setSuperAttribute( $this->getDefaultConfigurableOptions( $product ) );
				} 
else {
					if ($this->isBundle( $product )) {
						$buyRequest->setBundleOption( $this->getDefaultBundleOptions( $product ) );
					} 
else {
						if ($this->isGrouped( $product )) {
							$buyRequest->setSuperGroup( $this->getDefaultGroupedOptions( $product ) );
						} 
else {
							if ($this->isDownloadable( $product )) {
								$buyRequest->setLinks( $this->getDefaultDownloadableOptions( $product ) );
							}
						}
					}
				}

				$product->setData( $key, $buyRequest );
			}

			return $product->getData( $key );
		}

		/**
     * Get buy request
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Varien_Object
     */
		function getBuyRequest($product) {
			$key = 'buy_request';

			if (!$product->hasData( $key )) {
				$values = $product->getPreconfiguredValues(  );

				if (( ( $values && count( $values->getData(  ) ) ) && !count( $values->getErrors(  ) ) )) {
					$buyRequest = new Varien_Object(  );
					foreach ($values->getData(  ) as $key => $value) {

						if (!in_array( $key, array( 'errors' ) )) {
							$buyRequest->setData( $key, $value );
							continue;
						}
					}
				} 
else {
					$buyRequest = $this->getDefaultBuyRequest( $product );
				}

				$product->setData( $key, $buyRequest );
			}

			return $product->getData( $key );
		}

		/**
     * Get quote hash
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return string
     */
		function getQuoteHash($product, $stockId, $buyRequest = null) {
			$string = $product->getId(  ) . ':' . $stockId;

			if ($buyRequest) {
				$string .= ':' . serialize( $buyRequest );
			}

			return md5( $string );
		}

		/**
     * Get quote
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return Mage_Sales_Model_Quote
     */
		function getQuote($product, $stockId, $buyRequest = null) {
			if (!$buyRequest) {
				$buyRequest = $this->getBuyRequest( $product );
			}

			$hash = $this->getQuoteHash( $product, $stockId, $buyRequest );

			if (!isset( $this->_quotes[$hash] )) {
				$helper = $this->getWarehouseHelper(  );
				$quoteHelper = $helper->getQuoteHelper(  );
				$quote = $quoteHelper->getQuoteByProductAndStockId( $this->cloneProduct( $product ), $stockId, $buyRequest );
				$this->_quotes[$hash] = $quote;
			}

			return $this->_quotes[$hash];
		}

		/**
     * Get quote is in stock
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return boolean
     */
		function getQuoteIsInStock($product, $stockId, $buyRequest = null) {
			$isInStock = false;
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if (( $quote && $quote->hasItems(  ) )) {
				$isInStock = true;
				foreach ($quote->getAllVisibleItems(  ) as $item) {
					if (!$item->getStockItem(  )->getIsInStock(  )) {
						$isInStock = false;
						break;
					}
				}
			}

			return $isInStock;
		}

		/**
     * Get quote in stock stock ids
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param Varien_Object $buyRequest
     * 
     * @return boolean
     */
		function getQuoteInStockStockIds($product, $buyRequest = null) {
			$stockIds = array(  );
			foreach ($this->getWarehouseHelper(  )->getStockIds(  ) as $stockId) {
				if ($this->getQuoteIsInStock( $product, $stockId, $buyRequest )) {
					array_push( $stockIds, $stockId );
					continue;
				}
			}

			return $stockIds;
		}

		/**
     * Check if quote is in stock
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param Varien_Object $buyRequest
     * 
     * @return boolean
     */
		function isQuoteInStock($product, $buyRequest = null) {
			$stockIds = $this->getQuoteInStockStockIds( $product, $buyRequest );
			return (count( $stockIds ) ? true : false);
		}

		/**
     * Check if quote single stock id is in stock
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param Varien_Object $buyRequest
     * 
     * @return boolean
     */
		function isQuoteSingleStockIdInStock($product, $buyRequest = null) {
			$stockIds = $this->getQuoteInStockStockIds( $product, $buyRequest );
			return (count( $stockIds ) == 1 ? true : false);
		}

		/**
     * Get quote max qty
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return float|null
     */
		function getQuoteMaxQty($product, $stockId, $buyRequest = null) {
			$maxQty = null;
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if (( $quote && $quote->hasItems(  ) )) {
				$items = $quote->getAllVisibleItems(  );
				$isSingleItem = (count( $items ) == 1 ? true : false);
				foreach ($items as $item) {

					if (( $isSingleItem || !$item->isParentItem(  ) )) {
						$qty = (double)$item->getStockItem(  )->getQty(  );

						if (( is_null( $maxQty ) || $qty < $maxQty )) {
							$maxQty = $quote;
							continue;
						}

						continue;
					}
				}
			}

			return $maxQty;
		}

		/**
     * Get quote subtotal
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return float|null
     */
		function getQuoteSubtotal($product, $stockId, $buyRequest = null) {
			$subtotal = null;
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if ($quote) {
				$quoteHelper = ;
				$quoteHelper->getSubtotal( $quote );
				$subtotal = $this->getWarehouseHelper(  )->getQuoteHelper(  );
			}

			return $subtotal;
		}

		/**
     * Get quote min subtotal stock id
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $stockIds
     * @param Varien_Object $buyRequest
     * 
     * @return int
     */
		function getQuoteMinSubtotalStockId($product, $stockIds, $buyRequest = null) {
			$minStockId = null;
			$minSubtotal = null;
			foreach ($stockIds as $stockId) {
				$subtotal = $this->getQuoteSubtotal( $product, $stockId, $buyRequest );

				if (( !is_null( $subtotal ) && ( is_null( $minSubtotal ) || $subtotal < $minSubtotal ) )) {
					$minSubtotal = $subtotal;
					$minStockId = $stockId;
					continue;
				}
			}

			return $minStockId;
		}

		/**
     * Get quote grand total
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return float
     */
		function getQuoteGrandTotal($product, $stockId, $buyRequest = null) {
			$grandTotal = null;
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if ($quote) {
				$quoteHelper = $this->getWarehouseHelper(  )->getQuoteHelper(  );
				$grandTotal = $quoteHelper->getGrandTotal( $quote );
			}

			return $grandTotal;
		}

		/**
     * Get stock quote min grand total
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return float
     */
		function getQuoteMinGrandTotal($product, $stockId, $buyRequest = null) {
			$grandTotal = null;
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if ($quote) {
				$quoteHelper = $this->getWarehouseHelper(  )->getQuoteHelper(  );
				$grandTotal = $quoteHelper->getMinGrandTotal( $quote );
			}

			return $grandTotal;
		}

		/**
     * Get quote min grand total stock id
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $stockIds
     * @param Varien_Object $buyRequest
     * 
     * @return int
     */
		function getQuoteMinGrandTotalStockId($product, $stockIds, $buyRequest = null) {
			$minStockId = null;
			$minGrandTotal = null;
			foreach ($stockIds as $grandTotal) {
				$this->getQuoteMinGrandTotal( $product, $stockId, $buyRequest );

				if (( !is_null( $grandTotal ) && ( is_null( $minGrandTotal ) || $grandTotal < $minGrandTotal ) )) {
					$minGrandTotal = $buyRequest;
					$minStockId = $stockIds;
					continue;
				}
			}

			return $minStockId;
		}

		/**
     * Get quote tax amount
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return float|null
     */
		function getQuoteTaxAmount($product, $stockId, $buyRequest = null) {
			$taxAmount = null;
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if ($quote) {
				$quoteHelper = $this->getWarehouseHelper(  )->getQuoteHelper(  );
				$taxAmount = $quoteHelper->getTaxAmount( $quote );
			}

			return $taxAmount;
		}

		/**
     * Get quote min tax amount stock id
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $stockIds
     * @param Varien_Object $buyRequest
     * 
     * @return int
     */
		function getQuoteMinTaxAmountStockId($product, $stockIds, $buyRequest = null) {
			$minStockId = null;
			$minTaxAmount = null;
			foreach ($stockIds as $stockId) {
				$taxAmount = $this->getQuoteTaxAmount( $product, $stockId, $buyRequest );

				if (( !is_null( $taxAmount ) && ( is_null( $minTaxAmount ) || $taxAmount < $minTaxAmount ) )) {
					$minTaxAmount = $stockIds;
					$minStockId = $product;
					continue;
				}
			}

			return $minStockId;
		}

		/**
     * Get quote min shipping rate
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return Varien_Object 
     */
		function getQuoteMinShippingRate($product, $stockId, $buyRequest = null) {
			$this->getQuote( $product, $stockId, $buyRequest );
			$quote = $minShippingRate = null;

			if ($quote) {
				$this->getWarehouseHelper(  )->getQuoteHelper(  );
				$minShippingRate = $quoteHelper = $quoteHelper->getMinShippingRate( $quote );
			}

			return $minShippingRate;
		}

		/**
     * Get quote min shipping price
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return float
     */
		function getQuoteMinShippingPrice($product, $stockId, $buyRequest = null) {
			$minPrice = null;
			$shippingRate = $this->getQuoteMinShippingRate( $product, $stockId, $buyRequest );

			if ($shippingRate) {
				$minPrice = $shippingRate->getPrice(  );
			}

			return $minPrice;
		}

		/**
     * Get quote min shipping price stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param array $stockIds
     * @param Varien_Object $buyRequest
     * 
     * @return int
     */
		function getQuoteMinShippingPriceStockId($product, $stockIds, $buyRequest = null) {
			$minStockId = null;
			$minPrice = null;
			foreach ($stockIds as $stockId) {
				$price = $this->getQuoteMinShippingPrice( $product, $stockId, $buyRequest );

				if (( !is_null( $price ) && ( is_null( $minPrice ) || $price < $minPrice ) )) {
					$minPrice = $price;
					$minStockId = $stockId;
					continue;
				}
			}

			return $minStockId;
		}

		/**
     * Get quote configuration options
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param int $stockId
     * @param Varien_Object $buyRequest
     * 
     * @return array
     */
		function getQuoteConfigurationOptions($product, $stockId, $buyRequest = null) {
			$quote = $this->getQuote( $product, $stockId, $buyRequest );

			if (( $quote && $quote->hasItems(  ) )) {
				$item = null;
				foreach ($quote->getAllVisibleItems(  ) as $_item) {

					if ($_item->isParentItem(  )) {
						$item = $item;
						break;
					}
				}


				if (is_null( $item )) {
					foreach ($quote->getAllVisibleItems(  ) as $_item) {
						$item = $item;
						break;
					}
				}


				if ($item) {
					if ($this->isBundle( $product )) {
						$helper = $this->getBundleConfigurationHelper(  );
					} 
else {
						if ($this->isDownloadable( $product )) {
							$helper = $this->getDownloadableConfigurationHelper(  );
						} 
else {
							$helper = $this->getConfigurationHelper(  );
						}
					}

					return $helper->getOptions( $item );
				}

				return null;
			}

		}

		/**
     * Get default quote configuration options
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param Varien_Object $buyRequest
     * 
     * @return array
     */
		function getDefaultQuoteConfigurationOptions($product, $buyRequest = null) {
			return $this->getQuoteConfigurationOptions( $product, $this->getWarehouseHelper(  )->getDefaultStockId(  ), $buyRequest );
		}

		/**
     * Get formatted configuration option value
     * 
     * @param string $optionValue
     * 
     * @return string
     */
		function getFormatedConfigurationOptionValue($optionValue) {
			$helper = $this->getConfigurationHelper(  );
			$params = array( 'max_length' => 55, 'cut_replacer' => ' <a href="#" class="dots" onclick="return false">...</a>' );
			return $helper->getFormattedOptionValue( $optionValue, $params );
		}
	}

?>