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

	class Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item_Collection extends Mage_CatalogInventory_Model_Mysql4_Stock_Item_Collection {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Join Stock Status to collection
     * 
     * @param int | null $storeId
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item_Collection
     */
		function joinStockStatus($storeId = null) {
			if (( $this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  ) && !$this->hasFlag( 'isStockFilterSet' ) )) {
				$isStockManagedInConfig = (int)Mage::getStoreConfig( XML_PATH_MANAGE_STOCK );
				$websiteId = Mage::app(  )->getStore( $storeId )->getWebsiteId(  );
				$connection = $this->getConnection(  );
				$select = $this->getSelect(  );
				$select->joinLeft( array( 'status_table' => $this->getTable( 'cataloginventory/stock_status' ) ), '(main_table.product_id = status_table.product_id) AND ' . '(main_table.stock_id = status_table.stock_id) AND ' . '(status_table.website_id = ' . $connection->quote( $websiteId ) . ')', array( 'stock_status' ) );
				$conditionSelect = $connection->select(  );
				$conditionSelect->from( array( 'item_table' => $this->getTable( 'cataloginventory/stock_item' ) ), 'item_id' );
				$conditionSelect->joinLeft( array( 'item_status_table' => $this->getTable( 'cataloginventory/stock_status' ) ), '(item_table.product_id=item_status_table.product_id) AND ' . '(item_table.stock_id=item_status_table.stock_id) AND ' . '(item_status_table.website_id=' . $connection->quote( $websiteId ) . ')', array(  ) );
				$conditionSelect->order( array( 'item_status_table.stock_status DESC', '(IF(IF(item_table.use_config_manage_stock, ' . $connection->quote( $isStockManagedInConfig ) . ', item_table.manage_stock), ' . 'item_table.is_in_stock, 1)) DESC' ) );
				$conditionSelect->where( 'item_table.product_id = main_table.product_id' );
				$conditionSelect->limit( 1 );
				$select->where( 'main_table.item_id = (' . $conditionSelect->assemble(  ) . ')' );
				return $this;
			}

			return parent::joinStockStatus( $storeId );
		}

		/**
     * Add in stock filter
     *
     * @param mixed $isStockManagedInConfig
     * 
     * @return  Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item_Collection
     */
		function addInStockFilter($isStockManagedInConfig) {
			$isStockManagedInConfig = (int)$isStockManagedInConfig;
			$this->getSelect(  )->where( 'IF(
            IF(use_config_manage_stock, ' . $this->getConnection(  )->quote( $isStockManagedInConfig ) . ', manage_stock), is_in_stock, 1
        ) = 1' );
			return $this;
		}

		/**
     * Add stock filter to collection
     *
     * @param mixed $stock
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Item_Collection
     */
		function addStockFilter($stock) {
			parent::addStockFilter( $stock );
			$this->setFlag( 'isStockFilterSet', true );
			return $this;
		}
	}

?>