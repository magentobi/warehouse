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

	class Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock extends Mage_CatalogInventory_Model_Mysql4_Stock {
		private $_stockId = null;

		/**
     * Get stock id
     * 
     * @return int
     */
		function getStockId() {
			return $this->_stockId;
		}

		/**
     * Set stock id
     * 
     * @param   int $stockId
     */
		function setStockId($stockId) {
			$this->_stockId = $stockId;
		}

		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get stock items data for requested products
     *
     * @param Mage_CatalogInventory_Model_Stock $stock
     * @param array $products
     * @param bool $lockRows
     * 
     * @return array
     */
		function getProductsStock($stock, $productQtys, $lockRows = false) {
			if (!count( $productQtys )) {
				return array(  );
			}

			$adapter = $this->_getWriteAdapter(  );
			$select = $adapter->select(  )->from( array( 'si' => $this->getTable( 'cataloginventory/stock_item' ) ) )->join( array( 'p' => $this->getTable( 'catalog/product' ) ), 'p.entity_id=si.product_id', array( 'type_id' ) )->forUpdate( $lockRows );
			$queryPieces = array(  );
			foreach ($productQtys as $productId => $productStockQtys) {
				foreach ($productStockQtys as $stockId => $qty) {

					if (!$stockId) {
						$stockId = $this->getWarehouseHelper(  )->getDefaultStockId(  );
					}

					array_push( $queryPieces, '(' . $adapter->quoteInto( '(product_id = ?)', $productId ) . ' AND ' . $adapter->quoteInto( '(stock_id = ?)', $stockId ) . ')' );
				}
			}

			$select->where( '(' . implode( ' OR ', $queryPieces ) . ')' );
			return $this->_getWriteAdapter(  )->fetchAll( $select );
		}

		/**
     * Correct particular stock products qty based on operator
     * 
     * @param Mage_CatalogInventory_Model_Stock $stock
     * @param array $productQtys
     * @param string $operator +/-
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock
     */
		function correctItemsQty($stock, $productQtys, $operator = '-') {
			if (empty( $$productQtys )) {
				return $this;
			}

			$query = 'UPDATE ' . $this->getTable( 'cataloginventory/stock_item' ) . ' SET `qty`=CASE ';
			$adapter = $this->_getWriteAdapter(  );
			foreach ($productQtys as $productId => $productStockQtys) {
				foreach ($productStockQtys as $stockId => $qty) {

					if (!$stockId) {
						$stockId = $this->getWarehouseHelper(  )->getDefaultStockId(  );
					}


					if (!$qty) {
						$qty = 296;
					}

					$query .= $adapter->quoteInto( ' WHEN ((`product_id` = ?)', $productId ) . $adapter->quoteInto( ' AND (`stock_id` = ?))', $stockId ) . $adapter->quoteInto( ' THEN `qty`' . $operator . '? ', $qty );
				}
			}

			$query .= ' ELSE `qty` END';
			$query .= $adapter->quoteInto( ' WHERE `product_id` IN (?)', array_keys( $productQtys ) );
			$adapter->beginTransaction(  );
			$adapter->query( $query );
			$adapter->commit(  );
			return $this;
		}

		/**
     * Add join to select only in stock products
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Link_Product_Collection $collection
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock
     */
		function setInStockFilterToCollection($collection) {
			$manageStock = Mage::getStoreConfig( XML_PATH_MANAGE_STOCK );
			$cond = array( '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=1 AND {{table}}.is_in_stock=1', '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=0' );

			if ($manageStock) {
				$cond[] = '{{table}}.use_config_manage_stock = 1 AND {{table}}.is_in_stock=1';
			} 
else {
				$cond[] = '{{table}}.use_config_manage_stock = 1';
			}

			$collection->joinField( 'inventory_in_stock', 'cataloginventory/stock_item', 'is_in_stock', 'product_id=entity_id', '(' . join( ') OR (', $cond ) . ')' );
			$collection->distinct( true );
			return $this;
		}

		/**
     * Load inventory configuration settings
     */
		function _initConfig() {
			if (!$this->_isConfig) {
				parent::_initConfig(  );
				$this->_stock->setId( $this->getStockId(  ) );
			}

		}
	}

?>