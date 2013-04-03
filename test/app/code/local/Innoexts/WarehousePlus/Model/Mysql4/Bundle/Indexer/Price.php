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

	class Innoexts_WarehousePlus_Model_Mysql4_Bundle_Indexer_Price extends Innoexts_Warehouse_Model_Mysql4_Bundle_Indexer_Price {
		/**
     * Prepare temporary price index data for bundle products by price type
     *
     * @param int $priceType
     * @param int|array $entityIds the entity ids limitatation
     * 
     * @return Mage_Bundle_Model_Mysql4_Indexer_Price
     */
		function _prepareBundlePriceByType($priceType, $entityIds = null) {
			$helper = $this->getWarehouseHelper(  );
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$table = $this->_getBundlePriceTable(  );
			$select = $indexerHelper->getFinalPriceSelect( $write );
			$select->where( 'e.type_id=?', $this->getTypeId(  ) );
			$statusCond = $write->quoteInto( '=?', STATUS_ENABLED );
			$this->_addAttributeToSelect( $select, 'status', 'e.entity_id', 'cs.store_id', $statusCond, true );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				if (Mage::helper( 'core' )->isModuleEnabled( 'Mage_Tax' )) {
					$taxClassId = $this->_addAttributeToSelect( $select, 'tax_class_id', 'e.entity_id', 'cs.store_id' );
				} 
else {
					$taxClassId = new Zend_Db_Expr( '0' );
				}


				if ($priceType == PRICE_TYPE_DYNAMIC) {
					( array( 'tax_class_id' => new Zend_Db_Expr( '0' ) ) );
				} 
else {
					$select->columns( array( 'tax_class_id' => $write->getCheckSql( $taxClassId . ' IS NOT NULL', $taxClassId, 0 ) ) );
				}
			} 
else {
				if ($priceType == PRICE_TYPE_DYNAMIC) {
					( array( 'tax_class_id' => new Zend_Db_Expr( '0' ) ) );
				} 
else {
					$taxClassId = $this->_addAttributeToSelect( $select, 'tax_class_id', 'e.entity_id', 'cs.store_id' );
					( array( 'tax_class_id' => new Zend_Db_Expr( 'IF(' . $taxClassId . ' IS NOT NULL, ' . $taxClassId . ', 0)' ) ) );
				}
			}

			$priceTypeCond = $write->quoteInto( '=?', $priceType );
			$this->_addAttributeToSelect( $select, 'price_type', 'e.entity_id', 'cs.store_id', $priceTypeCond );
			$indexerHelper->addStockJoin( $select );
			$indexerHelper->addCurrencyRateJoin( $select );
			$indexerHelper->addTierPriceJoin( $select, 'tp', $this->getTable( 'catalog/product_index_tier_price' ) );
			$price = $this->_addAttributeToSelect( $select, 'price', 'e.entity_id', 'cs.store_id' );
			$indexerHelper->addBatchPriceJoin( $select, 'bp', $indexerHelper->getBatchPriceIndexTable(  ) );
			$price = new Zend_Db_Expr( ( 'IF (bp.price IS NOT NULL, bp.price, ' . $price . ')' ) );

			if ($isMultipleMode) {
				$minPrice = new Zend_Db_Expr( ( 'IF (bp.min_price IS NOT NULL, bp.min_price, ' . $price . ')' ) );
				$maxPrice = new Zend_Db_Expr( ( 'IF (bp.max_price IS NOT NULL, bp.max_price, ' . $price . ')' ) );
			}

			$origPrice = $indexerHelper->getBundleOrigPriceExpr( $write, $price );
			$specialFrom = $this->_addAttributeToSelect( $select, 'special_from_date', 'e.entity_id', 'cs.store_id' );
			$specialTo = $this->_addAttributeToSelect( $select, 'special_to_date', 'e.entity_id', 'cs.store_id' );
			$specialPrice = $this->_addAttributeToSelect( $select, 'special_price', 'e.entity_id', 'cs.store_id' );
			$specialExpr = $indexerHelper->getBundleSpecialPriceExpr( $write, $specialPrice, $specialFrom, $specialTo );
			$tierExpr = $indexerHelper->getBundleTierExpr( $write );
			$tierPrice = $indexerHelper->getBundleTierPriceExpr( $write, $priceType, $price );
			$groupPrice = $indexerHelper->getBundleGroupPriceExpr( $write, $priceType, $price );

			if ($isMultipleMode) {
				$groupMinPrice = $indexerHelper->getBundleGroupPriceExpr( $write, $priceType, $minPrice );
				$groupMaxPrice = $indexerHelper->getBundleGroupPriceExpr( $write, $priceType, $maxPrice );
			}

			$finalPrice = $indexerHelper->getBundleFinalPriceExpr( $write, $priceType, $price, $specialExpr, $groupPrice );

			if ($isMultipleMode) {
				$finalMinPrice = $indexerHelper->getBundleFinalPriceExpr( $write, $priceType, $minPrice, $specialExpr, $groupMinPrice );
				$finalMaxPrice = $indexerHelper->getBundleFinalPriceExpr( $write, $priceType, $maxPrice, $specialExpr, $groupMaxPrice );
			} 
else {
				$finalMinPrice = $origPrice;
				$finalMaxPrice = $origPrice;
			}

			( array( 'price_type' => new Zend_Db_Expr( $priceType ), 'special_price' => $specialExpr, 'tier_percent' => $tierExpr, 'orig_price' => $origPrice, 'price' => $finalPrice, 'min_price' => $finalMinPrice, 'max_price' => $finalMaxPrice, 'tier_price' => $tierPrice, 'base_tier' => $tierPrice ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				( array( 'group_price' => $groupPrice, 'base_group_price' => $groupPrice, 'group_price_percent' => new Zend_Db_Expr( 'gp.price' ) ) );
			}

			( array( 'stock_id' => new Zend_Db_Expr( 'cis.stock_id' ), 'currency' => $indexerHelper->getCurrencyExpr( 'cw.website_id' ), 'store_id' => new Zend_Db_Expr( 'cs.store_id' ) ) );

			if (!is_null( $entityIds )) {
				$select->where( 'e.entity_id IN(?)', $entityIds );
			}

			$eventData = array( 'select' => $select, 'entity_field' => new Zend_Db_Expr( 'e.entity_id' ), 'website_field' => new Zend_Db_Expr( 'cw.website_id' ), 'store_field' => new Zend_Db_Expr( 'cs.store_id' ), 'stock_field' => new Zend_Db_Expr( 'cis.stock_id' ), 'currency_field' => $indexerHelper->getCurrencyExpr( 'cw.website_id' ), 'store_field' => new Zend_Db_Expr( 'cs.store_id' ) );
			Mage::dispatchEvent( 'catalog_product_prepare_index_select', $eventData );
			$query = $select->insertFromSelect( $table );
			$write->query( $query );
			return $this;
		}

		/**
     * Calculate bundle product selections price by product type
     *
     * @param int $priceType
     * 
     * @return Mage_Bundle_Model_Mysql4_Indexer_Price
     */
		function _calculateBundleSelectionPrice($priceType) {
			$write = $this->_getWriteAdapter(  );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				if ($priceType == PRICE_TYPE_FIXED) {
					$selectionPriceValue = $write->getCheckSql( 'bsp.selection_price_value IS NULL', 'bs.selection_price_value', 'bsp.selection_price_value' );
					$selectionPriceType = $write->getCheckSql( 'bsp.selection_price_type IS NULL', 'bs.selection_price_type', 'bsp.selection_price_type' );
					$priceExpr = new Zend_Db_Expr( $selectionPriceType . ' = 1', 'ROUND(i.price * (' . $selectionPriceValue . ' / 100),4)', $write->getCheckSql( 'i.special_price > 0 AND i.special_price < 100', 'ROUND(' . $selectionPriceValue . ' * (i.special_price / 100),4)', $selectionPriceValue ) )( '* bs.selection_qty' );
					$tierExpr = $write->getCheckSql( 'i.base_tier IS NOT NULL', $write->getCheckSql( $selectionPriceType . ' = 1', 'ROUND(i.base_tier - (i.base_tier * (' . $selectionPriceValue . ' / 100)),4)', $write->getCheckSql( 'i.tier_percent > 0', 'ROUND(' . $selectionPriceValue . ' - (' . $selectionPriceValue . ' * (i.tier_percent / 100)),4)', $selectionPriceValue ) ) . ' * bs.selection_qty', 'NULL' );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$groupExpr = $write->getCheckSql( 'i.base_group_price IS NOT NULL', $write->getCheckSql( $selectionPriceType . ' = 1', $priceExpr, $write->getCheckSql( 'i.group_price_percent > 0', 'ROUND(' . $selectionPriceValue . ' - (' . $selectionPriceValue . ' * (i.group_price_percent / 100)),4)', $selectionPriceValue ) ) . ' * bs.selection_qty', 'NULL' );
						$priceExpr = new Zend_Db_Expr( $groupExpr . ' < ' . $priceExpr, $groupExpr, $priceExpr )(  );
					}
				} 
else {
					$priceExpr = new Zend_Db_Expr( 'i.special_price > 0 AND i.special_price < 100', 'ROUND(idx.min_price * (i.special_price / 100), 4)', 'idx.min_price' )( ' * bs.selection_qty' );
					$tierExpr = $write->getCheckSql( 'i.base_tier IS NOT NULL', 'ROUND(idx.min_price * (i.base_tier / 100), 4)* bs.selection_qty', 'NULL' );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$groupExpr = $write->getCheckSql( 'i.base_group_price IS NOT NULL', 'ROUND(idx.min_price * (i.base_group_price / 100), 4)* bs.selection_qty', 'NULL' );
						$groupPriceExpr = new Zend_Db_Expr( 'i.base_group_price IS NOT NULL AND i.base_group_price > 0 AND i.base_group_price < 100', 'ROUND(idx.min_price - idx.min_price * (i.base_group_price / 100), 4)', 'idx.min_price' )( ' * bs.selection_qty' );
						$priceExpr = new Zend_Db_Expr( $groupPriceExpr . ' < ' . $priceExpr, $groupPriceExpr, $priceExpr )(  );
					}
				}

				$groupType = $write->getCheckSql( 'bo.type = \'select\' OR bo.type = \'radio\'', '0', '1' );
			} 
else {
				if ($priceType == PRICE_TYPE_FIXED) {
					$priceExpr = new Zend_Db_Expr( 'IF(IF(bsp.selection_price_type IS NULL, bs.selection_price_type, ' . 'bsp.selection_price_type) = 1, ' . 'ROUND(i.price * (IF(bsp.selection_price_value IS NULL, bs.selection_price_value, ' . 'bsp.selection_price_value) / 100), 4), IF(i.special_price > 0, ' . 'ROUND(IF(bsp.selection_price_value IS NULL, bs.selection_price_value, bsp.selection_price_value) ' . '* (i.special_price / 100), 4), IF(bsp.selection_price_value IS NULL, bs.selection_price_value, ' . 'bsp.selection_price_value))) * bs.selection_qty' );
					$tierExpr = new Zend_Db_Expr( 'IF(i.base_tier IS NOT NULL, IF(IF(bsp.selection_price_type IS NULL, ' . 'bs.selection_price_type, bsp.selection_price_type) = 1, ' . 'ROUND(i.base_tier - (i.base_tier * (IF(bsp.selection_price_value IS NULL, bs.selection_price_value, ' . 'bsp.selection_price_value) / 100)), 4), IF(i.tier_percent > 0, ' . 'ROUND(IF(bsp.selection_price_value IS NULL, bs.selection_price_value, bsp.selection_price_value) ' . '- (IF(bsp.selection_price_value IS NULL, bs.selection_price_value, bsp.selection_price_value) ' . '* (i.tier_percent / 100)), 4), IF(bsp.selection_price_value IS NULL, bs.selection_price_value, ' . 'bsp.selection_price_value))) * bs.selection_qty, NULL)' );
				} 
else {
					$priceExpr = new Zend_Db_Expr( 'IF(i.special_price > 0, ROUND(idx.min_price * (i.special_price / 100), 4), ' . 'idx.min_price) * bs.selection_qty' );
					$tierExpr = new Zend_Db_Expr( 'IF(i.base_tier IS NOT NULL, ROUND(idx.min_price * (i.base_tier / 100), 4) ' . '* bs.selection_qty, NULL)' );
				}

				$groupType = new Zend_Db_Expr( 'IF(bo.type = \'select\' OR bo.type = \'radio\', 0, 1)' );
			}

			$select = ;

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->columns( array( 'group_type' => $groupType, 'is_required' => 'bo.required', 'price' => $priceExpr, 'tier_price' => $tierExpr, 'group_price' => $groupExpr, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			} 
else {
				$select->columns( array( 'group_type' => $groupType, 'is_required' => 'bo.required', 'price' => $priceExpr, 'tier_price' => $tierExpr, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			}

			$select->insertFromSelect( $this->_getBundleSelectionTable(  ) );
			$query = $write->select(  )->from( array( 'i' => $this->_getBundlePriceTable(  ) ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'bo' => $this->getTable( 'bundle/option' ) ), 'bo.parent_id = i.entity_id', array( 'option_id' ) )->join( array( 'bs' => $this->getTable( 'bundle/selection' ) ), 'bs.option_id = bo.option_id', array( 'selection_id' ) )->joinLeft( array( 'bsp' => $this->getTable( 'bundle/selection_price' ) ), 'bs.selection_id = bsp.selection_id AND bsp.website_id = i.website_id', array( '' ) )->join( array( 'idx' => $this->getIdxTable(  ) ), '(bs.product_id = idx.entity_id) AND (i.customer_group_id = idx.customer_group_id) AND ' . '(i.website_id = idx.website_id) AND ' . '(i.stock_id = idx.stock_id) AND (i.currency = idx.currency) AND (i.store_id = idx.store_id)', array(  ) )->join( array( 'e' => $this->getTable( 'catalog/product' ) ), 'bs.product_id = e.entity_id AND e.required_options=0', array(  ) )->where( 'i.price_type=?', $priceType );
			$write->query( $query );
			return $this;
		}

		/**
     * Calculate fixed bundle product selections price
     *
     * @return Mage_Bundle_Model_Mysql4_Indexer_Price
     */
		function _calculateBundleOptionPrice() {
			$write = $this->_getWriteAdapter(  );
			$this->_prepareBundleSelectionTable(  );
			$this->_calculateBundleSelectionPrice( PRICE_TYPE_FIXED );
			$this->_calculateBundleSelectionPrice( PRICE_TYPE_DYNAMIC );
			$this->_prepareBundleOptionTable(  );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$select = $write->select(  )->from( array( 'i' => $this->_getBundleSelectionTable(  ) ), array( 'entity_id', 'customer_group_id', 'website_id', 'option_id' ) )->group( array( 'entity_id', 'customer_group_id', 'website_id', 'option_id', 'stock_id', 'currency', 'store_id', 'is_required', 'group_type' ) );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$select->columns( array( 'min_price' => $write->getCheckSql( 'i.is_required = 1', 'MIN(i.price)', '0' ), 'alt_price' => $write->getCheckSql( 'i.is_required = 0', 'MIN(i.price)', '0' ), 'max_price' => $write->getCheckSql( 'i.group_type = 1', 'SUM(i.price)', 'MAX(i.price)' ), 'tier_price' => $write->getCheckSql( 'i.is_required = 1', 'MIN(i.tier_price)', '0' ), 'alt_tier_price' => $write->getCheckSql( 'i.is_required = 0', 'MIN(i.tier_price)', '0' ), 'group_price' => $write->getCheckSql( 'i.is_required = 1', 'MIN(i.group_price)', '0' ), 'alt_group_price' => $write->getCheckSql( 'i.is_required = 0', 'MIN(i.group_price)', '0' ), 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
				} 
else {
					$select->columns( array( 'min_price' => $write->getCheckSql( 'i.is_required = 1', 'MIN(i.price)', '0' ), 'alt_price' => $write->getCheckSql( 'i.is_required = 0', 'MIN(i.price)', '0' ), 'max_price' => $write->getCheckSql( 'i.group_type = 1', 'SUM(i.price)', 'MAX(i.price)' ), 'tier_price' => $write->getCheckSql( 'i.is_required = 1', 'MIN(i.tier_price)', '0' ), 'alt_tier_price' => $write->getCheckSql( 'i.is_required = 0', 'MIN(i.tier_price)', '0' ), 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
				}
			} 
else {
				$select = ( array( 'min_price' => new Zend_Db_Expr( 'IF(i.is_required = 1, MIN(i.price), 0)' ), 'alt_price' => new Zend_Db_Expr( 'IF(i.is_required = 0, MIN(i.price), 0)' ), 'max_price' => new Zend_Db_Expr( 'IF(i.group_type = 1, SUM(i.price), MAX(i.price))' ), 'tier_price' => new Zend_Db_Expr( 'IF(i.is_required = 1, MIN(i.tier_price), 0)' ), 'alt_tier_price' => new Zend_Db_Expr( 'IF(i.is_required = 0, MIN(i.tier_price), 0)' ), 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			}

			$query = $select->insertFromSelect( $this->_getBundleOptionTable(  ) );
			$write->query( $query );
			$this->_prepareDefaultFinalPriceTable(  );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$minPrice = new Zend_Db_Expr( 'SUM(io.min_price) = 0', 'MIN(io.alt_price)', 'SUM(io.min_price)' )( ' + i.price' );
				$maxPrice = new Zend_Db_Expr( 'SUM(io.max_price) + i.price' );
				$tierPrice = $write->getCheckSql( 'MIN(i.tier_percent) IS NOT NULL', $write->getCheckSql( 'SUM(io.tier_price) = 0', 'SUM(io.alt_tier_price)', 'SUM(io.tier_price)' ) . ' + MIN(i.tier_price)', 'NULL' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$groupPrice = $write->getCheckSql( 'MIN(i.group_price_percent) IS NOT NULL', $write->getCheckSql( 'SUM(io.group_price) = 0', 'SUM(io.alt_group_price)', 'SUM(io.group_price)' ) . ' + MIN(i.group_price)', 'NULL' );
				}

				$select = $write->select(  )->from( array( 'io' => $this->_getBundleOptionTable(  ) ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'i' => $this->_getBundlePriceTable(  ) ), 'i.entity_id = io.entity_id AND i.customer_group_id = io.customer_group_id AND i.website_id = io.website_id AND ' . '(i.stock_id = io.stock_id) AND (i.currency = io.currency) AND (i.store_id = io.store_id)', array(  ) )->group( array( 'io.entity_id', 'io.customer_group_id', 'io.website_id', 'io.stock_id', 'io.currency', 'io.store_id', 'i.tax_class_id', 'i.orig_price', 'i.price' ) );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$select->columns( array( 'i.tax_class_id', 'orig_price' => 'i.orig_price', 'price' => 'i.price', 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'base_tier' => 'MIN(i.base_tier)', 'group_price' => $groupPrice, 'base_group_price' => 'MIN(i.base_group_price)', 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
				} 
else {
					$select->columns( array( 'i.tax_class_id', 'orig_price' => 'i.orig_price', 'price' => 'i.price', 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'base_tier' => 'MIN(i.base_tier)', 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
				}
			} 
else {
				$minPrice = new Zend_Db_Expr( 'IF(SUM(io.min_price) = 0, MIN(io.alt_price), SUM(io.min_price)) + i.price' );
				$maxPrice = new Zend_Db_Expr( 'SUM(io.max_price) + i.price' );
				$tierPrice = new Zend_Db_Expr( 'IF(i.tier_percent IS NOT NULL, IF(SUM(io.tier_price) = 0, ' . 'SUM(io.alt_tier_price), SUM(io.tier_price)) + i.tier_price, NULL)' );
				$select = $write->select(  )->from( array( 'io' => $this->_getBundleOptionTable(  ) ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'i' => $this->_getBundlePriceTable(  ) ), '(i.entity_id = io.entity_id) AND (i.customer_group_id = io.customer_group_id) AND ' . '(i.website_id = io.website_id) AND ' . '(i.stock_id = io.stock_id) AND (i.currency = io.currency) AND (i.store_id = io.store_id)', array(  ) )->group( array( 'io.entity_id', 'io.customer_group_id', 'io.website_id', 'io.stock_id' ) )->columns( array( 'i.tax_class_id', 'orig_price' => 'i.orig_price', 'price' => 'i.price', 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'base_tier' => 'i.base_tier', 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			}

			$query = $select->insertFromSelect( $this->_getDefaultFinalPriceTable(  ) );
			$write->query( $query );
			return $this;
		}

		/**
     * Prepare temporary index price for bundle products
     *
     * @param int|array $entityIds  the entity ids limitation
     * 
     * @return Mage_Bundle_Model_Mysql4_Indexer_Price
     */
		function _prepareBundlePrice($entityIds = null) {
			$this->_prepareTierPriceIndex( $entityIds );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$this->_prepareGroupPriceIndex( $entityIds );
			}

			$this->_prepareBundlePriceTable(  );
			$this->_prepareBundlePriceByType( PRICE_TYPE_FIXED, $entityIds );
			$this->_prepareBundlePriceByType( PRICE_TYPE_DYNAMIC, $entityIds );
			$select = $this->_getWriteAdapter(  )->select(  )->join( array( 'wd' => $this->_getWebsiteDateTable(  ) ), 'i.website_id = wd.website_id', array(  ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$websiteDate = 'wd.website_date';
			} 
else {
				$websiteDate = 'wd.date';
			}

			Mage::dispatchEvent( 'prepare_catalog_product_price_index_table', array( 'index_table' => array( 'i' => $this->_getBundlePriceTable(  ) ), 'select' => $select, 'entity_id' => 'i.entity_id', 'customer_group_id' => 'i.customer_group_id', 'website_id' => 'i.website_id', 'website_date' => $websiteDate, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id', 'update_fields' => array( 'price', 'min_price', 'max_price' ) ) );
			$this->_calculateBundleOptionPrice(  );
			$this->_applyCustomOption(  );
			$this->_movePriceDataToIndexTable(  );
			return $this;
		}

		/**
     * Prepare percentage tier price for bundle products
     *
     * @see Mage_Catalog_Model_Resource_Product_Indexer_Price::_prepareTierPriceIndex
     *
     * @param int|array $entityIds
     * 
     * @return Mage_Bundle_Model_Resource_Indexer_Price
     */
		function _prepareTierPriceIndex($entityIds = null) {
			$isMultipleMode = $indexerHelper = $helper = $this->getWarehouseHelper(  );
			$adapter = $this->_getWriteAdapter(  );
			$adapter->select(  )->from( array( 'i' => $this->_getTierPriceIndexTable(  ) ), null )->join( array( 'e' => $this->getTable( 'catalog/product' ) ), 'i.entity_id=e.entity_id', array(  ) )->where( 'e.type_id=?', $this->getTypeId(  ) );
			$select = $this->getProductPriceIndexerHelper(  );
			$select->deleteFromSelect( 'i' );
			$query = $helper->isMultipleMode(  );
			$adapter->query( $query );
			$currency = $indexerHelper->getCurrencyExpr( 'cw.website_id' );
			$price = new Zend_Db_Expr( 'tp.value' );
			$columns = array( 'entity_id' => new Zend_Db_Expr( 'tp.entity_id' ), 'customer_group_id' => new Zend_Db_Expr( 'cg.customer_group_id' ), 'website_id' => new Zend_Db_Expr( 'cw.website_id' ), 'stock_id' => new Zend_Db_Expr( new Zend_Db_Expr( ($isMultipleMode ? $helper->getDefaultStockId(  ) : 'cis.stock_id') ) ), 'currency' => $currency, 'store_id' => new Zend_Db_Expr( 'cs.store_id' ), 'min_price' => new Zend_Db_Expr( ( 'MIN(' . $price . ')' ) ) );

			if ($isMultipleMode) {
				$group = array( 'tp.entity_id', 'cg.customer_group_id', 'cw.website_id', $currency, 'cs.store_id' );
			} 
else {
				$group = array( 'tp.entity_id', 'cg.customer_group_id', 'cw.website_id', 'cis.stock_id', $currency, 'cs.store_id' );
			}

			$select = $adapter->select(  )->from( array( 'tp' => $this->getValueTable( 'catalog/product', 'tier_price' ) ), array(  ) )->join( array( 'e' => $this->getTable( 'catalog/product' ) ), 'tp.entity_id=e.entity_id', array(  ) )->join( array( 'cg' => $this->getTable( 'customer/customer_group' ) ), 'tp.all_groups = 1 OR (tp.all_groups = 0 AND tp.customer_group_id = cg.customer_group_id)', array(  ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'tp.website_id = 0 OR tp.website_id = cw.website_id', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.website_id = cw.website_id', array(  ) )->joinLeft( array( 'cr' => $this->getTable( 'directory/currency_rate' ) ), implode( ' AND ', array( ( '(cr.currency_from = ' . $indexerHelper->getBaseCurrencyExpr( 'cw.website_id' ) . ')' ), '((tp.currency IS NULL) OR (tp.currency = cr.currency_to))' ) ), array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), '(csg.group_id = cs.group_id) AND ((tp.store_id = 0) OR (tp.store_id = cs.store_id))', array(  ) )->join( array( 'cis' => $this->getTable( 'cataloginventory/stock' ) ), '(tp.stock_id IS NULL) OR (tp.stock_id = cis.stock_id)', array(  ) )->where( '(cw.website_id != 0) AND (cs.store_id != 0)' )->where( 'e.type_id=?', $this->getTypeId(  ) )->columns( $columns )->group( $group );

			if (!empty( $$entityIds )) {
				$select->where( 'tp.entity_id IN(?)', $entityIds );
			}

			$query = $select->insertFromSelect( $this->_getTierPriceIndexTable(  ) );
			$adapter->query( $query );
			return $this;
		}
	}

?>