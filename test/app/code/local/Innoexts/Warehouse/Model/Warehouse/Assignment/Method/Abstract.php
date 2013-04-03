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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Abstract extends Varien_Object {
		private $_quote = null;

		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get quote
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote
     */
		function getQuote() {
			return $this->_quote;
		}

		/**
     * Set quote
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract
     */
		function setQuote($quote) {
			$this->_quote = $quote;
			return $this;
		}

		/**
     * Check if assignment method is active
     * 
     * @return bool
     */
		function isActive() {
			$flag = $this->getData( 'active' );

			if (( !empty( $$flag ) && $flag !== 'false' )) {
				return true;
			}

			return false;
		}

		/**
     * Get title
     * 
     * @return string
     */
		function getTitle() {
			return $this->getWarehouseHelper(  )->__( $this->getData( 'title' ) );
		}

		/**
     * Get description
     * 
     * @return string
     */
		function getDescription() {
			return $this->getWarehouseHelper(  )->__( $this->getData( 'description' ) );
		}

		/**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
		function getStore() {
			$store = null;
			$quote = $this->getQuote(  );

			if ($quote) {
				$store = $quote->getStore(  );
			}


			if (!$store) {
				$store = Mage::app(  )->getStore(  );
			}

			return $store;
		}

		/**
     * Get store identifier
     * 
     * @return int
     */
		function getStoreId() {
			return $this->getStore(  )->getId(  );
		}

		/**
     * Get customer group id
     * 
     * @return int
     */
		function getCustomerGroupId() {
			$customerGroupId = null;
			$quote = $this->getQuote(  );

			if ($quote) {
				$customerGroupId = $quote->getCustomerGroupId(  );
			}


			if (!$customerGroupId) {
				$customerGroupId = $this->getWarehouseHelper(  )->getCustomerHelper(  )->getCustomerGroupId(  );
			}

			return $customerGroupId;
		}

		/**
     * Get currency code
     * 
     * @return string
     */
		function getCurrencyCode() {
			$currencyCode = null;
			$quote = $this->getQuote(  );

			if ($quote) {
				$currencyCode = $quote->getQuoteCurrencyCode(  );
			}


			if (!$currencyCode) {
				$currencyCode = $this->getWarehouseHelper(  )->getCurrencyHelper(  )->getCurrentCode(  );
			}

			return $currencyCode;
		}

		/**
     * Get customer address
     * 
     * @return Varien_Object
     */
		function getCustomerAddress() {
			$helper = $this->getWarehouseHelper(  );
			$address = null;
			$addressHelper = $helper->getAddressHelper(  );
			$quote = $this->getQuote(  );

			if ($quote) {
				$shippingAddress = $quote->getShippingAddress(  );

				if (( $shippingAddress && !$addressHelper->isEmpty( $shippingAddress ) )) {
					$address = $addressHelper->cast( $shippingAddress );
				}
			}


			if (( !$address || $addressHelper->isEmpty( $address ) )) {
				$customerLocatorHelper = $helper->getCustomerLocatorHelper(  );
				$customerAddress = $customerLocatorHelper->getCustomerAddress(  );
				$address = $addressHelper->cast( $customerAddress );
			}

			return $address;
		}

		/**
     * Apply quote stock items
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Abstract
     */
		function applyQuoteStockItems() {
			return $this;
		}

		/**
     * Get stock identifier
     * 
     * @return int|null
     */
		function getStockId() {
		}

		/**
     * Get product stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int|null
     */
		function getProductStockId($product) {
		}
	}

?>