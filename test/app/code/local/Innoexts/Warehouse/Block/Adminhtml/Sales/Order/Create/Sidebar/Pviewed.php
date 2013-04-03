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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Sidebar_Pviewed extends Mage_Adminhtml_Block_Sales_Order_Create_Sidebar_Pviewed {
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
				$stores = array(  );
				$website = Mage::app(  )->getStore( $this->getStoreId(  ) )->getWebsite(  );
				foreach ($website->getStores(  ) as $store) {
					$stores[] = $store->getId(  );
				}

				$collection = Mage::getModel( 'reports/event' )->getCollection(  )->addStoreFilter( $stores )->addRecentlyFiler( EVENT_PRODUCT_VIEW, $this->getCustomerId(  ), 0 );
				$productIds = array(  );
				foreach ($collection as $event) {
					$productIds[] = $event->getObjectId(  );
				}

				$productCollection = null;

				if ($productIds) {
					$productCollection = Mage::getModel( 'catalog/product' )->getCollection(  )->setStoreId( $this->getQuote(  )->getStoreId(  ) )->addStoreFilter( $this->getQuote(  )->getStoreId(  ) )->addAttributeToSelect( 'name' )->addAttributeToSelect( 'price' )->addAttributeToSelect( 'small_image' )->addIdFilter( $productIds );
					$stockId = $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId( $this->getQuote(  ) );

					if ($stockId) {
						$productCollection->setFlag( 'stock_id', $stockId );
					}

					$productCollection->load(  );
				}

				$this->setData( 'item_collection', $productCollection );
			}

			return $productCollection;
		}
	}

?>