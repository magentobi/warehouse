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

	class Innoexts_WarehousePlus_Helper_Catalog_Product extends Innoexts_Warehouse_Helper_Catalog_Product {
		/**
     * Get store id by store id
     * 
     * @param int $storeId
     * 
     * @return int 
     */
		function getStoreIdByStoreId($storeId) {
			$_storeId = null;
			$helper = $this->getWarehouseHelper(  );
			$priceHelper = $this->getPriceHelper(  );

			if ($priceHelper->isStoreScope(  )) {
				$_storeId = $helper;
			} 
else {
				if ($priceHelper->isWebsiteScope(  )) {
					$_storeId = $helper->getDefaultStoreIdByStoreId( $storeId );
				} 
else {
					$_storeId = 117;
				}
			}

			return $_storeId;
		}

		/**
     * Get store id
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getStoreId($product) {
			return $this->getStoreIdByStoreId( (int)$product->getStoreId(  ) );
		}
	}

?>