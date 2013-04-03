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

	require_once( rtrim( dirname( __FILE__ ), '/' ) . '/../../../../Core/Importer.php' );
	class Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer extends Innoexts_Shell_Core_Importer {
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
     * Get batch price table name
     * 
     * @return string
     */
		function getBatchPriceTableName() {
			return 'catalog/product_batch_price';
		}

		/**
     * Get batch special price table name
     * 
     * @return string
     */
		function getBatchSpecialPriceTableName() {
			return 'catalog/product_batch_special_price';
		}

		/**
     * Get batch price table 
     * 
     * @return string
     */
		function getBatchPriceTable() {
			return $this->getResource(  )->getTable( $this->getBatchPriceTableName(  ) );
		}

		/**
     * Get batch special price table 
     * 
     * @return string
     */
		function getBatchSpecialPriceTable() {
			return $this->getResource(  )->getTable( $this->getBatchSpecialPriceTableName(  ) );
		}

		/**
     * Get batch pricing conditions
     * 
     * @param array $batchPrice
     * @return string
     */
		function getBatchPriceConditions($batchPrice) {
			$adapter = $this->getWriteAdapter(  );
			return implode( ' AND ', array( ( '(product_id    = ' . $adapter->quote( $batchPrice['product_id'] ) . ')' ), ( '(stock_id      = ' . $adapter->quote( $batchPrice['stock_id'] ) . ')' ), ( '(website_id    = ' . $adapter->quote( $batchPrice['website_id'] ) . ')' ) ) );
		}

		/**
     * Check if batch price exists
     * 
     * @param array $batchPrice
     * @param string $table
     * @return bool
     */
		function _isBatchPriceExists($batchPrice, $table) {
			$this->getWriteAdapter(  );
			$adapter->select(  )->from( $table, array( 'COUNT(*)' ) )->where( $this->getBatchPriceConditions( $batchPrice ) );
			$select = $isExists = false;
			$query = $adapter = $adapter->query( $select );
			$count = (int)$query->fetchColumn(  );

			if ($count) {
				$isExists = true;
			}

			return $isExists;
		}

		/**
     * Check if batch price exists
     * 
     * @param array $batchPrice
     * @return bool
     */
		function isBatchPriceExists($batchPrice) {
			return $this->_isBatchPriceExists( $batchPrice, $this->getBatchPriceTable(  ) );
		}

		/**
     * Check if batch special price exists
     * 
     * @param array $batchPrice
     * @return bool
     */
		function isBatchSpecialPriceExists($batchPrice) {
			return $this->_isBatchPriceExists( $batchPrice, $this->getBatchSpecialPriceTable(  ) );
		}

		/**
     * Add batch price
     * 
     * @param array $batchPrice
     * @param string $table
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function _addBatchPrice($batchPrice, $table) {
			$adapter = $this->getWriteAdapter(  );
			$adapter->insert( $table, $batchPrice );
			return $this;
		}

		/**
     * Add batch price
     * 
     * @param array $batchPrice
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function addBatchPrice($batchPrice) {
			return $this->_addBatchPrice( $batchPrice, $this->getBatchPriceTable(  ) );
		}

		/**
     * Add batch special price
     * 
     * @param array $batchPrice
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function addBatchSpecialPrice($batchPrice) {
			return $this->_addBatchPrice( $batchPrice, $this->getBatchSpecialPriceTable(  ) );
		}

		/**
     * Update batch price
     * 
     * @param array $batchPrice
     * @param string $table
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function _updateBatchPrice($batchPrice, $table) {
			$adapter = $this->getWriteAdapter(  );
			$adapter->update( $table, $batchPrice, $this->getBatchPriceConditions( $batchPrice ) );
			return $this;
		}

		/**
     * Update batch price
     * 
     * @param array $batchPrice
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function updateBatchPrice($batchPrice) {
			return $this->_updateBatchPrice( $batchPrice, $this->getBatchPriceTable(  ) );
		}

		/**
     * Update batch special price
     * 
     * @param array $batchPrice
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function updateBatchSpecialPrice($batchPrice) {
			return $this->_updateBatchPrice( $batchPrice, $this->getBatchSpecialPriceTable(  ) );
		}

		/**
     * Append batch price
     * 
     * @param array $batchPrice
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer  
     */
		function appendBatchPrice($batchPrice) {
			if ($this->isBatchPriceExists( $batchPrice )) {
				$this->updateBatchPrice( $batchPrice );
			} 
else {
				$this->addBatchPrice( $batchPrice );
			}

			return $this;
		}

		/**
     * Append batch special price
     * 
     * @param array $batchPrice
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer  
     */
		function appendBatchSpecialPrice($batchPrice) {
			if ($this->isBatchSpecialPriceExists( $batchPrice )) {
				$this->updateBatchSpecialPrice( $batchPrice );
			} 
else {
				$this->addBatchSpecialPrice( $batchPrice );
			}

			return $this;
		}

		/**
     * Reindex
     * 
     * @return Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer
     */
		function reindex() {
			$this->printMessage( 'Reindexing.' );
			$productPriceProcess = Mage::getSingleton( 'index/indexer' )->getProcessByCode( 'catalog_product_price' );

			if ($productPriceProcess) {
				$productPriceProcess->reindexAll(  );
			}

			return $this;
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
						$wensiteId = (( isset( $row['website'] ) && $row['website'] ) ? $row['website'] : 0);

						if ($wensiteId) {
							$wensiteId = Mage::app(  )->getWebsite( $wensiteId )->getId(  );
						} 
else {
							$wensiteId = 94;
						}

						foreach ($stockIds as $stockId) {
							$code = $helper->getWarehouseCodeByStockId( $stockId );

							if ($code) {
								$price = null;
								$priceKey = 'price_' . $code;
								$priceKey2 = 'price_' . $stockId;

								if (( isset( $row[$priceKey] ) && $row[$priceKey] )) {
									$price = (double)$row[$priceKey];
								} 
else {
									if (( isset( $row[$priceKey2] ) && $row[$priceKey2] )) {
										$price = (double)$row[$priceKey2];
									}
								}


								if (!is_null( $price )) {
									$batchPrice = array( 'product_id' => $productId, 'stock_id' => $stockId, 'website_id' => $wensiteId, 'price' => $price );
									$this->appendBatchPrice( $batchPrice );
								}

								$specialPrice = null;
								$specialPriceKey = 'special_price_' . $code;
								$specialPriceKey2 = 'special_price_' . $stockId;

								if (( isset( $row[$specialPriceKey] ) && $row[$specialPriceKey] )) {
									$specialPrice = (double)$row[$specialPriceKey];
								} 
else {
									if (( isset( $row[$specialPriceKey2] ) && $row[$specialPriceKey2] )) {
										$specialPrice = (double)$row[$specialPriceKey2];
									}
								}


								if (!is_null( $specialPrice )) {
									$batchSpecialPrice = array( 'product_id' => $productId, 'stock_id' => $stockId, 'website_id' => $wensiteId, 'price' => $specialPrice );
									$this->appendBatchSpecialPrice( $batchSpecialPrice );
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

	$shell = new Innoexts_Shell_Warehouse_Catalog_Product_Price_Importer(  );
	$shell->run(  );
?>