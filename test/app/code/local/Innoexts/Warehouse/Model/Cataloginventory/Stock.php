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

	class Innoexts_Warehouse_Model_Cataloginventory_Stock extends Mage_CatalogInventory_Model_Stock {
		private $_eventPrefix = 'cataloginventory_stock';
		private $_eventObject = 'stock';
		private $_cacheTag = 'cataloginventory_stock';

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
     * Get stock identifier
     *
     * @return mixed
     */
		function getId() {
			$fieldName = ($this->getIdFieldName(  ) ? $this->getIdFieldName(  ) : 'id');
			return $this->_getData( $fieldName );
		}

		/**
     * Get stock item collection
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item_Collection
     */
		function getItemCollection() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			$itemCollection = $this->getCatalogInventoryHelper(  )->getStockItemCollection(  );

			if (( !$config->isMultipleMode(  ) || $this->getId(  ) )) {
				$itemCollection->addStockFilter( $this->getId(  ) );
			}

			return $itemCollection;
		}

		/**
     * Prepare product qtys
     *
     * @param array $items
     */
		function _prepareProductQtys($items) {
			$qtys = array(  );
			foreach ($items as $productId => $productItems) {
				foreach ($productItems as $stockId => $item) {

					if (empty( $item['item'] )) {
						$stockItem = $this->getCatalogInventoryHelper(  )->getStockItem( $stockId );
						$stockItem->loadByProduct( $productId );
					} 
else {
						$stockItem = $item['item'];
					}

					$canSubtractQty = ( $stockItem->getId(  ) && $stockItem->canSubtractQty(  ) );

					if (( $canSubtractQty && Mage::helper( 'catalogInventory' )->isQty( $stockItem->getTypeId(  ) ) )) {
						$qtys[$productId][$stockId] = $item['qty'];
						continue;
					}
				}
			}

			return $qtys;
		}

		/**
     * Subtract product qtys from stock.
     *
     * @param array $items
     * 
     * @return array
     */
		function registerProductsSale($items) {
			$qtys = $this->_prepareProductQtys( $items );
			$item = $this->getCatalogInventoryHelper(  )->getStockItem(  );
			$this->_getResource(  )->beginTransaction(  );
			$stockInfo = $this->_getResource(  )->getProductsStock( $this, $qtys, true );
			$fullSaveItems = array(  );
			foreach ($stockInfo as $itemInfo) {
				$item->getProductId(  );
				$item->getStockId(  );
				$stockId = $productId = $item->setData( $itemInfo );
				$_qty = (( isset( $qtys[$productId] ) && isset( $qtys[$productId][$stockId] ) ) ? $qtys[$productId][$stockId] : null);

				if (!$item->checkQty( $_qty )) {
					$this->_getResource(  )->commit(  );
					Mage::throwException( Mage::helper( 'cataloginventory' )->__( 'Not all products are available in the requested quantity' ) );
				}

				$item->subtractQty( $_qty );

				if (( !$item->verifyStock(  ) || $item->verifyNotification(  ) )) {
					$fullSaveItems[] = clone $item;
					continue;
				}
			}

			$this->_getResource(  )->correctItemsQty( $this, $qtys, '-' );
			$this->_getResource(  )->commit(  );
			return $fullSaveItems;
		}

		/**
     * Subtract ordered qty for product
     *
     * @param   Varien_Object $item
     * 
     * @return  Mage_CatalogInventory_Model_Stock
     */
		function registerItemSale($item) {
			$productId = $item->getProductId(  );

			if ($productId) {
				$stockItem = $this->getCatalogInventoryHelper(  )->getStockItem( $item->getStockId(  ) );
				$stockItem->loadByProduct( $productId );

				if (Mage::helper( 'catalogInventory' )->isQty( $stockItem->getTypeId(  ) )) {
					if ($item->getStoreId(  )) {
						$stockItem->setStoreId( $item->getStoreId(  ) );
					}


					if (( $stockItem->checkQty( $item->getQtyOrdered(  ) ) || Mage::app(  )->getStore(  )->isAdmin(  ) )) {
						$stockItem->subtractQty( $item->getQtyOrdered(  ) );
						$stockItem->save(  );
					}
				}
			} 
else {
				Mage::throwException( Mage::helper( 'cataloginventory' )->__( 'Cannot specify product identifier for the order item.' ) );
			}

			return $this;
		}

		/**
     * Get back to stock (when order is canceled or whatever else)
     * 
     * @param int $productId
     * @param numeric $qty
     * 
     * @return Mage_CatalogInventory_Model_Stock
     */
		function backItemQty($productId, $qty) {
			$stockItem = $this->getCatalogInventoryHelper(  )->getStockItem( $this->getId(  ) );
			$stockItem->loadByProduct( $productId );

			if (( $stockItem->getId(  ) && Mage::helper( 'catalogInventory' )->isQty( $stockItem->getTypeId(  ) ) )) {
				$stockItem->addQty( $qty );

				if (( $stockItem->getCanBackInStock(  ) && $stockItem->getMinQty(  ) < $stockItem->getQty(  ) )) {
					$stockItem->setIsInStock( true )->setStockStatusChangedAutomaticallyFlag( true );
				}

				$stockItem->save(  );
			}

			return $this;
		}

		/**
     * Create filter chain
     *
     * @return Zend_Filter
     */
		function createFilterChain() {
			return new Zend_Filter(  );
		}

		/**
     * Create validator chain
     *
     * @return Zend_Validate
     */
		function createValidatorChain() {
			return new Zend_Validate(  );
		}

		/**
     * Filter catalog inventory stock
     *
     * @throws Mage_Core_Exception
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock
     */
		function filter() {
			$filters = array( 'stock_name' => ( new Zend_Filter_StripTags(  ) ) );
			foreach ($filters as $field => $filter) {
				$this->setData( $field, $filter->filter( $this->getData( $field ) ) );
			}

			return $this;
		}

		/**
     * Validate catalog inventory stock
     * 
     * @throws Mage_Core_Exception
     * @return bool
     */
		function validate() {
			$validators = array( 'stock_name' => ( new Zend_Validate_StringLength( array( 'min' => 3, 'max' => 255 ) ), true ) );
			$errorMessages = array(  );
			foreach ($validators as $field => $validator) {

				if (!$validator->isValid( $this->getData( $field ) )) {
					$errorMessages = array_merge( $errorMessages, $validator->getMessages(  ) );
					continue;
				}
			}


			if (count( $errorMessages )) {
				Mage::throwException( join( '
', $errorMessages ) );
			}

			return true;
		}

		/**
     * Processing object before save data
     *
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock
     */
		function _beforeSave() {
			$this->filter(  );
			$this->validate(  );
			parent::_beforeSave(  );
			return $this;
		}
	}

?>