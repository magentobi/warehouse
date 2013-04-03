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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract extends Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Abstract {
		private $_productStockIds = null;

		/**
     * Get quote value
     * 
     * @return float
     */
		function getValueGetter() {
			return 'getGrandTotal';
		}

		/**
     * Apply quote stock items
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract
     */
		function applyQuoteStockItems() {
			$quote = $this->getQuote(  );

			if (!$quote) {
				return $this;
			}

			$quoteHelper = $this->getWarehouseHelper(  )->getQuoteHelper(  );
			$stockData = $quoteHelper->getStockData( $quote );

			if (!is_null( $stockData )) {
				$quoteHelper->applyStockItems( $quote, $stockData, $this->getValueGetter(  ) );
			}

			return $this;
		}

		/**
     * Get product stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function _getProductStockId($product) {
			return $this->getWarehouseHelper(  )->getDefaultStockId(  );
		}

		/**
     * Get product stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getProductStockId($product) {
			$productId = (int)$product->getId(  );

			if (!isset( $this->_productStockIds[$productId] )) {
				$helper = $this->getWarehouseHelper(  );
				$config = $helper->getConfig(  );
				$stockId = null;

				if ($config->isAllowAdjustment(  )) {
					$productHelper = $helper->getProductHelper(  );
					$sessionStockId = $productHelper->getSessionStockId( $product );

					if ($sessionStockId) {
						$stockId = $productId;
					}
				}


				if (!$stockId) {
					$stockId = $this->_getProductStockId( $product );
				}

				$this->_productStockIds[$productId] = $stockId;
			}

			return $this->_productStockIds[$productId];
		}
	}

?>