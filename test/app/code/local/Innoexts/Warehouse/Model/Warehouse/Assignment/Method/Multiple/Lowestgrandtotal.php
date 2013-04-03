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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Lowestgrandtotal extends Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract {
		/**
     * Get quote value
     * 
     * @return float
     */
		function getValueGetter() {
			return 'getGrandTotal';
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
			$stockId = $productHelper->getQuoteMinGrandTotalStockId( $product, $stockIds );
			return ($stockId ? $stockId : $helper->getDefaultStockId(  ));
		}
	}

?>