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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Priority extends Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract {
		/**
     * Apply quote stock items
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Priority
     */
		function applyQuoteStockItems($quote = null) {
			if (is_null( $quote )) {
				$quote = $this->getQuote(  );
			}


			if (!$quote) {
				return $this;
			}

			$quoteHelper = $this->getWarehouseHelper(  )->getQuoteHelper(  );
			$stockData = $quoteHelper->getStockData( $quote );

			if (!is_null( $stockData )) {
				if (count( $stockData )) {
					$productHelper = $this->getWarehouseHelper(  )->getProductHelper(  );
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

								$minStockId = $productHelper->getMinPriorityStockId( $itemStockData->getProduct(  ), $stockIds );
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
			$stockIds = $productHelper->getQuoteInStockStockIds( $product );
			$stockId = $productHelper->getMinPriorityStockId( $product, $stockIds );
			return ($stockId ? $stockId : $helper->getDefaultStockId(  ));
		}
	}

?>