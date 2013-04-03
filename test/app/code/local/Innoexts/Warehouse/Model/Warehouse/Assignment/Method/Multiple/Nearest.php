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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Nearest extends Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract {
		/**
     * Apply quote stock items
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Nearest
     */
		function applyQuoteStockItems($quote = null) {
			if (is_null( $quote )) {
				$quote = $this->getQuote(  );
			}


			if (!$quote) {
				return $this;
			}

			$helper = $this->getWarehouseHelper(  );
			$quoteHelper = $helper->getQuoteHelper(  );
			$stockData = $quoteHelper->getStockData( $quote );
			$customerAddress = $this->getCustomerAddress(  );

			if (!is_null( $stockData )) {
				if (count( $stockData )) {
					$combination = array(  );
					foreach ($stockData as $itemKey => $itemStockData) {
						$minStockId = null;

						if ($itemStockData->getIsInStock(  )) {
							if (!$itemStockData->getSessionStockId(  )) {
								$stockIds = array(  );
								foreach ($itemStockData->getStockItems(  ) as $stockItem) {
									$stockId = (int)$stockItem->getStockId(  );

									if ($stockId) {
										array_push( $stockIds, $stockId );
										continue;
									}
								}

								$minStockId = $helper->getAddressMinPriorityStockId( $customerAddress, $stockIds );
							} 
else {
								$minStockId = $itemStockData->getSessionStockId(  );
							}
						}


						if (is_null( $minStockId )) {
							$combination = null;
							break;
						}

						$combination[$itemKey] = $minStockId;
					}


					if (!is_null( $combination )) {
						$quote->applyStockItemsCombination( $stockData, $combination );
					}
				}
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
			$helper = $this->getWarehouseHelper(  );
			$productHelper = $helper->getProductHelper(  );
			$customerAddress = $this->getCustomerAddress(  );
			$stockIds = $productHelper->getQuoteInStockStockIds( $product );
			$stockId = $helper->getAddressMinPriorityStockId( $customerAddress, $stockIds );
			return ($stockId ? $stockId : $helper->getDefaultStockId(  ));
		}
	}

?>