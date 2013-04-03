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

	class Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Indexer_Price_Default extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Default {
		/**
     * Get warehouse helper
     * 
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get price indexer helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer
     */
		function getProductPriceIndexerHelper() {
			return $this->getWarehouseHelper(  )->getProductPriceIndexerHelper(  );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getProductPriceIndexerHelper(  )->getVersionHelper(  );
		}

		/**
     * Prepare products default final price in temporary index table
     *
     * @param int|array $entityIds  the entity ids limitation
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Default
     */
		function _prepareFinalPriceData($entityIds = null) {
			$helper = $this->getWarehouseHelper(  );
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$this->_prepareDefaultFinalPriceTable(  );
			$select = $indexerHelper->getFinalPriceSelect( $write );
			$statusCond = $select->where( 'e.type_id=?', $this->getTypeId(  ) );
			$this->_addAttributeToSelect( $select, 'status', 'e.entity_id', 'cs.store_id', $statusCond, true );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				if (Mage::helper( 'core' )->isModuleEnabled( 'Mage_Tax' )) {
					$taxClassId = $this->_addAttributeToSelect( $select, 'tax_class_id', 'e.entity_id', 'cs.store_id' );
				} 
else {
					$taxClassId = new Zend_Db_Expr( '0' );
				}
			} 
else {
				$taxClassId = $this->_addAttributeToSelect( $select, 'tax_class_id', 'e.entity_id', 'cs.store_id' );
			}

			$select->columns( array( 'tax_class_id' => $taxClassId ) );
			$indexerHelper->addStockJoin( $select );
			$indexerHelper->addTierPriceJoin( $select, 'tp', $this->getTable( 'catalog/product_index_tier_price' ) );
			$price = $this->_addAttributeToSelect( $select, 'price', 'e.entity_id', 'cs.store_id' );
			$indexerHelper->addBatchPriceJoin( $select, 'bp', $indexerHelper->getBatchPriceIndexTable(  ) );
			$price = new Zend_Db_Expr( ( 'IF (bp.price IS NOT NULL, bp.price, ' . $price . ')' ) );

			if ($isMultipleMode) {
				$minPrice = new Zend_Db_Expr( ( 'IF (bp.min_price IS NOT NULL, bp.min_price, ' . $price . ')' ) );
				$maxPrice = new Zend_Db_Expr( ( 'IF (bp.max_price IS NOT NULL, bp.max_price, ' . $price . ')' ) );
			}

			$specialFrom = $this->_addAttributeToSelect( $select, 'special_from_date', 'e.entity_id', 'cs.store_id' );
			$specialTo = $this->_addAttributeToSelect( $select, 'special_to_date', 'e.entity_id', 'cs.store_id' );
			$specialPrice = $this->_addAttributeToSelect( $select, 'special_price', 'e.entity_id', 'cs.store_id' );
			$indexerHelper->addBatchPriceJoin( $select, 'bsp', $indexerHelper->getBatchSpecialPriceIndexTable(  ) );
			$specialPrice = new Zend_Db_Expr( ( 'IF (bsp.price IS NOT NULL, bsp.price, ' . $price . ')' ) );

			if ($isMultipleMode) {
				$specialMinPrice = new Zend_Db_Expr( ( 'IF (bsp.min_price IS NOT NULL, bsp.min_price, ' . $price . ')' ) );
				$specialMaxPrice = new Zend_Db_Expr( ( 'IF (bsp.max_price IS NOT NULL, bsp.max_price, ' . $price . ')' ) );
			}

			$finalPrice = $indexerHelper->getFinalPriceExpr( $write, $price, $specialPrice, $specialFrom, $specialTo );

			if ($isMultipleMode) {
				$finalMinPrice = $indexerHelper->getFinalPriceExpr( $write, $minPrice, $specialMinPrice, $specialFrom, $specialTo );
				$finalMaxPrice = $indexerHelper->getFinalPriceExpr( $write, $maxPrice, $specialMaxPrice, $specialFrom, $specialTo );
			} 
else {
				$finalMinPrice = $price;
				$finalMaxPrice = $price;
			}

			( array( 'orig_price' => $price, 'price' => $finalPrice, 'min_price' => $finalMinPrice, 'max_price' => $finalMaxPrice, 'tier_price' => new Zend_Db_Expr( 'tp.min_price' ), 'base_tier' => new Zend_Db_Expr( 'tp.min_price' ) ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				( array( 'group_price' => new Zend_Db_Expr( 'gp.price' ), 'base_group_price' => new Zend_Db_Expr( 'gp.price' ) ) );
			}

			( array( 'stock_id' => new Zend_Db_Expr( 'cis.stock_id' ) ) );

			if (!is_null( $entityIds )) {
				$select->where( 'e.entity_id IN(?)', $entityIds );
			}

			$eventData = array( 'select' => $select, 'entity_field' => new Zend_Db_Expr( 'e.entity_id' ), 'website_field' => new Zend_Db_Expr( 'cw.website_id' ), 'store_field' => new Zend_Db_Expr( 'cs.store_id' ), 'stock_field' => new Zend_Db_Expr( 'cis.stock_id' ) );
			Mage::dispatchEvent( 'prepare_catalog_product_index_select', $eventData );
			$select->insertFromSelect( $this->_getDefaultFinalPriceTable(  ) );
			$query = $write->quoteInto( '=?', STATUS_ENABLED );
			$write->query( $query );
			$select = $write->select(  )->join( array( 'wd' => $this->_getWebsiteDateTable(  ) ), 'i.website_id = wd.website_id', array(  ) );
			$parameters = array( 'index_table' => array( 'i' => $this->_getDefaultFinalPriceTable(  ) ), 'select' => $select, 'entity_id' => 'i.entity_id', 'customer_group_id' => 'i.customer_group_id', 'website_id' => 'i.website_id', 'stock_id' => 'i.stock_id', 'update_fields' => array( 'price', 'min_price', 'max_price' ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$parameters['website_date'] = 'wd.website_date';
			} 
else {
				$parameters['website_date'] = 'wd.date';
			}

			Mage::dispatchEvent( 'prepare_catalog_product_price_index_table', $parameters );
			return $this;
		}

		/**
     * Apply custom option minimal and maximal price to temporary final price index table
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Default
     */
		function _applyCustomOption() {
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$copTable = $coaTable = $write = $this->_getWriteAdapter(  );
			$finalPriceTable = $this->_getDefaultFinalPriceTable(  );
			$this->_prepareCustomOptionAggregateTable(  );
			$indexerHelper->getOptionTypePriceSelect( $write, $finalPriceTable );
			$select = $this->_getCustomOptionAggregateTable(  );
			$query = $this->_getCustomOptionPriceTable(  );
			$select = $indexerHelper->getOptionPriceSelect( $write, $finalPriceTable );
			$select->insertFromSelect( $coaTable );
			$query = $this->_prepareCustomOptionPriceTable(  );
			$write->query( $query );
			$select = $select->insertFromSelect( $coaTable );
			$select->insertFromSelect( $copTable );
			$query = $write->query( $query );
			$write->query( $query );
			$table = array( 'i' => $finalPriceTable );
			$select = $indexerHelper->getOptionFinalPriceSelect( $write, $copTable );
			$select->crossUpdateFromSelect( $table );
			$query = $indexerHelper->getAggregatedOptionPriceSelect( $write, $coaTable );
			$write->query( $query );

			if ($this->getVersionHelper(  )->isGe1620(  )) {
				$write->delete( $coaTable );
				$write->delete( $copTable );
			} 
else {
				if ($this->useIdxTable(  )) {
					$write->truncate( $coaTable );
					$write->truncate( $copTable );
				} 
else {
					$write->delete( $coaTable );
					$write->delete( $copTable );
				}
			}

			return $this;
		}

		/**
     * Mode final prices index to primary temporary index table
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price_Default
     */
		function _movePriceDataToIndexTable() {
			$this->getProductPriceIndexerHelper(  );
			$write = $columns = $indexerHelper->getPriceSelectColumns(  );
			$table = $indexerHelper = $this->_getDefaultFinalPriceTable(  );
			$write->select(  )->from( $table, $columns );
			$select = $this->_getWriteAdapter(  );
			$query = $select->insertFromSelect( $this->getIdxTable(  ) );
			$write->query( $query );

			if ($this->getVersionHelper(  )->isGe1620(  )) {
				$write->delete( $table );
			} 
else {
				if ($this->useIdxTable(  )) {
					$write->truncate( $table );
				} 
else {
					$write->delete( $table );
				}
			}

			return $this;
		}
	}

?>