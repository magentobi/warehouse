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

	class Innoexts_Warehouse_Model_Sales_Order_Item extends Mage_Sales_Model_Order_Item {
		private $_stockItem = null;
		private $_warehouse = null;

		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
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
     * Retrieve warehouse
     *
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			if (is_null( $this->_warehouse )) {
				if ($this->getStockId(  )) {
					$this->_warehouse = $this->getWarehouseHelper(  )->getWarehouseByStockId( $this->getStockId(  ) );
				}
			}

			return $this->_warehouse;
		}

		/**
     * Get warehouse title
     * 
     * @return string
     */
		function getWarehouseTitle() {
			$warehouse = $this->getWarehouse(  );

			if ($warehouse) {
				return $warehouse->getTitle(  );
			}

		}

		/**
     * Get warehouse description
     * 
     * @return string
     */
		function getWarehouseDescription() {
			$warehouse = $this->getWarehouse(  );

			if ($warehouse) {
				return $warehouse->getDescription(  );
			}

		}

		/**
     * Get product
     * 
     * @return Mage_Catalog_Model_Product
     */
		function _getProduct() {
			return $this->_getData( 'product' );
		}

		/**
     * Set product
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function _setProduct($product) {
			$this->setData( 'product', $product );
			return $this;
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function _getStockId() {
			return $this->_getData( 'stock_id' );
		}

		/**
     * Set stock identifier
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function _setStockId($stockId) {
			$this->setData( 'stock_id', $stockId );
			return $this;
		}

		/**
     * Get stock item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function _getStockItem() {
			return $this->_stockItem;
		}

		/**
     * Set stock item
     * 
     * @param Innoexts_Warehouse_Model_Cataloginventory_Stock_Item $stockItem
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function _setStockItem($stockItem) {
			$this->_stockItem = $stockItem;
			return $this;
		}

		/**
     * Unset stock item
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function _unsetStockItem() {
			if (!is_null( $this->_stockItem )) {
				$this->_stockItem = null;
			}

			return $this;
		}

		/**
     * Retrieve product model
     *
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			$product = $this->_getData( 'product' );

			if (( $product === null && $this->getProductId(  ) )) {
				$product = Mage::getModel( 'catalog/product' )->load( $this->getProductId(  ) );
				$this->setProduct( $product );
			}

			return $product;
		}

		/**
     * Set product
     * 
     * @param   Mage_Catalog_Model_Product $product
     * 
     * @return  Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function setProduct($product) {
			$this->_unsetStockItem(  );
			$this->_setProduct( $product );
			$this->getStockItem(  );
			parent::setProduct( $product );
			return $this;
		}

		/**
     * Set stock identifier
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function setStockId($stockId) {
			$this->_unsetStockItem(  );
			$this->_setStockId( $stockId );
			$this->getStockItem(  );
			return $this;
		}

		/**
     * Get stock item
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function getStockItem() {
			$stockItem = $this->_getStockItem(  );

			if (!$stockItem) {
				$stockId = $this->_getStockId(  );

				if (!$stockId) {
					$product = $this->_getProduct(  );

					if ($product) {
						$stockItem = $product->getStockItem(  );

						if ($stockItem) {
							$this->setStockItem( $stockItem );
							return $this->_getStockItem(  );
						}

						return null;
					}

					return null;
				}

				$stockItem = $this->getCatalogInventoryHelper(  )->getStockItem( $stockId );
				$this->setStockItem( $stockItem );
				return $this->_getStockItem(  );
			}

			return $this->_getStockItem(  );
		}

		/**
     * Set stock item
     * 
     * @param Innoexts_Warehouse_Model_Cataloginventory_Stock_Item $stockItem
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function setStockItem($stockItem) {
			if (( $stockItem && $stockItem->getStockId(  ) )) {
				$stockId = $stockItem->getStockId(  );
				$this->_setStockId( $stockId );
				$this->_setStockItem( $stockItem );
				$product = $this->_getProduct(  );

				if ($product) {
					$stockItem->assignProduct( $product );
				}
			}

			return $this;
		}

		/**
     * Clear order object data
     * 
     * @param string $key data key
     * 
     * @return Innoexts_Warehouse_Model_Sales_Order_Item
     */
		function unsetData($key = null) {
			parent::unsetData( $key );

			if (is_null( $key )) {
				$this->_stockItem = null;
			}

			return $this;
		}
	}

?>