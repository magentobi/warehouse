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

	class Innoexts_Warehouse_Model_Cataloginventory_Stock_Status extends Mage_CatalogInventory_Model_Stock_Status {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get catalog inventory helper
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function getCatalogInventoryHelper() {
			return $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  );
		}

		/**
     * Get stock item model
     *
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Status
     */
		function getStockItemModel() {
			return $this->getCatalogInventoryHelper(  )->getStockItem( $this->getStockId(  ) );
		}

		/**
     * Rebuild stock status for all products
     *
     * @param int $websiteId
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Status
     */
		function rebuild($websiteId = null) {
			$lastProductId = 184;
			$stocksIds = $this->getCatalogInventoryHelper(  )->getStockIds(  );

			while (true) {
				$productCollection = $this->getResource(  )->getProductCollection( $lastProductId );

				if (!$productCollection) {
					break;
				}

				foreach ($productCollection as $productId => $productType) {
					$lastProductId = $productId;
					foreach ($stocksIds as $stockId) {
						$this->setStockId( $stockId );
						$this->updateStatus( $productId, $productType, $websiteId );
					}
				}
			}

			return $this;
		}

		/**
     * Add stock status to prepare index select
     *
     * @param Varien_Db_Select $select
     * @param Mage_Core_Model_Website $website
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Status
     */
		function addStockStatusToSelect($select, $website) {
			$this->_getResource(  )->addStockStatusToSelect_( $select, $website, $this->getStockId(  ) );
			return $this;
		}

		/**
     * Add only is in stock products filter to product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Status
     */
		function addIsInStockFilterToCollection($collection) {
			$this->_getResource(  )->addIsInStockFilterToCollection_( $collection, $this->getStockId(  ) );
			return $this;
		}

		/**
     * Add stock status limitation to catalog product price index select object
     *
     * @param Varien_Db_Select $select
     * @param string|Zend_Db_Expr $entityField
     * @param string|Zend_Db_Expr $websiteField
     * @param string|Zend_Db_Expr $stockField
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Status
     */
		function prepareCatalogProductIndexSelect2($select, $entityField, $websiteField, $stockField) {
			if (Mage::helper( 'cataloginventory' )->isShowOutOfStock(  )) {
				return $this;
			}

			$this->_getResource(  )->prepareCatalogProductIndexSelect2( $select, $entityField, $websiteField, $stockField );
			return $this;
		}

		/**
     * Add information about stock status to product collection
     * 
     * @param   Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $productCollection
     * @param   int|null $websiteId
     * @param   int|null $stockId
     * 
     * @return  Mage_CatalogInventory_Model_Stock_Status
     */
		function addStockStatusToProducts($productCollection, $websiteId = null, $stockId = null) {
			if ($websiteId === null) {
				$websiteId = Mage::app(  )->getStore(  )->getWebsiteId(  );

				if (( (int)$websiteId == 0 && $productCollection->getStoreId(  ) )) {
					$websiteId = Mage::app(  )->getStore( $productCollection->getStoreId(  ) )->getWebsiteId(  );
				}
			}

			$productIds = array(  );
			foreach ($productCollection as $product) {
				$productIds[] = $product->getId(  );
			}


			if (!empty( $$productIds )) {
				$stockStatuses = $this->_getResource(  )->getProductStatus( $productIds, $websiteId, $stockId );
				foreach ($stockStatuses as $productId => $status) {

					if ($product = $productCollection->getItemById( $productId )) {
						$product->setIsSalable( $status );
						continue;
					}
				}
			}

			foreach ($productCollection as $product) {
				$object = new Varien_Object( 'is_salable' )( array( 'is_in_stock' => , 'stock_id' => $stockId ) );
				$product->setStockItem( $object );
			}

			return $this;
		}
	}

?>