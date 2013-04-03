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

	class Innoexts_Warehouse_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getWarehouseHelper(  )->getVersionHelper(  );
		}

		/**
     * Get allowed products
     * 
     * @return array of Mage_Catalog_Model_Product
     */
		function getAllowProducts() {
			if (!$this->hasAllowProducts(  )) {
				$helper = $this->getWarehouseHelper(  );
				$config = $helper->getConfig(  );
				$assignmentMethodHelper = $helper->getAssignmentMethodHelper(  );
				$inventoryHelper = $helper->getCatalogInventoryHelper(  );
				$products = array(  );
				$parentProduct = $this->getProduct(  );
				$parentStockItem = $parentProduct->getStockItem(  );
				$parentProductId = $parentProduct->getId(  );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$skipSaleableCheck = Mage::helper( 'catalog/product' )->getSkipSaleableCheck(  );
				}

				$allProducts = $parentProduct->getTypeInstance( true )->getUsedProducts( null, $parentProduct );

				if ($config->isMultipleMode(  )) {
					$stockIds = $inventoryHelper->getStockIds(  );
				} 
else {
					$stockIds = array( $assignmentMethodHelper->getQuoteStockId(  ) );
				}

				foreach ($allProducts as $product) {
					$productId = $product->getId(  );
					foreach ($stockIds as $stockId) {
						$pStockItem = $inventoryHelper->getStockItemCached( $parentProductId, $stockId );
						$pStockItem->assignProduct( $parentProduct );
						$stockItem = $inventoryHelper->getStockItemCached( $productId, $stockId );
						$stockItem->assignProduct( $product );

						if ($this->getVersionHelper(  )->isGe1700(  )) {
							if (( ( $product->isSaleable(  ) && $parentProduct->isSaleable(  ) ) || $skipSaleableCheck )) {
								$products[] = $product;
								break;
							}

							continue;
						}


						if (( $product->isSaleable(  ) && $parentProduct->isSaleable(  ) )) {
							$products[] = $product;
							break;
						}
					}
				}

				$parentStockItem->assignProduct( $parentProduct );
				$this->setAllowProducts( $products );
			}

			return $this->getData( 'allow_products' );
		}
	}

?>