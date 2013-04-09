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

	class Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Price_Tier_Renderer extends Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Price_Tier_Renderer {
		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'warehouseplus/catalog/product/edit/tab/price/tier/renderer.phtml' );
		}

		/**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
		function getStore() {
			if (is_null( $this->_store )) {
				$storeId = (int)$this->getRequest(  )->getParam( 'store', 0 );
				$this->_store = Mage::app(  )->getStore( $storeId );
			}

			return $this->_store;
		}

		/**
     * Check tier price attribute scope is global
     *
     * @return bool
     */
		function isStoreScope() {
			return $this->getWarehouseHelper(  )->getProductPriceHelper(  )->isStoreScope(  );
		}

		/**
     * Check if store column is visible
     *
     * @return bool
     */
		function isShowStoreColumn() {
			$helper = $this->getWarehouseHelper(  );

			if (( !$helper->isSingleStoreMode(  ) && $this->isStoreScope(  ) )) {
				return true;
			}

			return false;
		}

		/**
     * Check if allow to change store
     *
     * @return bool
     */
		function isAllowChangeStore() {
			if (( !$this->isShowStoreColumn(  ) || $this->getProduct(  )->getStoreId(  ) )) {
				return false;
			}

			return true;
		}

		/**
     * Get default currency code
     * 
     * @return string
     */
		function getDefaultCurrencyCode() {
			return $this->getStore(  )->getBaseCurrencyCode(  );
		}

		/**
     * Get default value for store
     *
     * @return int
     */
		function getDefaultStore() {
			if (( $this->isShowStoreColumn(  ) && !$this->isAllowChangeStore(  ) )) {
				return $this->getProduct(  )->getStoreId(  );
			}

			return 0;
		}

		/**
     * Get currency codes
     * 
     * @return array
     */
		function getCurrencyCodes() {
			return $this->getWarehouseHelper(  )->getCurrencyHelper(  )->getCodes(  );
		}

		/**
     * Check if group price is fixed
     * 
     * @return bool
     */
		function isGroupPriceFixed() {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->isGroupPriceFixed( $this->getProduct(  ) );
		}

		/**
     * Get values
     * 
     * @return array
     */
		function getValues() {
			$helper = $this->getWarehouseHelper(  );
			$productHelper = $helper->getProductHelper(  );
			$priceHelper = $helper->getProductPriceHelper(  );
			$element = $this->getElement(  );
			$product = $this->getProduct(  );
			$storeId = $productHelper->getStoreId( $product );
			$data = $element->getValue(  );
			$values = array(  );

			if (is_array( $data )) {
				usort( $data, array( $this, '_sortTierPrices' ) );
				$values = $this;
			}

			$_values = array(  );
			foreach ($values as $k => $v) {

				if (!$priceHelper->isInactiveData( $v, $storeId )) {
					$_values[$k] = $v;
					continue;
				}
			}

			$values = $v;
			foreach ($values as $v) {
// 				$v = &;

				$v['readonly'] = ($priceHelper->isAncestorData( $v, $storeId ) ? true : false);
			}

			return $values;
		}

		/**
     * Sort tier price values callback method
     *
     * @param array $a
     * @param array $b
     * 
     * @return int
     */
		function _sortTierPrices($a, $b) {
			if ($a['website_id'] != $b['website_id']) {
				return ($a['website_id'] < $b['website_id'] ? -1 : 1);
			}


			if ($a['store_id'] != $b['store_id']) {
				return ($a['store_id'] < $b['store_id'] ? -1 : 1);
			}


			if ($a['stock_id'] != $b['stock_id']) {
				return ($a['stock_id'] < $b['stock_id'] ? -1 : 1);
			}


			if ($a['cust_group'] != $b['cust_group']) {
				return ($this->getCustomerGroups( $a['cust_group'] ) < $this->getCustomerGroups( $b['cust_group'] ) ? -1 : 1);
			}


			if ($a['price_qty'] != $b['price_qty']) {
				return ($a['price_qty'] < $b['price_qty'] ? -1 : 1);
			}


			if ($this->isGroupPriceFixed(  )) {
				if ($a['currency'] != $b['currency']) {
					return ($a['currency'] < $b['currency'] ? -1 : 1);
				}
			}

			return 0;
		}
	}

?>