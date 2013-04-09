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

	class Innoexts_WarehousePlus_Helper_Catalog_Product_Price_Indexer extends Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer {
		/**
     * Get base currency expression
     * 
     * @param string $website
     * 
     * @return Zend_Db_Expr 
     */
		function getBaseCurrencyExpr($website) {
			$pieces = array(  );
			$helper = $this->getWarehouseHelper(  );
			$currencyHelper = $helper->getCurrencyHelper(  );
			foreach ($currencyHelper->getBaseCodes(  ) as $websiteId => $currencyCode) {
				array_push( $pieces, 'WHEN ' . $website . ' = ' . $websiteId . ' THEN \'' . $currencyCode . '\'' );
			}

			return new Zend_Db_Expr( ' ', $pieces )( '(CASE ' .  . ' END)' );
		}

		/**
     * Get currency expression
     * 
     * @param string $website
     * 
     * @return Zend_Db_Expr
     */
		function getCurrencyExpr($website) {
			return new Zend_Db_Expr( $website )( 'IF (
            cr.currency_to IS NULL, 
            ' .  . ', 
            cr.currency_to
        )' );
		}

		/**
     * Add currency rate to select
     * 
     * @param Zend_Db_Select $select
     * 
     * @return Innoexts_WarehousePlus_Helper_Catalog_Product_Price_Indexer
     */
		function addCurrencyRateJoin($select) {
			$tableAlias = 'cr';
			$table = $this->getTable( 'directory/currency_rate' );
			$select->joinLeft( array( $tableAlias => $table ), ( ( '(' ) . $tableAlias . '.currency_from = ' . $this->getBaseCurrencyExpr( 'cw.website_id' ) . ')' ), array(  ) );
			return $this;
		}

		/**
     * Add batch price join to select
     * 
     * @param Zend_Db_Select $select
     * @param string $tableAlias
     * @param string $table
     * 
     * @return Innoexts_Warehouse_Helper_Data 
     */
		function addBatchPriceJoin($select, $tableAlias, $table) {
			$select->joinLeft( array( $tableAlias => $table ), implode( ' AND ', array( ( '(' ) . $tableAlias . '.entity_id = e.entity_id)', ( '(' ) . $tableAlias . '.stock_id = cis.stock_id)', ( ( '(' ) . $tableAlias . '.currency = ' . $this->getCurrencyExpr( 'cw.website_id' ) . ')' ), ( '(' ) . $tableAlias . '.store_id = cs.store_id)' ) ), array(  ) );
			return $this;
		}

		/**
     * Add tier price join
     * 
     * @param Zend_Db_Select $select
     * @param string $tableAlias
     * @param string $table
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function addTierPriceJoin($select, $tableAlias, $table) {
			$select->joinLeft( array( $tableAlias => $table ), implode( ' AND ', array( ( '(' ) . $tableAlias . '.entity_id = e.entity_id)', ( '(' ) . $tableAlias . '.website_id = cw.website_id)', ( '(' ) . $tableAlias . '.store_id = cs.store_id)', ( '(' ) . $tableAlias . '.customer_group_id = cg.customer_group_id)', ( '(' ) . $tableAlias . '.stock_id = cis.stock_id)', ( ( '(' ) . $tableAlias . '.currency = ' . $this->getCurrencyExpr( 'cw.website_id' ) . ')' ) ) ), array(  ) );
			return $this;
		}

		/**
     * Get final price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * 
     * @return Zend_Db_Select
     */
		function getFinalPriceSelect($write) {
			$select = $write->select(  )->from( array( 'e' => $this->getTable( 'catalog/product' ) ), array( 'entity_id' ) )->join( array( 'cg' => $this->getTable( 'customer/customer_group' ) ), '', array( 'customer_group_id' ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), '', array( 'website_id' ) )->join( array( 'cwd' => $this->getTable( 'catalog/product_index_website' ) ), 'cw.website_id = cwd.website_id', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.website_id = cw.website_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'csg.group_id = cs.group_id AND cs.store_id != 0', array(  ) )->join( array( 'pw' => $this->getTable( 'catalog/product_website' ) ), 'pw.product_id = e.entity_id AND pw.website_id = cw.website_id', array(  ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$this->addGroupPriceJoin( $select, 'gp', $this->getTable( 'catalog/product_index_group_price' ) );
			}

			return $select;
		}

		/**
     * Get option type price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getOptionTypePriceSelect($write, $table) {
			$select = $write->select(  )->from( array( 'i' => $table ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'cw.website_id = i.website_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'cs.store_id = i.store_id', array(  ) )->join( array( 'o' => $this->getTable( 'catalog/product_option' ) ), 'o.product_id = i.entity_id', array( 'option_id' ) )->join( array( 'ot' => $this->getTable( 'catalog/product_option_type_value' ) ), 'ot.option_id = o.option_id', array(  ) )->join( array( 'otpd' => $this->getTable( 'catalog/product_option_type_price' ) ), 'otpd.option_type_id = ot.option_type_id AND otpd.store_id = 0', array(  ) )->joinLeft( array( 'otps' => $this->getTable( 'catalog/product_option_type_price' ) ), 'otps.option_type_id = otpd.option_type_id AND otpd.store_id = cs.store_id', array(  ) )->group( array( 'i.entity_id', 'i.customer_group_id', 'i.website_id', 'o.option_id', 'i.stock_id', 'i.currency', 'i.store_id' ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$optPriceType = $write->getCheckSql( 'otps.option_type_price_id > 0', 'otps.price_type', 'otpd.price_type' );
				$optPriceValue = $write->getCheckSql( 'otps.option_type_price_id > 0', 'otps.price', 'otpd.price' );
				$minPriceRound = new Zend_Db_Expr( 'ROUND(i.price * (' . $optPriceValue . ' / 100), 4)' );
				$minPriceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $minPriceRound );
				$minPriceMin = new Zend_Db_Expr( ( 'MIN(' . $minPriceExpr . ')' ) );
				$minPrice = $write->getCheckSql( 'MIN(o.is_require) = 1', $minPriceMin, '0' );
				$tierPriceRound = new Zend_Db_Expr( 'ROUND(i.base_tier * (' . $optPriceValue . ' / 100), 4)' );
				$tierPriceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $tierPriceRound );
				$tierPriceMin = new Zend_Db_Expr( ( 'MIN(' . $tierPriceExpr . ')' ) );
				$tierPriceValue = $write->getCheckSql( 'MIN(o.is_require) > 0', $tierPriceMin, 0 );
				$tierPrice = $write->getCheckSql( 'MIN(i.base_tier) IS NOT NULL', $tierPriceValue, 'NULL' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$groupPriceRound = new Zend_Db_Expr( 'ROUND(i.base_group_price * (' . $optPriceValue . ' / 100), 4)' );
					$groupPriceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $groupPriceRound );
					$groupPriceMin = new Zend_Db_Expr( ( 'MIN(' . $groupPriceExpr . ')' ) );
					$groupPriceValue = $write->getCheckSql( 'MIN(o.is_require) > 0', $groupPriceMin, 0 );
					$groupPrice = $write->getCheckSql( 'MIN(i.base_group_price) IS NOT NULL', $groupPriceValue, 'NULL' );
				}

				$maxPriceRound = new Zend_Db_Expr( 'ROUND(i.price * (' . $optPriceValue . ' / 100), 4)' );
				$maxPriceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $maxPriceRound );
				$maxPrice = $write->getCheckSql( '(MIN(o.type)=\'radio\' OR MIN(o.type)=\'drop_down\')', ( 'MAX(' . $maxPriceExpr . ')' ), ( 'SUM(' . $maxPriceExpr . ')' ) );
			} 
else {
				$minPrice = new Zend_Db_Expr( 'IF(o.is_require, MIN(IF(IF(otps.option_type_price_id>0, otps.price_type, ' . 'otpd.price_type)=\'fixed\', IF(otps.option_type_price_id>0, otps.price, otpd.price), ' . 'ROUND(i.price * (IF(otps.option_type_price_id>0, otps.price, otpd.price) / 100), 4))), 0)' );
				$tierPrice = new Zend_Db_Expr( 'IF(i.base_tier IS NOT NULL, IF(o.is_require, ' . 'MIN(IF(IF(otps.option_type_price_id>0, otps.price_type, otpd.price_type)=\'fixed\', ' . 'IF(otps.option_type_price_id>0, otps.price, otpd.price), ' . 'ROUND(i.base_tier * (IF(otps.option_type_price_id>0, otps.price, otpd.price) / 100), 4))), 0), NULL)' );
				$maxPrice = new Zend_Db_Expr( 'IF((o.type=\'radio\' OR o.type=\'drop_down\'), ' . 'MAX(IF(IF(otps.option_type_price_id>0, otps.price_type, otpd.price_type)=\'fixed\', ' . 'IF(otps.option_type_price_id>0, otps.price, otpd.price), ' . 'ROUND(i.price * (IF(otps.option_type_price_id>0, otps.price, otpd.price) / 100), 4))), ' . 'SUM(IF(IF(otps.option_type_price_id>0, otps.price_type, otpd.price_type)=\'fixed\', ' . 'IF(otps.option_type_price_id>0, otps.price, otpd.price), ' . 'ROUND(i.price * (IF(otps.option_type_price_id>0, otps.price, otpd.price) / 100), 4))))' );
			}


			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'group_price' => $groupPrice, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			} 
else {
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			}

			return $select;
		}

		/**
     * Get option price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getOptionPriceSelect($write, $table) {
			$select = $write->select(  )->from( array( 'i' => $table ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'cw.website_id = i.website_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'cs.store_id = i.store_id', array(  ) )->join( array( 'o' => $this->getTable( 'catalog/product_option' ) ), 'o.product_id = i.entity_id', array( 'option_id' ) )->join( array( 'opd' => $this->getTable( 'catalog/product_option_price' ) ), 'opd.option_id = o.option_id AND opd.store_id = 0', array(  ) )->joinLeft( array( 'ops' => $this->getTable( 'catalog/product_option_price' ) ), 'ops.option_id = opd.option_id AND ops.store_id = cs.store_id', array(  ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$optPriceType = $write->getCheckSql( 'ops.option_price_id > 0', 'ops.price_type', 'opd.price_type' );
				$optPriceValue = $write->getCheckSql( 'ops.option_price_id > 0', 'ops.price', 'opd.price' );
				$minPriceRound = new Zend_Db_Expr( 'ROUND(i.price * (' . $optPriceValue . ' / 100), 4)' );
				$priceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $minPriceRound );
				$minPrice = $write->getCheckSql( $priceExpr . ' > 0 AND o.is_require > 1', $priceExpr, 0 );
				$maxPrice = $minPrice;
				$tierPriceRound = new Zend_Db_Expr( 'ROUND(i.base_tier * (' . $optPriceValue . ' / 100), 4)' );
				$tierPriceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $tierPriceRound );
				$tierPriceValue = $write->getCheckSql( $tierPriceExpr . ' > 0 AND o.is_require > 0', $tierPriceExpr, 0 );
				$tierPrice = $write->getCheckSql( 'i.base_tier IS NOT NULL', $tierPriceValue, 'NULL' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$groupPriceRound = new Zend_Db_Expr( 'ROUND(i.base_group_price * (' . $optPriceValue . ' / 100), 4)' );
					$groupPriceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $groupPriceRound );
					$groupPriceValue = $write->getCheckSql( $groupPriceExpr . ' > 0 AND o.is_require > 0', $groupPriceExpr, 0 );
					$groupPrice = $write->getCheckSql( 'i.base_group_price IS NOT NULL', $groupPriceValue, 'NULL' );
				}
			} 
else {
				$minPrice = new Zend_Db_Expr( 'IF((@price:=IF(IF(ops.option_price_id>0, ops.price_type, opd.price_type)=\'fixed\',' . ' IF(ops.option_price_id>0, ops.price, opd.price), ROUND(i.price * (IF(ops.option_price_id>0, ' . 'ops.price, opd.price) / 100), 4))) AND o.is_require, @price,0)' );
				$maxPrice = new Zend_Db_Expr( '@price' );
				$tierPrice = new Zend_Db_Expr( 'IF(i.base_tier IS NOT NULL, IF((@tier_price:=IF(IF(ops.option_price_id>0, ' . 'ops.price_type, opd.price_type)=\'fixed\', IF(ops.option_price_id>0, ops.price, opd.price), ' . 'ROUND(i.base_tier * (IF(ops.option_price_id>0, ops.price, opd.price) / 100), 4))) AND o.is_require, ' . '@tier_price, 0), NULL)' );
			}


			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'group_price' => $groupPrice, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			} 
else {
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			}

			return $select;
		}

		/**
     * Get aggregated option price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select 
     */
		function getAggregatedOptionPriceSelect($write, $table) {
			$select = $write->select(  );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->from( array( $table ), array( 'entity_id', 'customer_group_id', 'website_id', 'min_price' => 'SUM(min_price)', 'max_price' => 'SUM(max_price)', 'tier_price' => 'SUM(tier_price)', 'group_price' => 'SUM(group_price)', 'stock_id', 'currency', 'store_id' ) );
			} 
else {
				$select->from( array( $table ), array( 'entity_id', 'customer_group_id', 'website_id', 'min_price' => 'SUM(min_price)', 'max_price' => 'SUM(max_price)', 'tier_price' => 'SUM(tier_price)', 'stock_id', 'currency', 'store_id' ) );
			}

			$select->group( array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ) );
			return $select;
		}

		/**
     * Get option final price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getOptionFinalPriceSelect($write, $table) {
			$select = $write->select(  )->join( array( 'io' => $table ), '(i.entity_id = io.entity_id) AND ' . '(i.customer_group_id = io.customer_group_id) AND ' . '(i.website_id = io.website_id) AND ' . '(i.stock_id = io.stock_id) AND (i.currency = io.currency) AND (i.store_id = io.store_id)', array(  ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$tierPrice = $write->getCheckSql( 'i.tier_price IS NOT NULL', 'i.tier_price + io.tier_price', 'NULL' );
			} 
else {
				$tierPrice = new Zend_Db_Expr( 'IF(i.tier_price IS NOT NULL, i.tier_price + io.tier_price, NULL)' );
			}

			( array( 'min_price' => new Zend_Db_Expr( 'i.min_price + io.min_price' ), 'max_price' => new Zend_Db_Expr( 'i.max_price + io.max_price' ), 'tier_price' => $tierPrice ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->columns( array( 'group_price' => $write->getCheckSql( 'i.group_price IS NOT NULL', 'i.group_price + io.group_price', 'NULL' ) ) );
			}

			return $select;
		}

		/**
     * Get configurable option price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getConfigurableOptionPriceSelect($write, $table) {
			$select = $write->select(  )->from( array( 'i' => $table ), null )->join( array( 'l' => $this->getTable( 'catalog/product_super_link' ) ), 'l.parent_id = i.entity_id', array( 'parent_id', 'product_id' ) )->columns( array( 'customer_group_id', 'website_id' ), 'i' )->join( array( 'a' => $this->getTable( 'catalog/product_super_attribute' ) ), 'l.parent_id = a.product_id', array(  ) )->join( array( 'cp' => $this->getValueTable( 'catalog/product', 'int' ) ), 'l.product_id = cp.entity_id AND cp.attribute_id = a.attribute_id AND cp.store_id = 0', array(  ) )->joinLeft( array( 'apd' => $this->getTable( 'catalog/product_super_attribute_pricing' ) ), 'a.product_super_attribute_id = apd.product_super_attribute_id' . ' AND apd.website_id = 0 AND cp.value = apd.value_index', array(  ) )->joinLeft( array( 'apw' => $this->getTable( 'catalog/product_super_attribute_pricing' ) ), 'a.product_super_attribute_id = apw.product_super_attribute_id' . ' AND apw.website_id = i.website_id AND cp.value = apw.value_index', array(  ) )->join( array( 'le' => $this->getTable( 'catalog/product' ) ), 'le.entity_id = l.product_id', array(  ) )->where( 'le.required_options=0' )->group( array( 'l.parent_id', 'i.customer_group_id', 'i.website_id', 'l.product_id', 'i.stock_id', 'i.currency', 'i.store_id' ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$priceExpression = $write->getCheckSql( 'apw.value_id IS NOT NULL', 'apw.pricing_value', 'apd.pricing_value' );
				$percentExpr = $write->getCheckSql( 'apw.value_id IS NOT NULL', 'apw.is_percent', 'apd.is_percent' );
				$roundExpr = 'ROUND(i.price * (' . $priceExpression . ' / 100), 4)';
				$roundPriceExpr = $write->getCheckSql( $percentExpr . ' = 1', $roundExpr, $priceExpression );
				$priceColumn = $write->getCheckSql( $priceExpression . ' IS NULL', '0', $roundPriceExpr );
				$priceColumn = new Zend_Db_Expr( ( 'SUM(' . $priceColumn . ')' ) );
				$tierPrice = $groupPrice;
				$tierRoundPriceExp = $write->getCheckSql( $percentExpr . ' = 1', $roundExpr, $tierPrice );
				$tierPriceExp = $write->getCheckSql( $tierPrice . ' IS NULL', '0', $tierRoundPriceExp );
				$tierPriceColumn = $write->getCheckSql( 'MIN(i.tier_price) IS NOT NULL', ( 'SUM(' . $tierPriceExp . ')' ), 'NULL' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$groupPrice = $groupPrice;
					$groupRoundPriceExp = $write->getCheckSql( $percentExpr . ' = 1', $roundExpr, $groupPrice );
					$groupPriceExp = $write->getCheckSql( $groupPrice . ' IS NULL', '0', $groupRoundPriceExp );
					$groupPriceColumn = $write->getCheckSql( 'MIN(i.group_price) IS NOT NULL', ( 'SUM(' . $groupPriceExp . ')' ), 'NULL' );
				}


				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$select->columns( array( 'price' => $priceColumn, 'tier_price' => $tierPriceColumn, 'group_price' => $groupPriceColumn, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
				} 
else {
					$select->columns( array( 'price' => $priceColumn, 'tier_price' => $tierPriceColumn, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
				}
			} 
else {
				( new Zend_Db_Expr( 'IF(i.tier_price IS NOT NULL, SUM(IF((@tier_price:=' . 'IF(apw.value_id, apw.pricing_value, apd.pricing_value)) IS NULL, 0, IF(' . 'IF(apw.value_id, apw.is_percent, apd.is_percent) = 1, ' . 'ROUND(i.price * (@tier_price / 100), 4), @tier_price))), NULL)' ) )->columns( 'i.stock_id' )->columns( 'i.currency' )->columns( 'i.store_id' );
			}

			return $select;
		}

		/**
     * Get aggregated configurable option price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select 
     */
		function getAggregatedConfigurableOptionPriceSelect($write, $table) {
			$select = $write->select(  );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->from( array( $table ), array( 'parent_id', 'customer_group_id', 'website_id', 'MIN(price)', 'MAX(price)', 'MIN(tier_price)', 'MIN(group_price)', 'stock_id', 'currency', 'store_id' ) );
			} 
else {
				$select->from( array( $table ), array( 'parent_id', 'customer_group_id', 'website_id', 'MIN(price)', 'MAX(price)', 'MIN(tier_price)', 'stock_id', 'currency', 'store_id' ) );
			}

			$select->group( array( 'parent_id', 'customer_group_id', 'website_id', 'stock_id', 'currency', 'store_id' ) );
			return $select;
		}

		/**
     * Get configurable option final price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getConfigurableOptionFinalPriceSelect($write, $table) {
			$select = $write->select(  )->join( array( 'io' => $table ), '(i.entity_id = io.entity_id) AND (i.customer_group_id = io.customer_group_id) AND ' . '(i.website_id = io.website_id) AND (i.stock_id = io.stock_id) AND ' . '(i.currency = io.currency) AND (i.store_id = io.store_id)', array(  ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$tierPrice = $write->getCheckSql( 'i.tier_price IS NOT NULL', 'i.tier_price + io.tier_price', 'NULL' );
			} 
else {
				$tierPrice = new Zend_Db_Expr( 'IF(i.tier_price IS NOT NULL, i.tier_price + io.tier_price, NULL)' );
			}

			( array( 'min_price' => new Zend_Db_Expr( 'i.min_price + io.min_price' ), 'max_price' => new Zend_Db_Expr( 'i.max_price + io.max_price' ), 'tier_price' => $tierPrice ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->columns( array( 'group_price' => $write->getCheckSql( 'i.group_price IS NOT NULL', 'i.group_price + io.group_price', 'NULL' ) ) );
			}

			return $select;
		}

		/**
     * Get downloadable link price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getDownloadableLinkPriceSelect($write, $table) {
			$dlType = $this->getProductHelper(  )->getAttribute( 'links_purchased_separately' );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$ifPrice = $write->getIfNullSql( 'dlpw.price_id', 'dlpd.price' );
				$minPrice = new Zend_Db_Expr( 'MIN(' . $ifPrice . ')' );
				$maxPrice = new Zend_Db_Expr( 'SUM(' . $ifPrice . ')' );
			} 
else {
				$minPrice = new Zend_Db_Expr( 'MIN(IF(dlpw.price_id, dlpw.price, dlpd.price))' );
				$maxPrice = new Zend_Db_Expr( 'SUM(IF(dlpw.price_id, dlpw.price, dlpd.price))' );
			}

			$select = $write->select(  )->from( array( 'i' => $table ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'dl' => $dlType->getBackend(  )->getTable(  ) ), 'dl.entity_id = i.entity_id AND dl.attribute_id = ' . $dlType->getAttributeId(  ) . ' AND dl.store_id = 0', array(  ) )->join( array( 'dll' => $this->getTable( 'downloadable/link' ) ), 'dll.product_id = i.entity_id', array(  ) )->join( array( 'dlpd' => $this->getTable( 'downloadable/link_price' ) ), 'dll.link_id = dlpd.link_id AND dlpd.website_id = 0', array(  ) )->joinLeft( array( 'dlpw' => $this->getTable( 'downloadable/link_price' ) ), 'dlpd.link_id = dlpw.link_id AND dlpw.website_id = i.website_id', array(  ) )->where( 'dl.value = ?', 1 )->group( array( 'i.entity_id', 'i.customer_group_id', 'i.website_id', 'i.stock_id', 'i.currency', 'i.store_id' ) )->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'stock_id' => 'i.stock_id', 'currency' => 'i.currency', 'store_id' => 'i.store_id' ) );
			return $select;
		}

		/**
     * Get price select columns
     * 
     * @return array 
     */
		function getPriceSelectColumns() {
			$columns = array(  );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$columns = array( 'entity_id' => 'entity_id', 'customer_group_id' => 'customer_group_id', 'website_id' => 'website_id', 'tax_class_id' => 'tax_class_id', 'price' => 'orig_price', 'final_price' => 'price', 'min_price' => 'min_price', 'max_price' => 'max_price', 'tier_price' => 'tier_price', 'group_price' => 'group_price', 'stock_id' => 'stock_id', 'currency' => 'currency', 'store_id' => 'store_id' );
			} 
else {
				$columns = array( 'entity_id' => 'entity_id', 'customer_group_id' => 'customer_group_id', 'website_id' => 'website_id', 'tax_class_id' => 'tax_class_id', 'price' => 'orig_price', 'final_price' => 'price', 'min_price' => 'min_price', 'max_price' => 'max_price', 'tier_price' => 'tier_price', 'stock_id' => 'stock_id', 'currency' => 'currency', 'store_id' => 'store_id' );
			}

			return $columns;
		}

		/**
     * Add price index filter
     * 
     * @param Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection
     * 
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
     */
		function addPriceIndexFilter($collection) {
			if (!$collection) {
				return $collection;
			}

			$select = $collection->getSelect(  );
			$fromPart = $select->getPart( FROM );

			if (isset( $fromPart['price_index'] )) {
				$oldJoinCond = $fromPart['price_index']['joinCondition'];

				if (strpos( $oldJoinCond, 'stock_id' ) === false) {
					$helper = $this->getWarehouseHelper(  );
					$connection = $collection->getConnection(  );

					if (!$collection->getFlag( 'stock_id' )) {
						if ($this->isMultipleMode(  )) {
							$stockId = $connection->quote( $this->getDefaultStockId(  ) );
						} 
else {
							$stockId = $connection->quote( $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId(  ) );
						}
					} 
else {
						$stockId = $collection->getFlag( 'stock_id' );
					}


					if (!$collection->getFlag( 'currency' )) {
						$currencyCode = $helper->getCurrencyHelper(  )->getCurrentCode(  );
					} 
else {
						$currencyCode = $collection->getFlag( 'currency' );
					}

					$currencyCode = $connection->quote( $currencyCode );

					if (!$collection->getFlag( 'store_id' )) {
						$storeId = $helper->getCurrentStoreId(  );
					} 
else {
						$storeId = $collection->getFlag( 'store_id' );
					}

					$storeId = $connection->quote( $storeId );
					$joinCond = $oldJoinCond . ' AND price_index.stock_id = ' . $stockId;
					$joinCond .=  ' AND ((price_index.currency IS NULL) OR (price_index.currency = ' . $currencyCode . '))';

					if ($storeId) {
						$joinCond .= ( ' AND (price_index.store_id = ' . $storeId . ')' );
					} 
else {
						$joinCond .= ' AND (price_index.store_id = 0)';
					}

					$fromPart['price_index']['joinCondition'] = $joinCond;
					$select->setPart( FROM, $fromPart );
				}
			}

			return $collection;
		}
	}

?>