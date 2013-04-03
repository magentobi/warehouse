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

	class Innoexts_Warehouse_Model_Mysql4_Sales_Quote_Item_Collection extends Mage_Sales_Model_Mysql4_Quote_Item_Collection {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * After load processing
     *
     * @return Innoexts_Warehouse_Model_Mysql4_Sales_Quote_Item_Collection
     */
		function _afterLoad() {
			$dataChanges = array(  );
			foreach ($this->_items as $item) {
				$productId = $item->getProductId(  );
				$stockId = $item->getStockId(  );
				$dataChanges[$productId][$stockId] = $item->getDataChanges(  );
			}

			parent::_afterLoad(  );
			foreach ($this->_items as $item) {
				$productId = $item->getProductId(  );
				$stockId = $item->getStockId(  );

				if (( isset( $dataChanges[$productId] ) && isset( $dataChanges[$productId][$stockId] ) )) {
					$item->setDataChanges( $dataChanges[$productId][$stockId] );
					continue;
				}
			}

			return $this;
		}

		/**
     * Add products to items and item options
     *
     * @return Mage_Sales_Model_Mysql4_Quote_Item_Collection
     */
		function _assignProducts() {
			$productIds = array(  );
			foreach ($this as $item) {
				$productIds[] = $item->getProductId(  );
			}

			$this->_productIds = array_merge( $this->_productIds, $productIds );
			$productCollection = Mage::getModel( 'catalog/product' )->getCollection(  )->setStoreId( $this->getStoreId(  ) )->addIdFilter( $this->_productIds )->addAttributeToSelect( Mage::getSingleton( 'sales/quote_config' )->getProductAttributes(  ) )->addOptionsToResult(  )->addStoreFilter(  )->addUrlRewrite(  )->addTierPriceData(  );
			Mage::dispatchEvent( 'prepare_catalog_product_collection_prices', array( 'collection' => $productCollection, 'store_id' => $this->getStoreId(  ) ) );
			Mage::dispatchEvent( 'sales_quote_item_collection_products_after_load', array( 'product_collection' => $productCollection ) );
			$recollectQuote = false;
			foreach ($this as $item) {
				$product = $productCollection->getItemById( $item->getProductId(  ) );

				if ($product) {
					$product->setCustomOptions( array(  ) );
					$qtyOptions = array(  );
					$optionProductIds = array(  );
					foreach ($item->getOptions(  ) as $option) {
						$product->getTypeInstance( true )->assignProductToOption( $productCollection->getItemById( $option->getProductId(  ) ), $option, $product );

						if (( is_object( $option->getProduct(  ) ) && $option->getProduct(  )->getId(  ) != $product->getId(  ) )) {
							$optionProductIds[$option->getProduct(  )->getId(  )] = $option->getProduct(  )->getId(  );
							continue;
						}
					}


					if ($optionProductIds) {
						foreach ($optionProductIds as $optionProductId) {
							$qtyOption = $item->getOptionByCode( 'product_qty_' . $optionProductId );

							if ($qtyOption) {
								$qtyOptions[$optionProductId] = $qtyOption;
								continue;
							}
						}
					}

					$item->setQtyOptions( $qtyOptions );
					$item->setProduct( $product );
					continue;
				}

				$item->isDeleted( true );
				$recollectQuote = true;
			}

			foreach ($this as $item) {
				$item->checkData(  );
			}


			if (( $recollectQuote && $this->_quote )) {
				$this->_quote->collectTotals(  );
			}

			return $this;
		}
	}

?>