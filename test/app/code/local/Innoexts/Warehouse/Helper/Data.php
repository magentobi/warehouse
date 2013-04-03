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

	class Innoexts_Warehouse_Helper_Data extends Mage_Core_Helper_Abstract {
		private $_warehouses = null;
		private $_addressStockIds = array(  );
		private $_nearestAddressStockIds = array(  );
		private $_stockPriorities = null;
		private $_addressStockPriorities = array(  );
		private $_addressStockDistances = array(  );

		/**
     * Get core helper
     * 
     * @return Innoexts_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'innoexts_core' );
		}

		/**
     * Get math helper
     * 
     * @return Innoexts_Core_Helper_Math
     */
		function getMathHelper() {
			return $this->getCoreHelper(  )->getMathHelper(  );
		}

		/**
     * Get address helper
     * 
     * @return Innoexts_Core_Helper_Address
     */
		function getAddressHelper() {
			return $this->getCoreHelper(  )->getAddressHelper(  );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getCoreHelper(  )->getVersionHelper(  );
		}

		/**
     * Get database helper
     * 
     * @return Innoexts_Core_Helper_Database
     */
		function getDatabaseHelper() {
			return $this->getCoreHelper(  )->getDatabaseHelper(  );
		}

		/**
     * Get model helper
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function getModelHelper() {
			return $this->getCoreHelper(  )->getModelHelper(  );
		}

		/**
     * Get customer locator helper
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function getCustomerLocatorHelper() {
			return Mage::helper( 'customerlocator' );
		}

		/**
     * Get geo coder helper
     * 
     * @return Innoexts_GeoCoder_Helper_Data
     */
		function getGeoCoderHelper() {
			return Mage::helper( 'innoexts_geocoder' );
		}

		/**
     * Get catalog inventory helper
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function getCatalogInventoryHelper() {
			return Mage::helper( 'warehouse/cataloginventory' );
		}

		/**
     * Get assignment method helper
     * 
     * @return Innoexts_Warehouse_Helper_Warehouse_Assignment_Method
     */
		function getAssignmentMethodHelper() {
			return Mage::helper( 'warehouse/warehouse_assignment_method' );
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
     * Get process helper
     * 
     * @return Innoexts_Warehouse_Helper_Index_Process
     */
		function getProcessHelper() {
			return Mage::helper( 'warehouse/index_process' );
		}

		/**
     * Get product price helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function getProductPriceHelper() {
			return $this->getProductHelper(  )->getPriceHelper(  );
		}

		/**
     * Get product price indexer helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer
     */
		function getProductPriceIndexerHelper() {
			return $this->getProductPriceHelper(  )->getIndexerHelper(  );
		}

		/**
     * Get shipping helper
     * 
     * @return Innoexts_Warehouse_Helper_Shipping
     */
		function getShippingHelper() {
			return Mage::helper( 'warehouse/shipping' );
		}

		/**
     * Get quote helper
     * 
     * @return Innoexts_Warehouse_Helper_Sales_Quote
     */
		function getQuoteHelper() {
			return Mage::helper( 'warehouse/sales_quote' );
		}

		/**
     * Get order helper
     * 
     * @return Innoexts_Warehouse_Helper_Sales_Order
     */
		function getOrderHelper() {
			return Mage::helper( 'warehouse/sales_order' );
		}

		/**
     * Get tax helper
     * 
     * @return Mage_Tax_Helper_Data
     */
		function getTaxHelper() {
			return Mage::helper( 'tax' );
		}

		/**
     * Get customer helper
     * 
     * @return Innoexts_Warehouse_Helper_Customer
     */
		function getCustomerHelper() {
			return Mage::helper( 'warehouse/customer' );
		}

		/**
     * Get currency helper
     * 
     * @return Innoexts_Warehouse_Helper_Directory_Currency
     */
		function getCurrencyHelper() {
			return Mage::helper( 'warehouse/directory_currency' );
		}

		/**
     * Get adminhtml helper
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function getAdminhtmlHelper() {
			return Mage::helper( 'warehouse/adminhtml' );
		}

		/**
     * Get config
     * 
     * @return Innoexts_Warehouse_Model_Config
     */
		function getConfig() {
			return Mage::getSingleton( 'warehouse/config' );
		}

		/**
     * Get core session
     * 
     * @return Mage_Core_Model_Session
     */
		function getCoreSession() {
			return Mage::getSingleton( 'core/session' );
		}

		/**
     * Get session
     * 
     * @return Innoexts_Warehouse_Model_Session
     */
		function getSession() {
			return Mage::getSingleton( 'warehouse/session' );
		}

		/**
     * Get warehouse collection
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Warehouse_Collection
     */
		function getWarehouseCollection() {
			return Mage::getSingleton( 'warehouse/warehouse' )->getCollection(  );
		}

		/**
     * Get warehouse resource
     * 
     * @return Mage_Core_Model_Mysql4_Abstract
     */
		function getWarehouseResource() {
			return Mage::getResourceSingleton( 'warehouse/warehouse' );
		}

		/**
     * Get table
     * 
     * @param string $entityName
     * 
     * @return string 
     */
		function getTable($entityName) {
			return Mage::getSingleton( 'core/resource' )->getTableName( $entityName );
		}

		/**
     * Check if multiple mode is enabled
     * 
     * @return bool
     */
		function isMultipleMode() {
			return $this->getConfig(  )->isMultipleMode(  );
		}

		/**
     * Check if admin store is active
     * 
     * @return boolean
     */
		function isAdmin() {
			return Mage::app(  )->getStore(  )->isAdmin(  );
		}

		/**
     * Get request action
     * 
     * @return string
     */
		function getRequestAction() {
			$request = Mage::app(  )->getRequest(  );
			$action = $request->getModuleName(  );

			if ($request->getControllerName(  )) {
				$action .= '_' . $request->getControllerName(  );
			}


			if ($request->getActionName(  )) {
				$action .= '_' . $request->getActionName(  );
			}

			return $action;
		}

		/**
     * Check if create order request is active
     * 
     * @return boolean
     */
		function isCreateOrderRequest() {
			if ($this->isAdmin(  )) {
				$controllerName = Mage::app(  )->getRequest(  )->getControllerName(  );

				if (in_array( strtolower( $controllerName ), array( 'sales_order_edit', 'sales_order_create' ) )) {
					return true;
				}

				return false;
			}

			return false;
		}

		/**
     * Get store by identifier
     * 
     * @param mixed $storeId
     * 
     * @return Mage_Core_Model_Store
     */
		function getStoreById($storeId) {
			return Mage::app(  )->getStore( $storeId );
		}

		/**
     * Get website by store identifier
     * 
     * @param mixed $storeId
     * 
     * @return Mage_Core_Model_Website 
     */
		function getWebsiteByStoreId($storeId) {
			return $this->getStoreById( $storeId )->getWebsite(  );
		}

		/**
     * Get website identifier by store identifier 
     * 
     * @param mixed $storeId
     * 
     * @return int
     */
		function getWebsiteIdByStoreId($storeId) {
			return $this->getStoreById( $storeId )->getWebsiteId(  );
		}

		/**
     * Get current store
     * 
     * @return Mage_Core_Model_Store
     */
		function getCurrentStore() {
			if (( $this->isAdmin(  ) && $this->isCreateOrderRequest(  ) )) {
				return Mage::getSingleton( 'adminhtml/session_quote' )->getStore(  );
			}

			return Mage::app(  )->getStore(  );
		}

		/**
     * Get stock identifiers
     * 
     * @return array
     */
		function getStockIds() {
			return $this->getCatalogInventoryHelper(  )->getStockIds(  );
		}

		/**
     * Get default stock identifier
     * 
     * @return int
     */
		function getDefaultStockId() {
			return $this->getCatalogInventoryHelper(  )->getDefaultStockId(  );
		}

		/**
     * Check if stock id exists
     * 
     * @param int $stockId
     * 
     * @return bool
     */
		function isStockIdExists($stockId) {
			return $this->getCatalogInventoryHelper(  )->isStockIdExists( $stockId );
		}

		/**
     * Get default warehouse identifier
     * 
     * @return int
     */
		function getDefaultWarehouseId() {
			return $this->getWarehouseIdByStockId( $this->getDefaultStockId(  ) );
		}

		/**
     * compare warehouses
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse1
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse2
     * 
     * @return int
     */
		function compareWarehouses($warehouse1, $warehouse2) {
			$this->getConfig(  );
			$value1 = $warehouse1->getId(  );
			$value2 = $config = $warehouse2->getId(  );

			if ($config->isSortByCode(  )) {
				$value1 = $warehouse1->getCode(  );
				$value2 = $warehouse2->getCode(  );
			} 
else {
				if ($config->isSortByTitle(  )) {
					$value1 = $warehouse1->getTitle(  );
					$value2 = $warehouse2->getTitle(  );
				} 
else {
					if ($config->isSortByPriority(  )) {
						$value1 = $warehouse1->getPriority(  );
						$value2 = $warehouse2->getPriority(  );
					} 
else {
						if ($config->isSortByOrigin(  )) {
							$value1 = $warehouse1->getOriginString(  );
							$value2 = $warehouse2->getOriginString(  );
						}
					}
				}
			}


			if ($value1 != $value2) {
				return ($value1 < $value2 ? -1 : 1);
			}

			return 0;
		}

		/**
     * Sort warehouses
     * 
     * @param array $warehouses
     * 
     * @return array
     */
		function sortWarehouses($warehouses) {
			usort( $warehouses, array( $this, 'compareWarehouses' ) );
			return $warehouses;
		}

		/**
     * Get warehouses
     * 
     * @return array
     */
		function getWarehouses() {
			if (is_null( $this->_warehouses )) {
				$warehouses = array(  );
				foreach ($this->getWarehouseCollection(  ) as $warehouse) {
					$warehouses[$warehouse->getId(  )] = $warehouse;
				}

				$this->_warehouses = $this->sortWarehouses( $warehouses );
			}

			return $this->_warehouses;
		}

		/**
     * Get warehouse
     * 
     * @param int $warehouseId
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse($warehouseId) {
			$warehouses = $this->getWarehouses(  );

			if (isset( $warehouses[$warehouseId] )) {
				return $warehouses[$warehouseId];
			}

		}

		/**
     * Get stock identifier by warehouse identifier
     * 
     * @param int $warehouseId
     * 
     * @return int
     */
		function getStockIdByWarehouseId($warehouseId) {
			$stockId = null;
			$warehouse = $this->getWarehouse( $warehouseId );

			if ($warehouse) {
				$stockId = $warehouse->getStockId(  );
			}

			return $stockId;
		}

		/**
     * Get warehouse by stock identifier
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouseByStockId($stockId) {
			if (!$stockId) {
				return null;
			}

			$warehouse = null;
			$warehouses = $this->getWarehouses(  );
			foreach ($warehouses as $_warehouse) {

				if ($_warehouse->getStockId(  ) == $stockId) {
					$warehouse = $stockId;
					break;
				}
			}

			return $warehouse;
		}

		/**
     * Get warehouse title by stock identifier
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getWarehouseTitleByStockId($stockId) {
			$warehouse = $this->getWarehouseByStockId( $stockId );

			if ($warehouse) {
				return $warehouse->getTitle(  );
			}

		}

		/**
     * Get warehouse code by stock identifier
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getWarehouseCodeByStockId($stockId) {
			$warehouse = $this->getWarehouseByStockId( $stockId );

			if ($warehouse) {
				return $warehouse->getCode(  );
			}

		}

		/**
     * Get warehouse identifier by stock identifier
     * 
     * @param int $stockId
     * 
     * @return int|null
     */
		function getWarehouseIdByStockId($stockId) {
			$warehouse = $this->getWarehouseByStockId( $stockId );

			if ($warehouse) {
				return $warehouse->getId(  );
			}

		}

		/**
     * Get warehouses by stock identifiers
     * 
     * @param array $stocksIds
     * 
     * @return array
     */
		function getWarehousesByStockIds($stockIds) {
			$warehouses = array(  );
			foreach ($this->getWarehouses(  ) as $warehouse) {

				if (in_array( $warehouse->getStockId(  ), $stockIds )) {
					array_push( $warehouses, $warehouse );
					continue;
				}
			}

			return $warehouses;
		}

		/**
     * Get warehouses options
     * 
     * @param bool $required
     * @param string $emptyLabel
     * @param string $emptyValue
     * 
     * @return array
     */
		function getWarehousesOptions($required = true, $emptyLabel = '', $emptyValue = '') {
			$options = $this->getWarehouseCollection(  )->toOptionArray(  );

			if (( 0 < count( $options ) && !$required )) {
				array_unshift( $options, array( 'value' => $emptyValue, 'label' => $emptyLabel ) );
			}

			return $options;
		}

		/**
     * Get stocks options
     * 
     * @param bool $required
     * @param string $emptyLabel
     * 
     * @return array
     */
		function getStocksOptions($required = true, $emptyLabel = '') {
			$options = $this->getWarehouseCollection(  )->toStockOptionArray( 'stock_id' );

			if (( 0 < count( $options ) && !$required )) {
				array_unshift( $options, array( 'value' => '', 'label' => $emptyLabel ) );
			}

			return $options;
		}

		/**
     * Get warehouses hash
     * 
     * @return array
     */
		function getWarehousesHash() {
			return $this->getWarehouseCollection(  )->toOptionHash(  );
		}

		/**
     * Get stocks hash
     * 
     * @return array
     */
		function getStocksHash() {
			return $this->getWarehouseCollection(  )->toOptionHash( 'stock_id' );
		}

		/**
     * Get customer address
     * 
     * @return Varien_Object
     */
		function getCustomerAddress() {
			return $this->getCustomerLocatorHelper(  )->getCustomerAddress(  );
		}

		/**
     * Set customer shipping address
     * 
     * @param Varien_Object $shippingAddress
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function setCustomerShippingAddress($shippingAddress) {
			$this->getCustomerLocatorHelper(  )->setCustomerAddress( $shippingAddress );
			return $this;
		}

		/**
     * Copy customer address
     * 
     * @param Varien_Object $address
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function copyCustomerAddress($address) {
			return $this->getAddressHelper(  )->copy( $this->getCustomerAddress(  ), $address );
		}

		/**
     * Copy customer address if destination address is empty
     * 
     * @param Varien_Object $address
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function copyCustomerAddressIfEmpty($address) {
			if ($this->getAddressHelper(  )->isEmpty( $address )) {
				$this->copyCustomerAddress( $address );
			}

			return $this;
		}

		/**
     * Set warehouse coordinates
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Warehouse_Data
     */
		function setWarehouseCoordinates($warehouse) {
			$coordinates = $this->getGeoCoderHelper(  )->getCoordinates( $warehouse->getOrigin(  ) );

			if (( $coordinates->getLatitude(  ) && $coordinates->getLongitude(  ) )) {
				$warehouse->setOriginLatitude( $coordinates->getLatitude(  ) );
				$warehouse->setOriginLongitude( $coordinates->getLongitude(  ) );
			}

			return $this;
		}

		/**
     * Update warehouses coordinates
     * 
     * @return Innoexts_Warehouse_Helper_Warehouse_Data
     */
		function updateWarehousesCoordinates() {
			foreach ($this->getWarehouses(  ) as $warehouse) {
				$this->setWarehouseCoordinates( $warehouse );
				$warehouse->save(  );
				sleep( 1 );
			}

			return $this;
		}

		/**
     * Get stock priorities
     * 
     * @return array
     */
		function getStockPriorities() {
			if (!isset( $this->_stockPriorities )) {
				$stockPriorities = array(  );
				foreach ($this->getWarehouses(  ) as $warehouse) {
					$stockId = (int)$warehouse->getStockId(  );
					$priority = (int)$warehouse->getPriority(  );
					$stockPriorities[$stockId] = $priority;
				}

				$this->_stockPriorities = $stockPriorities;
			}

			return $this->_stockPriorities;
		}

		/**
     * Set session stock id
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function setSessionStockId($stockId) {
			$this->getSession(  )->setStockId( $stockId );
			return $this;
		}

		/**
     * Get session stock id
     * 
     * @return int
     */
		function getSessionStockId() {
			return $this->getSession(  )->getStockId(  );
		}

		/**
     * Remove session stock id
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function removeSessionStockId() {
			$this->getSession(  )->removeStockId(  );
			return $this;
		}

		/**
     * Get stock id by store id
     * 
     * @param mixed $storeId
     * 
     * @return int
     */
		function getStockIdByStoreId($storeId) {
			$stockId = null;
			foreach ($this->getWarehouses(  ) as $warehouse) {
				$storeIds = $warehouse->getStoreIds(  );

				if (( ( $storeIds && count( $storeIds ) ) && in_array( $storeId, $storeIds ) )) {
					$stockId = (int)$warehouse->getStockId(  );
					break;
					continue;
				}
			}

			return $stockId;
		}

		/**
     * Get stock id by customer group id
     * 
     * @param mixed $customerGroupId
     * 
     * @return int
     */
		function getStockIdByCustomerGroupId($customerGroupId) {
			$stockId = null;
			foreach ($this->getWarehouses(  ) as $warehouse) {
				$customerGroupIds = $warehouse->getCustomerGroupIds(  );

				if (( ( $customerGroupIds && count( $customerGroupIds ) ) && in_array( $customerGroupId, $customerGroupIds ) )) {
					$stockId = (int)$warehouse->getStockId(  );
					break;
					continue;
				}
			}

			return $stockId;
		}

		/**
     * Get stock id by currency code
     * 
     * @param string $currencyCode
     * 
     * @return int
     */
		function getStockIdByCurrencyCode($currencyCode) {
			$stockId = null;
			foreach ($this->getWarehouses(  ) as $warehouse) {
				$currencyCodes = $warehouse->getCurrencies(  );

				if (( ( $currencyCodes && count( $currencyCodes ) ) && in_array( $currencyCode, $currencyCodes ) )) {
					$stockId = (int)$warehouse->getStockId(  );
					break;
					continue;
				}
			}

			return $stockId;
		}

		/**
     * Get stock шв by address
     * 
     * @param Varien_Object $address
     * 
     * @return int
     */
		function getStockIdByAddress($address) {
			$hash = $this->getAddressHelper(  )->getHash( $address );

			if (!isset( $this->_addressStockIds[$hash] )) {
				$resource = $this->getWarehouseResource(  );
				$adapter = $resource->getReadConnection(  );
				$bind = array( ':country_id' => $address->getCountryId(  ), ':region_id' => (int)$address->getRegionId(  ), ':postcode' => $address->getPostcode(  ) );
				$table = 'wa';
				$countryId = $table . '.country_id';
				$regionId = $table . '.region_id';
				$isZipRange = $table . '.is_zip_range';
				$zip = $table . '.zip';
				$fromZip = $table . '.from_zip';
				$toZip = $table . '.to_zip';
				$countryIdOrder = $countryId . ' DESC';
				$regionIdOrder = $regionId . ' DESC';
				$zipOrder = '(IF (' . $isZipRange . ' = \'0\', IF ((' . $zip . ' IS NULL) OR (' . $zip . ' = \'\'), 3, 1), 2)) ASC';
				$warehouseTable = $resource->getTable( 'warehouse/warehouse' );
				$warehouseAreaTable = $resource->getTable( 'warehouse/warehouse_area' );
				$select = $adapter->select(  )->from( array( 'w' => $warehouseTable ), array( 'stock_id' ) )->joinInner( array( 'wa' => $warehouseAreaTable ), 'w.warehouse_id = wa.warehouse_id' )->order( array( $countryIdOrder, $regionIdOrder, $zipOrder ) )->limit( 1 );
				$countryIdWhere = $countryId . ' = :country_id';
				$countryIdEmptyWhere = $countryId . ' = \'0\'';
				$regionIdWhere = $regionId . ' = :region_id';
				$regionIdEmptyWhere = $regionId . ' = \'0\'';
				$zipWhere = '(
                IF (
                    ' . $isZipRange . ' <> \'0\', 
                    (:postcode >= ' . $fromZip . ') AND (:postcode <= ' . $toZip . '), 
                    ' . $zip . ' = :postcode
                )
            )';
				$zipEmptyWhere = '((' . $isZipRange . ' = \'0\') AND ((' . $zip . ' IS NULL) OR (' . $zip . ' = \'\')))';
				$orWhere = '(' . implode( ') OR (', array( $countryIdWhere . ' AND ' . $regionIdWhere . ' AND ' . $zipWhere, $countryIdWhere . ' AND ' . $regionIdWhere . ' AND ' . $zipEmptyWhere, $countryIdWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipEmptyWhere, $countryIdWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipWhere, $countryIdEmptyWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipEmptyWhere ) ) . ')';
				$select->where( $orWhere );
				$stockId = (int)$adapter->fetchOne( $select, $bind );

				if (!$stockId) {
					$stockId = $this->getDefaultStockId(  );
				}

				$this->_addressStockIds[$hash] = $stockId;
			}

			return $this->_addressStockIds[$hash];
		}

		/**
     * Get nearest stock identifier by address
     * 
     * @param Varien_Object $address
     * 
     * @return int
     */
		function getNearestStockIdByAddress($address) {
			$hash = $this->getAddressHelper(  )->getHash( $address );

			if (!isset( $this->_nearestAddressStockIds[$hash] )) {
				$stockId = null;
				$coordinates = $this->getGeoCoderHelper(  )->getCoordinates( $address );

				if (( ( $coordinates && $coordinates->getLatitude(  ) ) && $coordinates->getLongitude(  ) )) {
					$mathHelper = $this->getMathHelper(  );
					$latitude1 = (double)$coordinates->getLatitude(  );
					$longitude1 = (double)$coordinates->getLongitude(  );
					$minStockId = null;
					$minDistance = null;
					foreach ($this->getWarehouses(  ) as $warehouse) {
						$_stockId = (int)$warehouse->getStockId(  );
						$latitude2 = (double)$warehouse->getOriginLatitude(  );
						$longitude2 = (double)$warehouse->getOriginLongitude(  );
						$distance = $mathHelper->getDistance( $latitude1, $longitude1, $latitude2, $longitude2 );

						if (( is_null( $minDistance ) || $distance < $minDistance )) {
							$minDistance = $distance;
							$minStockId = $_stockId;
							continue;
						}
					}


					if ($minStockId) {
						$stockId = $minStockId;
					}
				}


				if (!$stockId) {
					$stockId = $this->getDefaultStockId(  );
				}

				$this->_nearestAddressStockIds[$hash] = $stockId;
			}

			return $this->_nearestAddressStockIds[$hash];
		}

		/**
     * Get address stock priorities
     * 
     * @param Varien_Object $address
     * 
     * @return array
     */
		function getAddressStockPriorities($address) {
			$addressHash = $this->getAddressHelper(  )->getHash( $address );

			if (!isset( $this->_addressStockPriorities[$addressHash] )) {
				$stockPriorities = array(  );
				$coordinates = $this->getGeoCoderHelper(  )->getCoordinates( $address );

				if (( $coordinates->getLatitude(  ) && $coordinates->getLongitude(  ) )) {
					$mathHelper = $this->getMathHelper(  );
					$latitude1 = (double)$coordinates->getLatitude(  );
					$longitude1 = (double)$coordinates->getLongitude(  );
					foreach ($this->getWarehouses(  ) as $warehouse) {

						if (( $warehouse->getOriginLatitude(  ) && $warehouse->getOriginLongitude(  ) )) {
							$stockId = (int)$warehouse->getStockId(  );
							$latitude2 = (double)$warehouse->getOriginLatitude(  );
							$longitude2 = (double)$warehouse->getOriginLongitude(  );
							$distance = (int)$mathHelper->getDistance( $latitude1, $longitude1, $latitude2, $longitude2 );
							$stockPriorities[$stockId] = $distance;
							continue;
						}
					}
				} 
else {
					$stockPriorities = $this->getStockPriorities(  );
				}

				$this->_addressStockPriorities[$addressHash] = $stockPriorities;
			}

			return $this->_addressStockPriorities[$addressHash];
		}

		/**
     * Get address stock priority
     * 
     * @param Varien_Object $address
     * @param int $stockId
     * 
     * @return int
     */
		function getAddressStockPriority($address, $stockId) {
			$stockPriority = null;
			$stockPriorities = ($address ? $this->getAddressStockPriorities( $address ) : array(  ));

			if (isset( $stockPriorities[$stockId] )) {
				$stockPriority = (int)$stockPriorities[$stockId];
			}


			if (is_null( $stockPriority )) {
				$stockPriority = -2147483494;
			}

			return $stockPriority;
		}

		/**
     * Get address min priority stock identifier
     * 
     * @param Varien_Object $address
     * @param array $stockIds
     * 
     * @return int
     */
		function getAddressMinPriorityStockId($address, $stockIds) {
			$minStockId = null;
			$minStockPriority = null;
			foreach ($stockIds as $stockId) {
				$stockPriority = $this->getAddressStockPriority( $address, $stockId );

				if (( !is_null( $stockPriority ) && ( is_null( $minStockPriority ) || $stockPriority < $minStockPriority ) )) {
					$minStockPriority = $address;
					$minStockId = $stockPriority;
					continue;
				}
			}

			return $minStockId;
		}

		/**
     * Get address stock distances
     * 
     * @param Varien_Object $address
     * 
     * @return array
     */
		function getAddressStockDistances($address) {
			$hash = $this->getAddressHelper(  )->getHash( $address );

			if (!isset( $this->_addressStockDistances[$hash] )) {
				$stockDistances = array(  );
				$mathHelper = $this->getMathHelper(  );
				$distanceUnits = $mathHelper->getDistanceUnits(  );
				$coordinates = $this->getGeoCoderHelper(  )->getCoordinates( $address );

				if (( $coordinates->getLatitude(  ) && $coordinates->getLongitude(  ) )) {
					$latitude1 = (double)$coordinates->getLatitude(  );
					$longitude1 = (double)$coordinates->getLongitude(  );
					foreach ($this->getWarehouses(  ) as $warehouse) {
						$stockId = (int)$warehouse->getStockId(  );
						$latitude2 = (double)$warehouse->getOriginLatitude(  );
						$longitude2 = (double)$warehouse->getOriginLongitude(  );
						$stockDistance = array(  );
						foreach ($distanceUnits as $unitCode => $unit) {
							$stockDistance[$unitCode] = $mathHelper->getDistance( $latitude1, $longitude1, $latitude2, $longitude2, $unitCode );
						}

						$stockDistances[$stockId] = $stockDistance;
					}
				}

				$this->_addressStockDistances[$hash] = $stockDistances;
			}

			return $this->_addressStockDistances[$hash];
		}

		/**
     * Get address stock distance
     * 
     * @param Varien_Object $address
     * @param int $stockId
     * 
     * @return array
     */
		function getAddressStockDistance($address, $stockId) {
			$stockDistance = array(  );
			$stockDistances = $this->getAddressStockDistances( $address );

			if (isset( $stockDistances[$stockId] )) {
				$stockDistance = $stockDistances[$stockId];
			}

			return $stockDistance;
		}

		/**
     * Get address stock unit distance
     * 
     * @param Varien_Object $address
     * @param int $stockId
     * @param string $unitCode
     * 
     * @return float
     */
		function getAddressStockUnitDistance($address, $stockId, $unitCode) {
			$this->getAddressStockDistance( $address, $stockId );
			$stockDistance = $unitStockDistance = null;

			if (isset( $stockDistance[$unitCode] )) {
				$unitStockDistance = $stockDistance[$unitCode];
			}

			return $unitStockDistance;
		}

		/**
     * Get address stock unit distance string
     * 
     * @param Varien_Object $address
     * @param int $stockId
     * @param string $unitCode
     * 
     * @return string
     */
		function getAddressStockUnitDistanceString($address, $stockId, $unitCode) {
			$distance = $this->getAddressStockUnitDistance( $address, $stockId, $unitCode );

			if (!$distance) {
				return null;
			}

			$template = null;
			switch ($unitCode) {
				case 'mi': {
					$template = '%s miles away';
					break;
				}

				case 'nmi': {
					$template = '%s nautical miles away';
					break;
				}

				case 'km': {
					$template = '%s kilometers away';
					break;
				}
			}

			$template = '%s away';
			break;
			return sprintf( $this->__( $template ), round( $distance ) );
		}

		/**
     * Get address stock distance string
     * 
     * @param Varien_Object $address
     * @param int $stockId
     * 
     * @return string
     */
		function getAddressStockDistanceString($address, $stockId) {
			return $this->getAddressStockUnitDistanceString( $address, $stockId, $this->getConfig(  )->getDistanceUnit(  ) );
		}

		/**
     * Get customer address stock distance string
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getCustomerAddressStockDistanceString($stockId) {
			return $this->getAddressStockDistanceString( $this->getCustomerAddress(  ), $stockId );
		}

		/**
     * Save child data
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function saveChildData($warehouse, $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			$this->getModelHelper(  )->saveChildData( $warehouse, 'Innoexts_Warehouse_Model_Warehouse', 'warehouse_id', $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType );
			return $this;
		}

		/**
     * Add child data
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param array $array
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addChildData($warehouse, $array, $dataAttributeCode) {
			$this->getModelHelper(  )->addChildData( $warehouse, 'Innoexts_Warehouse_Model_Warehouse', $array, $dataAttributeCode );
			return $this;
		}

		/**
     * Load child data
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param string $dataTableName
     * @param string $dataAttributeCode
     * @param string $dataValueAttributeCode
     * @param string $dataValueType
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadChildData($warehouse, $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			$this->getModelHelper(  )->loadChildData( $warehouse, 'Innoexts_Warehouse_Model_Warehouse', 'warehouse_id', $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType );
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
		function loadCollectionChildData($collection, $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType = 'string') {
			$this->getModelHelper(  )->loadCollectionChildData( $collection, 'warehouse_id', $dataTableName, $dataAttributeCode, $dataValueAttributeCode, $dataValueType );
			return $this;
		}

		/**
     * Remove child data
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param string $dataAttributeCode
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeChildData($warehouse, $dataAttributeCode) {
			$this->getModelHelper(  )->removeChildData( $warehouse, 'Innoexts_Warehouse_Model_Warehouse', $dataAttributeCode );
			return $this;
		}

		/**
     * Save stores
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function saveStores($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedStoreSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->saveChildData( $warehouse, 'warehouse/warehouse_store', 'store_ids', 'store_id', 'int' );
		}

		/**
     * Add data stores
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param array $array
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataStores($warehouse, $array) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedStoreSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->addChildData( $warehouse, $array, 'store_ids' );
		}

		/**
     * Load stores
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadStores($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedStoreSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->loadChildData( $warehouse, 'warehouse/warehouse_store', 'store_ids', 'store_id', 'int' );
		}

		/**
     * Load collection stores
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCollectionStores($collection) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedStoreSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->loadCollectionChildData( $collection, 'warehouse/warehouse_store', 'store_ids', 'store_id', 'int' );
		}

		/**
     * Remove stores
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeStores($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedStoreSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->removeChildData( $warehouse, 'store_ids' );
		}

		/**
     * Save customer groups
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function saveCustomerGroups($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCustomerGroupSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->saveChildData( $warehouse, 'warehouse/warehouse_customer_group', 'customer_group_ids', 'customer_group_id', 'int' );
		}

		/**
     * Add data customer groups
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param array $array
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataCustomerGroups($warehouse, $array) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCustomerGroupSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->addChildData( $warehouse, $array, 'customer_group_ids' );
		}

		/**
     * Load customer groups
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCustomerGroups($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCustomerGroupSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->loadChildData( $warehouse, 'warehouse/warehouse_customer_group', 'customer_group_ids', 'customer_group_id', 'int' );
		}

		/**
     * Load collection customer groups
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCollectionCustomerGroups($collection) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCustomerGroupSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->loadCollectionChildData( $collection, 'warehouse/warehouse_customer_group', 'customer_group_ids', 'customer_group_id', 'int' );
		}

		/**
     * Remove customer groups
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeCustomerGroups($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCustomerGroupSingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->removeChildData( $warehouse, 'customer_group_ids' );
		}

		/**
     * Save currencies
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function saveCurrencies($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCurrencySingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->saveChildData( $warehouse, 'warehouse/warehouse_currency', 'currencies', 'currency', 'string' );
		}

		/**
     * Add data currencies
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param array $array
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataCurrencies($warehouse, $array) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCurrencySingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->addChildData( $warehouse, $array, 'currencies' );
		}

		/**
     * Load currencies
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCurrencies($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCurrencySingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->loadChildData( $warehouse, 'warehouse/warehouse_currency', 'currencies', 'currency', 'string' );
		}

		/**
     * Load collection currencies
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCollectionCurrencies($collection) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCurrencySingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->loadCollectionChildData( $collection, 'warehouse/warehouse_currency', 'currencies', 'currency', 'string' );
		}

		/**
     * Remove currencies
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeCurrencies($warehouse) {
			$config = $this->getConfig(  );

			if (( $config->isMultipleMode(  ) || !$config->isAssignedCurrencySingleAssignmentMethod(  ) )) {
				return $this;
			}

			return $this->removeChildData( $warehouse, 'currencies' );
		}

		/**
     * Save shipping carriers
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function saveShippingCarriers($warehouse) {
			$config = $this->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			return $this->saveChildData( $warehouse, 'warehouse/warehouse_shipping_carrier', 'shipping_carriers', 'shipping_carrier', 'string' );
		}

		/**
     * Add data shipping carriers
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param array $array
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function addDataShippingCarriers($warehouse, $array) {
			$config = $this->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			return $this->addChildData( $warehouse, $array, 'shipping_carriers' );
		}

		/**
     * Load shipping carriers
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadShippingCarriers($warehouse) {
			$config = $this->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			$this->loadChildData( $warehouse, 'warehouse/warehouse_shipping_carrier', 'shipping_carriers', 'shipping_carrier', 'string' );
			return $this;
		}

		/**
     * Load collection shipping carriers
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function loadCollectionShippingCarriers($collection) {
			$config = $this->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			return $this->loadCollectionChildData( $collection, 'warehouse/warehouse_shipping_carrier', 'shipping_carriers', 'shipping_carrier', 'string' );
		}

		/**
     * Remove shipping carriers
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return Innoexts_Core_Helper_Model
     */
		function removeShippingCarriers($warehouse) {
			$config = $this->getConfig(  );

			if (!$config->isShippingCarrierFilterEnabled(  )) {
				return $this;
			}

			return $this->removeChildData( $warehouse, 'shipping_carriers' );
		}
	}

?>