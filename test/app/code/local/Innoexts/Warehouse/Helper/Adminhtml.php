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

	class Innoexts_Warehouse_Helper_Adminhtml extends Mage_Core_Helper_Abstract {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Add column relation to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addColumnRelationToCollection($collection, $column) {
			if (!$column->getRelation(  )) {
				return $this;
			}

			$relation = ;
			$column->getId(  );
			$relation['field_name'];
			$relation['fk_field_name'];
			$refFieldName = $fieldName = $relation['ref_field_name'];
			$relation['table_alias'];
			$collection->getTable( $relation['table_name'] );
			$table = $tableAlias = $fkFieldName = $fieldAlias = $column->getRelation(  );
			$collection->addFilterToMap( $fieldAlias, $tableAlias . '.' . $fieldName );
			$collection->getSelect(  )->joinLeft( array( $tableAlias => $table ), '(main_table.' . $fkFieldName . ' = ' . $tableAlias . '.' . $refFieldName . ')', array( $fieldAlias => $tableAlias . '.' . $fieldName ) );
			return $this;
		}

		/**
     * Add column relation to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addColumnRelationDataToCollection($collection, $column) {
			if (( ( !$collection || !$column ) || !$column->getRelation(  ) )) {
				return $this;
			}

			$relation = $column->getRelation(  );
			$fkFieldName = $relation['fk_field_name'];
			$refFieldName = $relation['ref_field_name'];
			$fieldName = $relation['field_name'];
			$tableName = $relation['table_name'];
			$table = $collection->getTable( $tableName );
			$modelValues = array(  );
			foreach ($collection as $model) {
				$modelValues[$model->getData( $fkFieldName )] = array(  );
			}


			if (count( $modelValues )) {
				$adapter = $collection->getConnection(  );
				$select = $adapter->select(  )->from( $table )->where( $adapter->quoteInto( $fkFieldName . ' IN (?)', array_keys( $modelValues ) ) );
				$items = $adapter->fetchAll( $select );
				foreach ($items as $item) {
					$modelId = $item[$refFieldName];
					$value = $item[$fieldName];
					$modelValues[$modelId][] = $value;
				}
			}

			foreach ($collection as $model) {
				$modelId = $model->getData( $fkFieldName );

				if (isset( $modelValues[$modelId] )) {
					$model->setData( $column->getId(  ), $modelValues[$modelId] );
					continue;
				}
			}

			return $this;
		}

		/**
     * Get column filter to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addColumnFilterToCollection($collection, $column) {
			$this->addColumnRelationToCollection( $collection, $column );
			$field = ($column->getFilterIndex(  ) ? $column->getFilterIndex(  ) : $column->getIndex(  ));
			$condition = $column->getFilter(  )->getCondition(  );

			if (( $field && empty( $$condition ) )) {
				$collection->addFieldToFilter( $field, $condition );
			}

			return $this;
		}

		/**
     * Prepare order grid
     * 
     * @param Mage_Adminhtml_Block_Sales_Order_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function prepareOrderGrid($grid) {
			$columnId = 'stock_ids';
			$this->addColumnRelationDataToCollection( $grid->getCollection(  ), $grid->getColumn( $columnId ) );
			$grid->addColumnsOrder( $columnId, 'status' );
			$grid->sortColumnsByOrder(  );
			return $this;
		}

		/**
     * Add stock order grid column
     * 
     * @param Mage_Adminhtml_Block_Sales_Order_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addStockOrderGridColumn($grid) {
			$helper = $this->getWarehouseHelper(  );
			$options = $helper->getStocksHash(  );
			$grid->addColumn( 'stock_ids', array( 'header' => $helper->__( 'Warehouses' ), 'sortable' => false, 'index' => 'stock_ids', 'type' => 'options', 'options' => $options, 'filter_condition_callback' => array( $this, 'addColumnFilterToCollection' ), 'relation' => array( 'table_alias' => 'stock_ids_table', 'table_name' => 'warehouse/order_grid_warehouse', 'fk_field_name' => 'entity_id', 'ref_field_name' => 'entity_id', 'field_name' => 'stock_id' ) ) );
			return $this;
		}

		/**
     * Prepare invoice grid
     * 
     * @param Mage_Adminhtml_Block_Sales_Invoice_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function prepareInvoiceGrid($grid) {
			$columnId = 'stock_ids';
			$this->addColumnRelationDataToCollection( $grid->getCollection(  ), $grid->getColumn( $columnId ) );
			$grid->addColumnsOrder( $columnId, 'grand_total' );
			$grid->sortColumnsByOrder(  );
			return $this;
		}

		/**
     * Add stock invoice grid column
     * 
     * @param Mage_Adminhtml_Block_Sales_Invoice_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addStockInvoiceGridColumn($grid) {
			$helper = $this->getWarehouseHelper(  );
			$options = $helper->getStocksHash(  );
			$grid->addColumn( 'stock_ids', array( 'header' => $helper->__( 'Warehouses' ), 'sortable' => false, 'index' => 'stock_ids', 'type' => 'options', 'options' => $options, 'filter_condition_callback' => array( $this, 'addColumnFilterToCollection' ), 'relation' => array( 'table_alias' => 'stock_ids_table', 'table_name' => 'warehouse/invoice_grid_warehouse', 'fk_field_name' => 'entity_id', 'ref_field_name' => 'entity_id', 'field_name' => 'stock_id' ) ) );
			return $this;
		}

		/**
     * Prepare shipment grid
     * 
     * @param Mage_Adminhtml_Block_Sales_Shipment_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function prepareShipmentGrid($grid) {
			$columnId = 'stock_ids';
			$this->addColumnRelationDataToCollection( $grid->getCollection(  ), $grid->getColumn( $columnId ) );
			$grid->addColumnsOrder( $columnId, 'total_qty' );
			$grid->sortColumnsByOrder(  );
			return $this;
		}

		/**
     * Add stock shipment grid column
     * 
     * @param Mage_Adminhtml_Block_Sales_Shipment_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addStockShipmentGridColumn($grid) {
			$helper = $this->getWarehouseHelper(  );
			$options = $helper->getStocksHash(  );
			$grid->addColumn( 'stock_ids', array( 'header' => $helper->__( 'Warehouses' ), 'sortable' => false, 'index' => 'stock_ids', 'type' => 'options', 'options' => $options, 'filter_condition_callback' => array( $this, 'addColumnFilterToCollection' ), 'relation' => array( 'table_alias' => 'stock_ids_table', 'table_name' => 'warehouse/shipment_grid_warehouse', 'fk_field_name' => 'entity_id', 'ref_field_name' => 'entity_id', 'field_name' => 'stock_id' ) ) );
			return $this;
		}

		/**
     * Prepare creditmemo grid
     * 
     * @param Mage_Adminhtml_Block_Sales_Creditmemo_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function prepareCreditmemoGrid($grid) {
			$columnId = 'stock_ids';
			$this->addColumnRelationDataToCollection( $grid->getCollection(  ), $grid->getColumn( $columnId ) );
			$grid->addColumnsOrder( $columnId, 'grand_total' );
			$grid->sortColumnsByOrder(  );
			return $this;
		}

		/**
     * Add stock creditmemo grid column
     * 
     * @param Mage_Adminhtml_Block_Sales_Creditmemo_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addStockCreditmemoGridColumn($grid) {
			$helper = $this->getWarehouseHelper(  );
			$options = $helper->getStocksHash(  );
			$grid->addColumn( 'stock_ids', array( 'header' => $helper->__( 'Warehouses' ), 'sortable' => false, 'index' => 'stock_ids', 'type' => 'options', 'options' => $options, 'filter_condition_callback' => array( $this, 'addColumnFilterToCollection' ), 'relation' => array( 'table_alias' => 'stock_ids_table', 'table_name' => 'warehouse/creditmemo_grid_warehouse', 'fk_field_name' => 'entity_id', 'ref_field_name' => 'entity_id', 'field_name' => 'stock_id' ) ) );
			return $this;
		}

		/**
     * Add column qty data to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addColumnQtyDataToCollection($collection, $column) {
			$helper = $this->getWarehouseHelper(  );
			$stockIds = $helper->getStockIds(  );
			$qtys = array(  );
			foreach ($collection as $product) {
				$productId = (int)$product->getId(  );
				$qtys[$productId] = array(  );
			}


			if (!empty( $$qtys )) {
				$adapter = $collection->getConnection(  );
				$table = $collection->getTable( 'cataloginventory/stock_item' );
				$select = $adapter->select(  )->from( $table )->where( $adapter->quoteInto( 'product_id IN (?)', array_keys( $qtys ) ) );
				$data = $adapter->fetchAll( $select );
				foreach ($data as $row) {
					$productId = (int)$row['product_id'];
					$stockId = (int)$row['stock_id'];
					$qty = (double)$row['qty'];
					$qtys[$productId][$stockId] = $qty;
				}

				foreach ($qtys as $productId => $productQtys) {
					foreach ($stockIds as $stockId) {

						if (!isset( $productQtys[$stockId] )) {
							$qtys[$productId][$stockId] = 0;
							continue;
						}
					}
				}
			}

			foreach ($collection as $product) {
				$productId = (int)$product->getId(  );

				if (isset( $qtys[$productId] )) {
					$product->setData( $column->getId(  ), $qtys[$productId] );
					continue;
				}
			}

			return $this;
		}

		/**
     * Add column batch price data to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addColumnBatchPriceDataToCollection($collection, $column) {
			$helper = $this->getWarehouseHelper(  );
			$priceHelper = $helper->getProductPriceHelper(  );
			$stockIds = $helper->getStockIds(  );
			foreach ($collection as $product) {
				$batchPrices = array(  );
				foreach ($stockIds as $stockId) {
					$batchPrice = $priceHelper->getWebsiteBatchPriceByStockId( $product, $stockId );

					if (is_null( $batchPrice )) {
						$batchPrice = $product->getPrice(  );
					}

					$batchPrices[$stockId] = $batchPrice;
				}

				$product->setBatchPrices( $batchPrices );
			}

			return $this;
		}

		/**
     * Get column qty filter to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addColumnQtyFilterToCollection($collection, $column) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );

			if (!$config->isCatalogBackendGridQtyVisible(  )) {
				return $this;
			}

			$adapter = $collection->getConnection(  );
			$condition = $column->getFilter(  )->getCondition(  );
			$select = $collection->getSelect(  );
			$qtys = 'SUM(si.qty)';
			$table = $collection->getTable( 'cataloginventory/stock_item' );
			$select->joinInner( array( 'si' => $table ), '(e.entity_id = si.product_id)', array( 'qtys' => $qtys ) );
			$select->group( array( 'e.entity_id' ) );
			$conditionPieces = array(  );

			if (isset( $condition['from'] )) {
				array_push( $conditionPieces, $qtys . ' >= ' . $adapter->quote( $condition['from'] ) );
			}


			if (isset( $condition['to'] )) {
				array_push( $conditionPieces, $qtys . ' <= ' . $adapter->quote( $condition['to'] ) );
			}


			if (count( $conditionPieces )) {
				$select->having( implode( ' AND ', $conditionPieces ) );
			}

			return $this;
		}

		/**
     * Add qty product grid column
     * 
     * @param Mage_Adminhtml_Block_Catalog_Product_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addQtyProductGridColumn($grid) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );

			if (!$config->isCatalogBackendGridQtyVisible(  )) {
				return $this;
			}

			$grid->addColumn( 'qtys', array( 'header' => $helper->__( 'Qty' ), 'sortable' => false, 'index' => 'qtys', 'width' => '140px', 'align' => 'left', 'renderer' => 'warehouse/adminhtml_catalog_product_grid_column_renderer_qtys', 'filter_condition_callback' => array( $this, 'addColumnQtyFilterToCollection' ), 'filter' => 'adminhtml/widget_grid_column_filter_range' ) );
			return $this;
		}

		/**
     * Add batch price product grid column
     * 
     * @param Mage_Adminhtml_Block_Catalog_Product_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addBatchPriceProductGridColumn($grid) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );

			if (!$config->isCatalogBackendGridBatchPricesVisible(  )) {
				return $this;
			}

			$store = Mage::app(  )->getStore( $storeId );
			$store->getBaseCurrency(  );
			$baseCurrency = $storeId = (int)$grid->getRequest(  )->getParam( 'store', 0 );
			$grid->addColumn( 'batch_prices', array( 'header' => $helper->__( 'Batch Price' ), 'currency_code' => $baseCurrency->getCode(  ), 'index' => 'batch_prices', 'width' => '140px', 'align' => 'left', 'renderer' => 'warehouse/adminhtml_catalog_product_grid_column_renderer_batchprices', 'filter' => false, 'sortable' => false ) );
			return $this;
		}

		/**
     * Prepare product grid
     * 
     * @param Mage_Adminhtml_Block_Catalog_Product_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function prepareProductGrid($grid) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );

			if ($helper->getVersionHelper(  )->isGe1600(  )) {
				$grid->removeColumn( 'qty' );
			}


			if ($config->isCatalogBackendGridQtyVisible(  )) {
				$qtyColumnId = 'qtys';
				$this->addColumnQtyDataToCollection( $grid->getCollection(  ), $grid->getColumn( $qtyColumnId ) );
				$grid->addColumnsOrder( $qtyColumnId, 'price' );
			}


			if ($config->isCatalogBackendGridBatchPricesVisible(  )) {
				$batchPricesColumnId = 'batch_prices';
				$this->addColumnBatchPriceDataToCollection( $grid->getCollection(  ), $grid->getColumn( $batchPricesColumnId ) );
				$grid->addColumnsOrder( $batchPricesColumnId, 'price' );
			}

			$grid->sortColumnsByOrder(  );
			return $this;
		}

		/**
     * Before load product lowstock collection
     * 
     * @param Mage_Reports_Model_Mysql4_Product_Lowstock_Collection $collection
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function beforeLoadProductLowstockCollection($collection) {
			$helper = $this->getWarehouseHelper(  );
			$inventoryHelper = $helper->getCatalogInventoryHelper(  );
			$select = $collection->getSelect(  );
			$stockIds = $helper->getStockIds(  );
			foreach ($stockIds as $stockId) {
				$qtyFieldName = 'qty_' . $stockId;
				$collection->joinField( $qtyFieldName, 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', ( '{{table}}.stock_id = \'' . $stockId . '\'' ), 'left' );
			}

			$queryPieces = array(  );
			foreach ($stockIds as $stockId) {
				$stockItemTableAlias = 'at_qty_' . $stockId;
				array_push( $queryPieces, sprintf( '(IF(%s, %d, %s)=1)', $stockItemTableAlias . '.use_config_manage_stock', $inventoryHelper->getManageStock(  ), $stockItemTableAlias . '.manage_stock' ) );
			}


			if (count( $queryPieces )) {
				$select->where( implode( ' OR ', $queryPieces ) );
			}

			$queryPieces = array(  );
			foreach ($stockIds as $stockId) {
				$stockItemTableAlias = 'at_qty_' . $stockId;
				array_push( $queryPieces, sprintf( '(%s < IF(%s, %d, %s))', $stockItemTableAlias . '.qty', $stockItemTableAlias . '.use_config_notify_stock_qty', $inventoryHelper->getNotifyStockQty(  ), $stockItemTableAlias . '.notify_stock_qty' ) );
			}


			if (count( $queryPieces )) {
				$select->where( implode( ' OR ', $queryPieces ) );
			}

			$collection->setOrder( 'sku', 'asc' );
			return $this;
		}

		/**
     * Add qty product lowstock grid column filters to collection
     * 
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
		function addQtyProductLowstockGridColumnFiltersToCollection($collection, $column) {
			$field = ($column->getFilterIndex(  ) ? $column->getFilterIndex(  ) : $column->getIndex(  ));
			$condition = $column->getFilter(  )->getCondition(  );

			if (( $field && empty( $$condition ) )) {
				$adapter = $collection->getConnection(  );
				$sql = $adapter->prepareSqlCondition( 'at_' . $field . '.qty', $condition );
				$collection->getSelect(  )->where( $sql );
			}

			return $this;
		}

		/**
     * Add product lowstock grid columns
     * 
     * @param Mage_Adminhtml_Block_Report_Product_Lowstock_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
			$helper = function addQtyProductLowstockGridColumns($grid) {;
			$helper->getStockIds(  );
			$stockIds = $this->getWarehouseHelper(  );
			foreach ($stockIds as $stockId) {
				$fieldName = 'qty_' . $stockId;
				$grid->addColumn( $fieldName, array( 'header' => sprintf( $helper->__( '%s Qty' ), $helper->getWarehouseTitleByStockId( $stockId ) ), 'align' => 'right', 'sortable' => false, 'filter' => 'adminhtml/widget_grid_column_filter_range', 'filter_condition_callback' => array( $this, 'addQtyProductLowstockGridColumnFiltersToCollection' ), 'stock_id' => $stockId, 'index' => $fieldName, 'type' => 'number' ) );
			}

			return $this;
		}

		/**
     * Prepare product lowstock grid
     * 
     * @param Mage_Adminhtml_Block_Report_Product_Lowstock_Grid $grid
     * 
     * @return Innoexts_Warehouse_Helper_Adminhtml
     */
			$helper = function prepareProductLowstockGrid($grid) {;
			$helper->getStockIds(  );
			$stockIds = $this->getWarehouseHelper(  );

			if ($helper->getVersionHelper(  )->isGe1600(  )) {
				$grid->removeColumn( 'qty' );
			}

			$prevColumn = 'sku';
			foreach ($stockIds as $stockId) {
				$fieldName = 'qty_' . $stockId;
				$grid->addColumnsOrder( $fieldName, $prevColumn );
				$prevColumn = $helper;
			}

			$grid->sortColumnsByOrder(  );
			return $this;
		}
	}

?>