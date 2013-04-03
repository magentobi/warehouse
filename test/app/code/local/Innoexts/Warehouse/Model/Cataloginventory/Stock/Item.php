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

	class Innoexts_Warehouse_Model_Cataloginventory_Stock_Item extends Mage_CatalogInventory_Model_Stock_Item {
		/**
     * Get helper
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
     * Retrieve stock identifier
     *
     * @return mixed
     */
		function getStockId() {
			return $this->getData( 'stock_id' );
		}

		/**
     * Get warehouse
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			$helper = $this->getWarehouseHelper(  );
			return $helper->getWarehouseByStockId( $this->getStockId(  ) );
		}

		/**
     * Load available item data by product
     * 
     * @param   Mage_Catalog_Model_Product $product
     * @return  Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function loadAvailableByProduct($product) {
			$this->_getResource(  )->loadAvailableByProduct( $this, $product );
			$this->setOrigData(  );
			return $this;
		}

		/**
     * Get default data
     * 
     * @return array
     */
		function getDefaultData() {
			return array( 'qty' => 0, 'is_in_stock' => 0, 'stock_status' => 0, 'manage_stock' => 1 );
		}

		/**
     * Adding available stock data to product
     * 
     * @param   Mage_Catalog_Model_Product $product
     * @return  Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function assignAvailableProduct($product) {
			$helper = $this->getWarehouseHelper(  );
			$productPriceHelper = $helper->getProductPriceHelper(  );
			$stockStatus = $this->getCatalogInventoryHelper(  )->getStockStatusSingleton(  );

			if (( !$this->getId(  ) || !$this->getProductId(  ) )) {
				$this->loadAvailableByProduct( $product );

				if (!$this->getId(  )) {
					$this->addData( $this->getDefaultData(  ) );
				}
			}

			$this->setProduct( $product );
			$product->setStockItem( $this );
			$product->setIsInStock( $this->getIsInStock(  ) );
			$stockStatus->assignProduct( $product, $this->getStockId(  ), $this->getStockStatus(  ) );
			$productPriceHelper->applyPrice( $product );
			return $this;
		}

		/**
     * Adding stock data to product
     * 
     * @param   Mage_Catalog_Model_Product $product
     * @return  Innoexts_Warehouse_Model_Cataloginventory_Stock_Item
     */
		function assignProduct($product) {
			$helper = $this->getWarehouseHelper(  );
			$productPriceHelper = $helper->getProductPriceHelper(  );
			$stockStatus = $this->getCatalogInventoryHelper(  )->getStockStatusSingleton(  );

			if (( !$this->getId(  ) || !$this->getProductId(  ) )) {
				if (( $this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  ) && !$this->getStockId(  ) )) {
					$this->loadAvailableByProduct( $product );
				} 
else {
					$this->loadByProduct( $product );
				}


				if (!$this->getId(  )) {
					$this->addData( $this->getDefaultData(  ) );
				}
			}

			$this->setProduct( $product );
			$product->setStockItem( $this );
			$product->setIsInStock( $this->getIsInStock(  ) );
			$stockStatus->assignProduct( $product, $this->getStockId(  ), $this->getStockStatus(  ) );
			$productPriceHelper->applyPrice( $product );
			return $this;
		}

		/**
     * Get maximal stock quantity
     * 
     * @param float $origQty
     * @return float
     */
		function getMaxStockQty($origQty) {
			$qty = $this->getQty(  );

			if ($origQty < $qty) {
				$qty = $qtyIncrements;
			}


			if (!$this->getManageStock(  )) {
				return $qty;
			}


			if (!$this->getIsInStock(  )) {
				return false;
			}


			if (!is_numeric( $qty )) {
				$qty = Mage::app(  )->getLocale(  )->getNumber( $qty );
			}


			if (!$this->getIsQtyDecimal(  )) {
				$qty = (int)$qty;
			}

			$qtyIncrements = $this->getQtyIncrements(  );

			if (!$qtyIncrements) {
				$qtyIncrements = $this->getDefaultQtyIncrements(  );
			}


			if (( $qtyIncrements && $qty % $qtyIncrements != 0 )) {
				$qty = floor( $qty / $qtyIncrements ) * $qtyIncrements;
			}


			if (( $this->getMinSaleQty(  ) && $qty < $this->getMinSaleQty(  ) )) {
				return false;
			}


			if (( $this->getMaxSaleQty(  ) && $this->getMaxSaleQty(  ) < $qty )) {
				$qty = $this->getMaxSaleQty(  );
			}


			if (!$qty) {
				return false;
			}

			return $qty;
		}

		/**
     * Returns product instance
     *
     * @return Mage_Catalog_Model_Product|null
     */
		function getProduct() {
			return ($this->_productInstance ? $this->_productInstance : $this->_getData( 'product' ));
		}
	}

?>