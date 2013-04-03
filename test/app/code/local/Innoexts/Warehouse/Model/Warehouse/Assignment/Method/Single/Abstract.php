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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Abstract extends Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Abstract {
		private $_stockId = null;

		/**
     * Set quote
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract
     */
		function setQuote($quote) {
			if (( is_null( $this->_quote ) && is_null( $quote ) )) {
				return $this;
			}

			$this->_quote = $quote;
			$this->_stockId = null;
			return $this;
		}

		/**
     * Apply quote stock items
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Abstract
     */
		function applyQuoteStockItems() {
			$quote = $this->getQuote(  );

			if (!$quote) {
				return $this;
			}

			$stockId = $this->getStockId(  );

			if ($stockId) {
				foreach ($quote->getAllItems(  ) as $item) {
					$item->setStockId( $stockId );
				}
			}

			return $this;
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function _getStockId() {
			return $this->getWarehouseHelper(  )->getDefaultStockId(  );
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function getStockId() {
			if (is_null( $this->_stockId )) {
				$stockId = null;
				$helper = $this->getWarehouseHelper(  );
				$config = $helper->getConfig(  );

				if ($config->isAllowAdjustment(  )) {
					$stockId = $helper->getSessionStockId(  );
				}


				if (!$stockId) {
					$quote = $this->getQuote(  );

					if ($quote) {
						$stockId = $quote->getStockId(  );
					}


					if (!$stockId) {
						$stockId = $this->_getStockId(  );
					}
				}

				$this->_stockId = $stockId;
			}

			return $this->_stockId;
		}
	}

?>