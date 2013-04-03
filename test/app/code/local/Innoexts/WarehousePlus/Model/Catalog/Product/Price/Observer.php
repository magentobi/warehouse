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

	class Innoexts_WarehousePlus_Model_Catalog_Product_Price_Observer extends Innoexts_Warehouse_Model_Catalog_Product_Price_Observer {
		/**
     * Get product collection final price
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Price_Observer
     */
		function getCollectionFinal($observer) {
			$this->getWarehouseHelper(  );
			$collection = $observer->getEvent(  )->getCollection(  );
			$customerAddress = $helper->getCustomerLocatorHelper(  )->getCustomerAddress(  );
			$zonePriceCollection = $helper = Mage::getSingleton( 'catalog/product_zone_price' )->getCollection(  );
			$zonePriceCollection->setProductsFilter( $collection );
			$zonePriceCollection->setAddressFilter( $customerAddress );
			$zonePrices = array(  );
			foreach ($zonePriceCollection as $zonePrice) {
				$productId = $zonePrice->getProductId(  );

				if (!isset( $zonePrices[$productId] )) {
					$zonePrices[$productId] = $zonePrice;
					continue;
				}
			}


			if (count( $zonePrices )) {
				foreach ($collection as $product) {
					$productId = $product->getId(  );

					if (isset( $zonePrices[$productId] )) {
						$zonePrice = $zonePrices[$productId];
						$finalPrice = (double)$product->getFinalPrice(  );
						$finalPrice = $zonePrice->getFinalPrice( $finalPrice );
						$product->setFinalPrice( $finalPrice );
						continue;
					}
				}
			}

			return $this;
		}

		/**
     * Get product final price
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Price_Observer
     */
		function getFinal($observer) {
			$product = $observer->getEvent(  )->getProduct(  );

			if ($product instanceof Mage_Catalog_Model_Product) {
				$helper = $this->getWarehouseHelper(  );
				$customerAddress = $helper->getCustomerLocatorHelper(  )->getCustomerAddress(  );
				$zonePrice = Mage::getModel( 'catalog/product_zone_price' );
				$zonePrice->loadByProductIdAndAddress( $product->getId(  ), $customerAddress );

				if ($zonePrice->getId(  )) {
					$finalPrice = (double)$product->getFinalPrice(  );
					$finalPrice = $zonePrice->getFinalPrice( $finalPrice );
					$product->setFinalPrice( $finalPrice );
				}
			}

			return $this;
		}

		/**
     * Prepare product index
     *
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Price_Observer
     */
		function prepareIndex($observer) {
			$event = $observer->getEvent(  );
			$select = clone $event->getSelect(  );
			$indexTable = $event->getIndexTable(  );
			$entityId = $event->getEntityId(  );
			$updateFields = $event->getUpdateFields(  );
			$resource = Mage::getSingleton( 'core/resource' );
			$adapter = $resource->getConnection( 'core_write' );
			$productZonePriceTable = $resource->getTableName( 'catalog/product_zone_price' );

			if (empty( $$updateFields )) {
				return $this;
			}


			if (is_array( $indexTable )) {
				foreach ($indexTable as $key => $value) {

					if (is_string( $key )) {
						$indexAlias = $priceSelect;
					} 
else {
						$indexAlias = $priceExpr;
					}

					break;
				}
			} 
else {
				$indexAlias = $indexAlias;
			}

			foreach ($updateFields as $priceField) {

				if ($priceField != 'min_price') {
					continue;
				}

				$priceCond = $adapter->quoteIdentifier( array( $indexAlias, $priceField ) );
				$priceAlias = $priceField . '_pzp';
				$priceCountAlias = $priceAlias . '_count';
				$function = 'MIN';
				$countSelect = $adapter->select(  )->from( array( $priceCountAlias => $productZonePriceTable ), 'COUNT(*)' )->where( $priceCountAlias . '.product_id = ' . $entityId );
				$priceSelect = ( new Zend_Db_Expr( $function . '(IF(' . $priceAlias . '.price_type = \'fixed\', ' . 'IF(' . $priceAlias . ( '.price < ' . $priceCond . ', ' . $priceCond . ' - ' ) . $priceAlias . ( '.price, ' . $priceCond . '), ' ) . 'IF(' . $priceAlias . ( '.price < 100, ROUND(' . $priceCond . ' - (' ) . $priceAlias . ( '.price * (' . $priceCond . ' / 100)), 4), ' . $priceCond . ')' ) . '))' ) );
				$priceExpr = new Zend_Db_Expr(  )( 'IF((' .  . ') > 0, (' . $priceSelect->assemble(  ) . ( '), ' . $priceCond . ')' ) );
				$select->columns( array( $priceField => $priceExpr ) );
			}

			$query = $select->crossUpdateFromSelect( $indexTable );
			$adapter->query( $query );
			return $this;
		}
	}

?>