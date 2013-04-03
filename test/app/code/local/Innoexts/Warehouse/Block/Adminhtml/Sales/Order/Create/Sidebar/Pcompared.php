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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Sidebar_Pcompared extends Mage_Adminhtml_Block_Sales_Order_Create_Sidebar_Pcompared {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve item collection
     *
     * @return mixed
     */
		function getItemCollection() {
			$productCollection = $this->getData( 'item_collection' );

			if (is_null( $productCollection )) {
				$skipProducts = array(  );

				if ($collection = $this->getCreateOrderModel(  )->getCustomerCompareList(  )) {
					$collection = $collection->getItemCollection(  )->useProductItem( true )->setStoreId( $this->getStoreId(  ) )->setCustomerId( $this->getCustomerId(  ) )->load(  );
					foreach ($collection as $_item) {
						$skipProducts[] = $_item->getProductId(  );
					}
				}

				$productCollection = Mage::getModel( 'catalog/product' )->getCollection(  )->setStoreId( $this->getQuote(  )->getStoreId(  ) )->addStoreFilter( $this->getQuote(  )->getStoreId(  ) )->addAttributeToSelect( 'name' )->addAttributeToSelect( 'price' )->addAttributeToSelect( 'small_image' );
				Mage::getResourceSingleton( 'reports/event' )->applyLogToCollection( $productCollection, EVENT_PRODUCT_COMPARE, $this->getCustomerId(  ), 0, $skipProducts );
				$stockId = $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId( $this->getQuote(  ) );

				if ($stockId) {
					$productCollection->setFlag( 'stock_id', $stockId );
				}

				$productCollection->load(  );
				$this->setData( 'item_collection', $productCollection );
			}

			return $productCollection;
		}
	}

?>