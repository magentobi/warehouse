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

	class Innoexts_Shell_Warehouse_Cataloginventory_Stock_Item_Importer extends Innoexts_Shell_Core_Importer {
		private $_product = null;

		/**
     * Retrieve warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get product
     *
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			if (is_null( $this->_product )) {
				$this->_product = Mage::getModel( 'catalog/product' );
			}

			return $this->_product;
		}

		/**
     * Get resource
     * 
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product
     */
		function getResource() {
			return $this->getProduct(  )->getResource(  );
		}

		/**
     * Get adapter
     * 
     * @return Varien_Db_Adapter_Interface
     */
		function getWriteAdapter() {
			return $this->getResource(  )->getWriteConnection(  );
		}

		/**
     * Get select
     * 
     * @return Varien_Db_Select
     */
		function getSelect() {
			return $this->getWriteAdapter(  )->select(  );
		}

		/**
     * Get stock item table name
     * 
     * @return string
     */
		function getStockItemTableName() {
			return 'cataloginventory/stock_item';
		}

		/**
     * Get stock item table
     * 
     * @return string
     */
		function getStockItemTable() {
			return $this->getResource(  )->getTable( $this->getStockItemTableName(  ) );
		}

		/**
     * Get stock item conditions
     * 
     * @param array $stockItem
     * @return string
     */
		function getStockItemConditions($stockItem) {
			$adapter = $this->getWriteAdapter(  );
			return implode( ' AND ', array( ( '(product_id    = ' . $adapter->quote( $stockItem['product_id'] ) . ')' ), ( '(stock_id      = ' . $adapter->quote( $stockItem['stock_id'] ) . ')' ) ) );
		}

		/**
     * Check if stock item exists
     * 
     * @param array $stockItem
     * @return bool
     */
		function isStockItemExists($stockItem) {
			$isExists = false;
			$adapter = $this->getWriteAdapter(  );
			$select = $adapter->select(  )->from( $this->getStockItemTable(  ), array( 'COUNT(*)' ) )->where( $this->getStockItemConditions( $stockItem ) );
			$query = $adapter->query( $select );
			$count = (int)$query->fetchColumn(  );

			if ($count) {
				$isExists = true;
			}

			return $isExists;
		}

		/**
     * Add stock item
     * 
     * @param array $stockItem
     * @return Innoexts_Shell_Warehouse_Cataloginventory_Stock_Item_Importer
     */
		function addStockItem($stockItem) {
			$adapter = $this->getWriteAdapter(  );
			$adapter->insert( $this->getStockItemTable(  ), $stockItem );
			return $this;
		}

		/**
     * Update stock item
     * 
     * @param array $stockItem
     * @return Innoexts_Shell_Warehouse_Cataloginventory_Stock_Item_Importer
     */
		function updateStockItem($stockItem) {
			$adapter = $this->getWriteAdapter(  );
			$adapter->update( $this->getStockItemTable(  ), $stockItem, $this->getStockItemConditions( $stockItem ) );
			return $this;
		}

		/**
     * Append stock item
     * 
     * @param array $stockItem
     * @return Innoexts_Shell_Warehouse_Cataloginventory_Stock_Item_Importer
     */
		function appendStockItem($stockItem) {
			if ($this->isStockItemExists( $stockItem )) {
				$this->updateStockItem( $stockItem );
			} 
else {
				$this->addStockItem( $stockItem );
			}

			return $this;
		}

		/**
     * Reindex
     * 
     * @return Innoexts_Shell_Warehouse_Cataloginventory_Stock_Item_Importer
     */
		function reindex() {
			$this->printMessage( 'Reindexing.' );
			$stockProcess = Mage::getSingleton( 'index/indexer' )->getProcessByCode( 'cataloginventory_stock' );

			if ($stockProcess) {
				$stockProcess->reindexAll(  );
			}

			return $this;
		}

		/**
     * Get stock item attributes
     * 
     * @return array
     */
		function getStockItemAttributes() {
			return array( 'qty', 'use_config_min_qty', 'min_qty', 'is_qty_decimal', 'use_config_backorders', 'backorders', 'use_config_min_sale_qty', 'min_sale_qty', 'use_config_max_sale_qty', 'max_sale_qty', 'is_in_stock', 'use_config_notify_stock_qty', 'notify_stock_qty', 'use_config_manage_stock', 'manage_stock', 'use_config_qty_increments', 'qty_increments', 'use_config_enable_qty_inc', 'enable_qty_increments', 'qty', 'is_in_stock', 'use_config_manage_stock', 'manage_stock', 'use_config_backorders', 'backorders' );
		}

		/**
     * Import row
     * 
     * @param array $row
     * @return bool
     */
		function importRow($row) {
			$helper = $this->getWarehouseHelper(  );
			$isImported = false;
			$sku = null;

			if (( isset( $row['sku'] ) && $row['sku'] )) {
				$sku = $row['sku'];
			}


			if ($sku) {
				$product = $this->getProduct(  );
				$productId = $product->getIdBySku( $sku );

				if ($productId) {
					$stockIds = $helper->getStockIds(  );

					if (count( $stockIds )) {
						$stockItemAttributes = $this->getStockItemAttributes(  );
						foreach ($stockIds as $stockId) {
							$code = $helper->getWarehouseCodeByStockId( $stockId );

							if ($code) {
								$stockItem = array(  );
								foreach ($stockItemAttributes as $attribute) {
									$key = $attribute . '_' . $code;

									if (isset( $row[$key] )) {
										$stockItem[$attribute] = $row[$key];
										continue;
									}
								}


								if (count( $stockItem )) {
									$stockItem = array_merge( array( 'product_id' => $productId, 'stock_id' => $stockId ), $stockItem );
									$this->appendStockItem( $stockItem );
									continue;
								}

								continue;
							}
						}
					}
				} 
else {
					$this->printMessage( 'Can\'t find product by sku: ' . $sku );
				}
			}

			return $isImported;
		}
	}

	new Innoexts_Shell_Warehouse_Cataloginventory_Stock_Item_Importer(  );
	$shell = require_once( rtrim( dirname( __FILE__ ), '/' ) . '/../../../../Core/Importer.php' );
	$shell->run(  );
?>