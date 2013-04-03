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

	class Innoexts_Warehouse_Block_Catalog_Product_View_Quote extends Mage_Core_Block_Template {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Construct
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'warehouse/catalog/product/view/quote.phtml' );
		}

		/**
     * Get product
     * 
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			$key = 'product';

			if (!$this->hasData( $key )) {
				$this->setData( $key, Mage::registry( 'product' ) );
			}

			return $this->getData( $key );
		}

		/**
     * Get product name
     * 
     * @return string
     */
		function getProductName() {
			return $this->getProduct(  )->getName(  );
		}

		/**
     * Convert price
     * 
     * @param float $price
     * 
     * @return float
     */
		function convertPrice($price) {
			return $this->getProduct(  )->getStore(  )->convertPrice( $price, true, false );
		}

		/**
     * Format price
     * 
     * @param float $price
     * 
     * @return float
     */
		function formatPrice($price) {
			return $this->getProduct(  )->getStore(  )->formatPrice( $price, false );
		}

		/**
     * Check if product is virtual
     * 
     * @return boolean
     */
		function isVirtual() {
			$productHelper = $this->getWarehouseHelper(  )->getProductHelper(  );
			$product = $this->getProduct(  );
			return (( $productHelper->isVirtual( $product ) || $productHelper->isDownloadable( $product ) ) ? true : false);
		}

		/**
     * Get buy request
     * 
     * @return Varien_Object
     */
		function getBuyRequest() {
			$key = 'buy_request';

			if (!$this->hasData( $key )) {
				$buyRequest = $this->getWarehouseHelper(  )->getProductHelper(  )->getBuyRequest( $this->getProduct(  ) );
				$this->setData( $key, $buyRequest );
			}

			return $this->getData( $key );
		}

		/**
     * Get stock ids
     * 
     * @return array
     */
		function getStockIds() {
			$key = 'stock_ids';

			if (!$this->hasData( $key )) {
				$stockIds = array(  );
				$helper = $this->getWarehouseHelper(  );
				$config = $helper->getConfig(  );

				if (!$config->isCatalogOutOfStockVisible(  )) {
					$stockIds = $helper->getProductHelper(  )->getQuoteInStockStockIds( $this->getProduct(  ), $this->getBuyRequest(  ) );
				} 
else {
					$stockIds = $helper->getStockIds(  );
				}

				$this->setData( $key, $stockIds );
			}

			return $this->getData( $key );
		}

		/**
     * Get current stock identifier
     * 
     * @return int
     */
		function getCurrentStockId() {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getCurrentStockId( $this->getProduct(  ) );
		}

		/**
     * Check if adjustment is allowed
     * 
     * @return bool
     */
		function isAllowAdjustment() {
			$key = 'is_allow_adjustment';

			if (!$this->hasData( $key )) {
				$this->getWarehouseHelper(  );
				$helper->getConfig(  );
				$stockIds = $config = $helper = $this->getStockIds(  );
				$isAllowAdjustment = (( ( $config->isAllowAdjustment(  ) && $config->isMultipleMode(  ) ) && 1 < count( $stockIds ) ) ? true : false);
				$this->setData( $key, $isAllowAdjustment );
			}

			return $this->getData( $key );
		}

		/**
     * Get default quote configuration options
     * 
     * @return array
     */
		function getDefaultQuoteConfigurationOptions() {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getDefaultQuoteConfigurationOptions( $this->getProduct(  ), $this->getBuyRequest(  ) );
		}

		/**
     * Get formated configuration option value
     * 
     * @param string $optionValue
     * 
     * @return string
     */
		function getFormatedConfigurationOptionValue($optionValue) {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getFormatedConfigurationOptionValue( $optionValue );
		}

		/**
     * Get quote
     * 
     * @param int $stockId
     * 
     * @return Mage_Sales_Model_Quote
     */
		function getQuote($stockId) {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getQuote( $this->getProduct(  ), $stockId, $this->getBuyRequest(  ) );
		}

		/**
     * Get quote shipping rates
     * 
     * @param int $stockId
     * 
     * @return Mage_Sales_Model_Quote
     */
		function getQuoteShippingRates($stockId) {
			return $this->getWarehouseHelper(  )->getQuoteHelper(  )->getGroupedShippingRates( $this->getQuote( $stockId ) );
		}

		/**
     * Get quote is in stock
     * 
     * @param int $stockId
     * 
     * @return boolean
     */
		function getQuoteIsInStock($stockId) {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getQuoteIsInStock( $this->getProduct(  ), $stockId, $this->getBuyRequest(  ) );
		}

		/**
     * Get quote max qty
     * 
     * @param int $stockId
     * 
     * @return float|null
     */
		function getQuoteMaxQty($stockId) {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getQuoteMaxQty( $this->getProduct(  ), $stockId, $this->getBuyRequest(  ) );
		}

		/**
     * Get quote subtotal
     * 
     * @param int $stockId
     * 
     * @return float|null
     */
		function getQuoteSubtotal($stockId) {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getQuoteSubtotal( $this->getProduct(  ), $stockId, $this->getBuyRequest(  ) );
		}

		/**
     * Get quote tax amount
     * 
     * @param int $stockId
     * 
     * @return float|null
     */
		function getQuoteTaxAmount($stockId) {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->getQuoteTaxAmount( $this->getProduct(  ), $stockId, $this->getBuyRequest(  ) );
		}

		/**
     * Get customer address stock distance string
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getCustomerAddressStockDistanceString($stockId) {
			return $this->getWarehouseHelper(  )->getCustomerAddressStockDistanceString( $stockId );
		}
	}

?>