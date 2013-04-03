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

	class Innoexts_WarehousePlus_Model_Catalog_Product_Attribute_Backend_Tierprice extends Mage_Catalog_Model_Product_Attribute_Backend_Tierprice {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get product helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product
     */
		function getProductHelper() {
			return $this->getWarehouseHelper(  )->getProductHelper(  );
		}

		/**
     * Get product price helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price
     */
		function getProductPriceHelper() {
			return $this->getWarehouseHelper(  )->getProductPriceHelper(  );
		}

		/**
     * Redefine attribute scope
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * 
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Price
     */
		function setScope($attribute) {
			$this->getProductPriceHelper(  )->setAttributeScope( $attribute );
			return $this;
		}

		/**
     * Validate data
     * 
     * @param array $data
     * @param int $storeId
     * @param bool $filterEmpty
     * @param bool $filterInactive
     * @param bool $filterAncestors
     * 
     * @return bool
     */
		function validateData($data, $storeId, $filterEmpty = true, $filterInactive = true, $filterAncestors = true) {
			$priceHelper = $this->getProductPriceHelper(  );

			if ($filterEmpty) {
				if (( ( empty( $data['price_qty'] ) || !isset( $data['cust_group'] ) ) || !empty( $data['delete'] ) )) {
					return false;
				}
			}


			if ($filterInactive) {
				if ($priceHelper->isInactiveData( $data, $storeId )) {
					return false;
				}
			}


			if ($filterAncestors) {
				if ($priceHelper->isAncestorData( $data, $storeId )) {
					return false;
				}
			}

			return true;
		}

		/**
     * Get data key
     * 
     * @param array $data
     * @param bool $allWebsites
     * 
     * @return string 
     */
		function getDataKey($data, $allWebsites = false) {
			return join( '-', array( ($allWebsites ? 0 : $data['website_id']), $data['store_id'], ($data['stock_id'] ? $data['stock_id'] : 0), $data['cust_group'], $data['price_qty'] * 1, (( isset( $data['currency'] ) && $data['currency'] ) ? $data['currency'] : null) ) );
		}

		/**
     * Get short data key
     * 
     * @param array $data
     * 
     * @return string 
     */
		function getShortDataKey($data) {
			return join( '-', array( ($data['stock_id'] ? $data['stock_id'] : 0), $data['cust_group'], $data['price_qty'] * 1, (( isset( $data['currency'] ) && $data['currency'] ) ? $data['currency'] : null) ) );
		}

		/**
     * Validate tier price data
     * 
     * @param Mage_Catalog_Model_Product $object
     * @throws Mage_Core_Exception
     * 
     * @return bool
     */
		function validate($object) {
			$this->getWarehouseHelper(  );
			$this->getProductHelper(  );
			$this->getProductPriceHelper(  );
			$this->getAttribute(  );
			$attribute->getName(  );
			$tiers = $attributeName = $attribute = $priceHelper = $productHelper = $helper = $object->getData( $attributeName );

			if (empty( $$tiers )) {
				return true;
			}


			if ($productHelper->isGroupPriceFixed( $object )) {
				$duplicateMessage = $helper->__( 'Duplicate website tier price store, warehouse, customer group, quantity and currency.' );
			} 
else {
				$duplicateMessage = $helper->__( 'Duplicate website tier price store, warehouse, customer group and quantity.' );
			}

			$duplicates = array(  );
			foreach ($tiers as $tier) {

				if (!empty( $tier['delete'] )) {
					continue;
				}

				$compare = $this->getDataKey( $tier );

				if (isset( $duplicates[$compare] )) {
					Mage::throwException( $duplicateMessage );
				}

				$duplicates[$compare] = true;
			}


			if (( ( $priceHelper->isStoreScope(  ) || $priceHelper->isWebsiteScope(  ) ) && $object->getStoreId(  ) )) {
				$object->getStoreId(  );
				$origTierPrices = $storeId = $object->getOrigData( $attributeName );
				foreach ($origTierPrices as $tier) {

					if ($priceHelper->isAncestorData( $tier, $storeId )) {
						$compare = $this->getDataKey( $tier );
						$duplicates[$compare] = true;
						continue;
					}
				}
			}

			$helper->getCurrencyHelper(  )->getBaseCode(  );
			$rates = $baseCurrency = $this->_getWebsiteRates(  );
			foreach ($tiers as $tier) {

				if (!empty( $tier['delete'] )) {
					continue;
				}


				if ($tier['website_id'] == 0) {
					continue;
				}

				$rates[$tier['website_id']]['code'];
				$this->getDataKey( $tier );
				$globalCompare = $compare = $websiteCurrency = $this->getDataKey( $tier, true );

				if (( $baseCurrency == $websiteCurrency && isset( $duplicates[$globalCompare] ) )) {
					Mage::throwException( $duplicateMessage );
					continue;
				}
			}

			return true;
		}

		/**
     * Sort price data
     *
     * @param array $a
     * @param array $b
     * 
     * @return int
     */
		function _sortPriceData($a, $b) {
			if ($a['website_id'] != $b['website_id']) {
				return ($a['website_id'] < $b['website_id'] ? 1 : -1);
			}


			if ($a['store_id'] != $b['store_id']) {
				return ($a['store_id'] < $b['store_id'] ? 1 : -1);
			}


			if ($a['stock_id'] != $b['stock_id']) {
				return ($a['stock_id'] < $b['stock_id'] ? 1 : -1);
			}


			if (( isset( $a['currency'] ) && isset( $b['currency'] ) )) {
				if ($a['currency'] != $b['currency']) {
					return ($a['currency'] < $b['currency'] ? 1 : -1);
				}
			}

			return 0;
		}

		/**
     * Sort price data by quantity
     *
     * @param array $a
     * @param array $b
     * 
     * @return int
     */
		function _sortPriceDataByQty($a, $b) {
			if ($a['price_qty'] != $b['price_qty']) {
				return ($a['price_qty'] < $b['price_qty'] ? -1 : 1);
			}

			return 0;
		}

		/**
     * Prepare tier prices data for website
     *
     * @param array $priceData
     * @param string $productTypeId
     * @param int $websiteId
     * @param int $storeId
     * @param string $currency
     * @param int $stockId
     * 
     * @return array
     */
		function preparePriceData2($priceData, $productTypeId, $websiteId, $storeId, $currency, $stockId) {
			$helper = $this->getWarehouseHelper(  );
			$priceHelper = $this->getProductPriceHelper(  );
			$currencyHelper = $helper->getCurrencyHelper(  );
			$isGroupPriceFixed = $priceHelper->isGroupPriceFixed( $productTypeId );
			$website = $helper->getWebsiteById( $websiteId );
			$data = array(  );
			usort( $priceData, array( $this, '_sortPriceData' ) );
			foreach ($priceData as $v) {
				$v['currency'] = (( isset( $v['currency'] ) && $v['currency'] ) ? $v['currency'] : null);

				if (empty( $v['currency'] )) {
					$isEmptyCurrency = true;

					if ($v['website_id'] == 0) {
						$v['currency'] = $currencyHelper->getBaseCode(  );
					} 
else {
						$v['currency'] = $helper->getWebsiteById( $v['website_id'] )->getBaseCurrencyCode(  );
					}
				} 
else {
					$isEmptyCurrency = false;
				}

				$key = $this->getShortDataKey( $v );

				if (( ( ( !isset( $data[$key] ) && ( ( ( $v['website_id'] == $websiteId && $v['store_id'] == $storeId ) || ( $v['website_id'] == $websiteId && $v['store_id'] == 0 ) ) || $v['website_id'] == 0 ) ) && ( $v['currency'] == $currency || $isEmptyCurrency ) ) && ( ( $stockId && ( $v['stock_id'] == $stockId || !$v['stock_id'] ) ) || ( !$stockId && !$v['stock_id'] ) ) )) {
					$data[$key] = $v;
					$data[$key]['website_id'] = $websiteId;
					$data[$key]['store_id'] = $storeId;
					$data[$key]['currency'] = $currency;

					if ($stockId) {
						$data[$key]['stock_id'] = $stockId;
					}


					if ($isGroupPriceFixed) {
						$websiteCurrency = $website->getBaseCurrencyCode(  );

						if (( $websiteCurrency != $v['currency'] || $isEmptyCurrency )) {
							$rate = $currencyHelper->getRate( $websiteCurrency, $v['currency'] );
							$data[$key]['price'] = $v['price'] / $rate;
							$data[$key]['website_price'] = $v['price'] / $rate;
							continue;
						}

						continue;
					}

					continue;
				}
			}

			usort( $data, array( $this, '_sortPriceDataByQty' ) );
			return $data;
		}

		/**
     * After load
     * 
     * @param Mage_Catalog_Model_Product $object
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Attribute_Backend_Tierprice
     */
		function afterLoad($object) {
			$helper = $this->getWarehouseHelper(  );
			$productHelper = $helper->getProductHelper(  );
			$priceHelper = $helper->getProductPriceHelper(  );
			$resource = $this->_getResource(  );
			$websiteId = null;
			$storeId = null;
			$store = $helper->getStoreById( $object->getStoreId(  ) );
			$attribute = $this->getAttribute(  );
			$attributeName = $attribute->getName(  );
			$isEditMode = $object->getData( '_edit_mode' );

			if ($priceHelper->isGlobalScope(  )) {
				$websiteId = null;
				$storeId = null;
			} 
else {
				if (( $priceHelper->isWebsiteScope(  ) && $store->getId(  ) )) {
					$websiteId = $helper->getWebsiteIdByStoreId( $store->getId(  ) );
					$storeId = null;
				} 
else {
					if (( $priceHelper->isStoreScope(  ) && $store->getId(  ) )) {
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
				$stockId = $productHelper->getCurrentStockId( $object );
			}

			$data = $resource->loadPriceData2( $object->getId(  ), $websiteId, $storeId, $currency, $stockId );
			foreach ($data as $k => $v) {
				$data[$k]['website_price'] = $v['price'];

				if ($v['all_groups']) {
					$data[$k]['cust_group'] = CUST_GROUP_ALL;
					continue;
				}
			}

			$object->setTierPrices( $data );
			$priceHelper->setTierPrice( $object );
			$object->setData( $attributeName, $data );
			$object->setOrigData( $attributeName, $data );
			$valueChangedKey = $attributeName . '_changed';
			$object->setOrigData( $valueChangedKey, 0 );
			$object->setData( $valueChangedKey, 0 );
			return $this;
		}

		/**
     * After save
     *
     * @param Mage_Catalog_Model_Product $object
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Attribute_Backend_Tierprice
     */
		function afterSave($object) {
			$helper = $this->getWarehouseHelper(  );
			$priceHelper = $helper->getProductPriceHelper(  );
			$resource = $this->_getResource(  );
			$objectId = $object->getId(  );
			$storeId = $object->getStoreId(  );
			$websiteId = $helper->getWebsiteIdByStoreId( $storeId );
			$attribute = $this->getAttribute(  );
			$attributeName = $attribute->getName(  );
			$tierPrices = $object->getData( $attributeName );

			if (empty( $$tierPrices )) {
				if (( $priceHelper->isGlobalScope(  ) || $websiteId == 0 )) {
					$resource->deletePriceData2( $objectId );
				} 
else {
					if ($priceHelper->isWebsiteScope(  )) {
						$resource->deletePriceData2( $objectId, $websiteId );
					} 
else {
						if ($priceHelper->isStoreScope(  )) {
							$resource->deletePriceData2( $objectId, $websiteId, $storeId );
						}
					}
				}

				return $this;
			}

			$old = array(  );
			$new = array(  );
			$origTierPrices = $object->getOrigData( $attributeName );

			if (!is_array( $origTierPrices )) {
				$origTierPrices = array(  );
			}

			foreach ($origTierPrices as $data) {

				if (!$this->validateData( $data, $storeId, false, false, true )) {
					continue;
				}

				$key = $this->getDataKey( $data );
				$old[$key] = $data;
			}

			foreach ($tierPrices as $data) {

				if (!$this->validateData( $data, $storeId, true, true, true )) {
					continue;
				}

				$key = $this->getDataKey( $data );
				$useForAllGroups = $data['cust_group'] == CUST_GROUP_ALL;
				$customerGroupId = (!$useForAllGroups ? $data['cust_group'] : 0);
				$new[$key] = array( 'website_id' => $data['website_id'], 'store_id' => $data['store_id'], 'stock_id' => ($data['stock_id'] ? $data['stock_id'] : null), 'all_groups' => ($useForAllGroups ? 1 : 0), 'customer_group_id' => $customerGroupId, 'qty' => $data['price_qty'], 'value' => $data['price'], 'currency' => (( isset( $data['currency'] ) && $data['currency'] ) ? $data['currency'] : null) );
			}

			$delete = array_diff_key( $old, $new );
			$insert = array_diff_key( $new, $old );
			$update = array_intersect_key( $new, $old );
			$isChanged = false;
			$productId = $key;

			if (!empty( $$delete )) {
				foreach ($delete as $data) {
					$resource->deletePriceData2( $productId, null, null, $data['price_id'] );
					$isChanged = true;
				}
			}


			if (!empty( $$insert )) {
				foreach ($insert as $data) {
					$price = new Varien_Object( $data );
					$price->setEntityId( $productId );
					$resource->savePriceData( $price );
					$isChanged = true;
				}
			}


			if (!empty( $$update )) {
				foreach ($update as $k => $v) {

					if ($old[$k]['price'] != $v['value']) {
						$price = new Varien_Object( array( 'value_id' => $old[$k]['price_id'], 'value' => $v['value'] ) );
						$resource->savePriceData( $price );
						$isChanged = true;
						continue;
					}
				}
			}


			if ($isChanged) {
				$valueChangedKey = $attributeName . '_changed';
				$object->setData( $valueChangedKey, 1 );
			}

			return $this;
		}
	}

?>