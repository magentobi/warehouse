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

	class Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item extends Mage_CatalogInventory_Model_Mysql4_Stock_Item {
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
     * Loading available stock item data by product
     * 
     * @param   Mage_CatalogInventory_Model_Stock_Item $item
     * @param   Mage_Catalog_Model_Product $product
     * @param   float $qty
     * 
     * @return  Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item
     */
		function loadAvailableByProduct($item, $product) {
			$manageStock = $this->getCatalogInventoryHelper(  )->getManageStock(  );
			$adapter = $this->_getReadAdapter(  );
			$select = $this->_getLoadSelect( 'product_id', $product->getId(  ), $item );
			$joinConditions = array( $this->getMainTable(  ) . '.product_id = stock_status.product_id', $this->getMainTable(  ) . '.stock_id = stock_status.stock_id', 'stock_status.website_id = ' . $adapter->quote( $product->getStore(  )->getWebsiteId(  ) ) );
			$select->joinLeft( array( 'stock_status' => $this->getTable( 'cataloginventory/stock_status' ) ), implode( ' AND ', $joinConditions ), array( 'stock_status' ) );
			$select->order( array( 'stock_status.stock_status DESC', '(IF(IF(use_config_manage_stock, ' . $adapter->quote( $manageStock ) . ', manage_stock), is_in_stock, 1)) DESC' ) );
			$select->limit( 1 );
			$row = $adapter->fetchRow( $select );
			$item->setData( $row );
			$this->_afterLoad( $item );
			return $this;
		}

		/**
     * Add join for catalog in stock field to product collection
     *
     * @param Mage_Catalog_Model_Entity_Product_Collection $productCollection
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item
     */
		function addCatalogInventoryToProductCollection($productCollection) {
			$isStockManagedInConfig = (int)Mage::getStoreConfig( XML_PATH_MANAGE_STOCK );
			$stockItemTable = $this->getTable( 'cataloginventory/stock_item' );
			$adapter = $this->_getReadAdapter(  );

			if ($this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  )) {
				$conditionSelect = $adapter->select(  );
				$conditionSelect->from( array( 'item_table_2' => $stockItemTable ), 'stock_id' );
				$conditionSelect->order( array( '(IF(IF(item_table_2.use_config_manage_stock, ' . $adapter->quote( $isStockManagedInConfig ) . ', item_table_2.manage_stock), ' . 'item_table_2.is_in_stock, 1)) DESC' ) );
				$conditionSelect->where( 'item_table_2.product_id = item_table.product_id' );
				$conditionSelect->limit( 1 );
				$stockId = '(' . $conditionSelect->assemble(  ) . ')';
			} 
else {
				$stockId = $this->getWarehouseHelper(  )->getDefaultStockId(  );
			}

			( array( 'item_table' => $stockItemTable ), 'product_id=entity_id', array( 'is_saleable' => new Zend_Db_Expr( $isStockManagedInConfig )( '(IF(
                    IF(item_table.use_config_manage_stock, ' .  . ', item_table.manage_stock), 
                    item_table.is_in_stock, 1
                ))' ), 'inventory_in_stock' => 'is_in_stock' ), 'item_table.stock_id = ' . $stockId, 'left' );
			return $this;
		}
	}

?>