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

	class Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Status extends Mage_CatalogInventory_Model_Mysql4_Stock_Status {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve product status
     * Return array as key product id, value - stock status
     *
     * @param int|array $productIds
     * @param int $websiteId
     * @param int $stockId
     *
     * @return array
     */
		function getProductStatus($productIds, $websiteId, $stockId = 1) {
			if (( $this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  ) && is_null( $stockId ) )) {
				if (!is_array( $productIds )) {
					$productIds = array( $productIds );
				}

				$adapter = $this->_getReadAdapter(  );
				$conditionSelect = $adapter->select(  );
				$conditionSelect->from( array( 'status_table' => $this->getTable( 'cataloginventory/stock_status' ) ), array( 'stock_id' ) );
				$conditionSelect->where( '(status_table.product_id = main_table.product_id) AND ' . '(status_table.website_id = main_table.website_id)' );
				$conditionSelect->order( 'status_table.stock_status DESC' );
				$conditionSelect->limit( 1 );
				$select = $adapter->select(  )->from( array( 'main_table' => $this->getMainTable(  ) ), array( 'product_id', 'stock_status' ) )->where( 'main_table.product_id IN(?)', $productIds )->where( 'main_table.website_id = ?', $websiteId )->where( 'main_table.stock_id = (' . $conditionSelect->assemble(  ) . ')' );
				return $this->_getReadAdapter(  )->fetchPairs( $select );
			}

			return parent::getProductStatus( $productIds, $websiteId, $stockId );
		}

		/**
     * Add stock status to prepare index select
     *
     * @param Varien_Db_Select $select
     * @param Mage_Core_Model_Website $website
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Status
     */
		function addStockStatusToSelect_($select, $website, $stockId) {
			$websiteId = $website->getId(  );
			$adapter = $this->_getReadAdapter(  );

			if ($this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  )) {
				$conditionSelect = $adapter->select(  );
				$conditionSelect->from( array( 'stock_status_2' => $this->getMainTable(  ) ), 'stock_id' );
				$conditionSelect->order( array( 'stock_status_2.stock_status DESC' ) );
				$conditionSelect->where( '(stock_status_2.product_id = stock_status.product_id) AND ' . '(stock_status_2.website_id = stock_status.website_id)' );
				$conditionSelect->limit( 1 );
				$stockIdValue = '(' . $conditionSelect->assemble(  ) . ')';
			} 
else {
				$stockIdValue = $adapter->quote( $stockId );
			}

			$select->joinLeft( array( 'stock_status' => $this->getMainTable(  ) ), '(e.entity_id = stock_status.product_id) AND (stock_status.website_id = ' . $websiteId . ') AND ' . '(stock_status.stock_id = ' . $stockIdValue . ')', array( 'salable' => 'stock_status.stock_status' ) );
			return $this;
		}

		/**
     * Add only is in stock products filter to product collection
     *
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Status
     */
		function addIsInStockFilterToCollection_($collection, $stockId) {
			$websiteId = ;
			$this->_getReadAdapter(  );
			$adapter = Mage::app(  )->getStore( $collection->getStoreId(  ) )->getWebsiteId(  );

			if (( !$stockId || $this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  ) )) {
				$conditionSelect = $adapter->select(  );
				$conditionSelect->from( array( 'stock_status_index_2' => $this->getMainTable(  ) ), 'stock_id' );
				$conditionSelect->order( array( 'stock_status_index_2.stock_status DESC' ) );
				$conditionSelect->where( '(stock_status_index_2.product_id = stock_status_index.product_id) AND ' . '(stock_status_index_2.website_id = stock_status_index.website_id)' );
				$conditionSelect->limit( 1 );
				$stockIdValue = '(' . $conditionSelect->assemble(  ) . ')';
			} 
else {
				$stockIdValue = $adapter->quote( $stockId );
			}

			$collection->getSelect(  )->join( array( 'stock_status_index' => $this->getMainTable(  ) ), '(e.entity_id = stock_status_index.product_id) AND (stock_status_index.website_id = ' . $websiteId . ') AND ' . '(stock_status_index.stock_id = ' . $stockIdValue . ')', array(  ) )->where( 'stock_status_index.stock_status=?', STATUS_IN_STOCK );
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
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Status
     */
		function prepareCatalogProductIndexSelect2($select, $entityField, $websiteField, $stockField) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			$tableAlias = 'ciss';
			$conditions = array( ( ( '(' ) . $tableAlias . '.product_id = ' . $entityField . ')' ), ( ( '(' ) . $tableAlias . '.website_id = ' . $websiteField . ')' ) );

			if ($config->isMultipleMode(  )) {
				$adapter = $this->_getReadAdapter(  );
				$stockSelect = $adapter->select(  );
				$stockTableAlias = 'ciss2';
				$stockSelect->from( array( $stockTableAlias => $this->getMainTable(  ) ), 'stock_id' );
				$stockSelect->where( implode( ' AND ', array( ( '(' ) . $stockTableAlias . '.product_id = ' . $tableAlias . '.product_id)', ( '(' ) . $stockTableAlias . '.website_id = ' . $tableAlias . '.website_id)' ) ) );
				$stockSelect->order( array( $stockTableAlias . '.stock_status DESC' ) );
				$stockSelect->limit( 1 );
				$stockField = '(' . $stockSelect->assemble(  ) . ')';
				array_push( $conditions, ( ( '(' ) . $tableAlias . '.stock_id = ' . $stockField . ')' ) );
			} 
else {
				if ($stockField) {
					array_push( $conditions, ( ( '(' ) . $tableAlias . '.stock_id = ' . $stockField . ')' ) );
				}
			}

			$select->join( array( $tableAlias => $this->getMainTable(  ) ), join( ' AND ', $conditions ), array(  ) );
			$select->where( $tableAlias . '.stock_status = ?', STATUS_IN_STOCK );

			if (( $config->isMultipleMode(  ) || !$stockField )) {
				$select->distinct( true );
			}

			return $this;
		}
	}

?>