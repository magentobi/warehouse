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

	class Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer extends Mage_Core_Helper_Abstract {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get price helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price
     */
		function getPriceHelper() {
			return Mage::helper( 'warehouse/catalog_product_price' );
		}

		/**
     * Get product helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function getProductHelper() {
			return $this->getPriceHelper(  )->getProductHelper(  );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getWarehouseHelper(  )->getVersionHelper(  );
		}

		/**
     * Check if multiple mode is enabled
     * 
     * @return bool
     */
		function isMultipleMode() {
			return $this->getWarehouseHelper(  )->isMultipleMode(  );
		}

		/**
     * Get default stock identifier
     * 
     * @return int
     */
		function getDefaultStockId() {
			return $this->getWarehouseHelper(  )->getDefaultStockId(  );
		}

		/**
     * Get table
     * 
     * @param string $entityName
     * 
     * @return string
     */
		function getTable($entityName) {
			return $this->getWarehouseHelper(  )->getTable( $entityName );
		}

		/**
     * Retrieve table name for the entity separated value
     *
     * @param string $entityName
     * @param string $valueType
     * 
     * @return string
     */
		function getValueTable($entityName, $valueType) {
			return $this->getTable( $entityName ) . '_' . $valueType;
		}

		/**
     * Get table name for product batch price index
     *
     * @return string
     */
		function getBatchPriceIndexTable() {
			return $this->getTable( 'catalog/product_index_batch_price' );
		}

		/**
     * Get table name for product batch price index
     *
     * @return string
     */
		function getBatchSpecialPriceIndexTable() {
			return $this->getTable( 'catalog/product_index_batch_special_price' );
		}

		/**
     * Add stock join to select
     * 
     * @param Zend_Db_Select $select
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer
     */
		function addStockJoin($select) {
			$tableAlias = 'cis';
			$table = $this->getTable( 'cataloginventory/stock' );
			$defaultStockId = $this->getDefaultStockId(  );
			$condition = ($this->isMultipleMode(  ) ? ( '(' ) . $tableAlias . '.stock_id = \'' . $defaultStockId . '\')' : '');
			$select->join( array( $tableAlias => $table ), $condition, array(  ) );
			return $this;
		}

		/**
     * Add batch price join to select
     * 
     * @param Zend_Db_Select $select
     * @param string $tableAlias
     * @param string $table
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer 
     */
		function addBatchPriceJoin($select, $tableAlias, $table) {
			$select->joinLeft( array( $tableAlias => $table ), implode( ' AND ', array( ( '(' ) . $tableAlias . '.entity_id = e.entity_id)', ( '(' ) . $tableAlias . '.stock_id = cis.stock_id)', ( '(' ) . $tableAlias . '.website_id = cw.website_id)' ) ), array(  ) );
			return $this;
		}

		/**
     * Add tier price join
     * 
     * @param Zend_Db_Select $select
     * @param string $tableAlias
     * @param string $table
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer
     */
		function addTierPriceJoin($select, $tableAlias, $table) {
			$select->joinLeft( array( $tableAlias => $table ), implode( ' AND ', array( ( '(' ) . $tableAlias . '.entity_id = e.entity_id)', ( '(' ) . $tableAlias . '.website_id = cw.website_id)', ( '(' ) . $tableAlias . '.customer_group_id = cg.customer_group_id)', ( '(' ) . $tableAlias . '.stock_id = cis.stock_id)' ) ), array(  ) );
			return $this;
		}

		/**
     * Add group price join
     * 
     * @param Zend_Db_Select $select
     * @param string $tableAlias
     * @param string $table
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product_Price_Indexer
     */
		function addGroupPriceJoin($select, $tableAlias, $table) {
			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$select->joinLeft( array( $tableAlias => $table ), implode( ' AND ', array( ( '(' ) . $tableAlias . '.entity_id = e.entity_id)', ( '(' ) . $tableAlias . '.website_id = cw.website_id)', ( '(' ) . $tableAlias . '.customer_group_id = cg.customer_group_id)' ) ), array(  ) );
			}

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
			$select = $write->select(  )->from( array( 'e' => $this->getTable( 'catalog/product' ) ), array( 'entity_id' ) )->join( array( 'cg' => $this->getTable( 'customer/customer_group' ) ), '', array( 'customer_group_id' ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), '', array( 'website_id' ) )->join( array( 'cwd' => $this->getTable( 'catalog/product_index_website' ) ), 'cw.website_id = cwd.website_id', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.website_id = cw.website_id AND cw.default_group_id = csg.group_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'csg.default_store_id = cs.store_id AND cs.store_id != 0', array(  ) )->join( array( 'pw' => $this->getTable( 'catalog/product_website' ) ), 'pw.product_id = e.entity_id AND pw.website_id = cw.website_id', array(  ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$this->addGroupPriceJoin( $select, 'gp', $this->getTable( 'catalog/product_index_group_price' ) );
			}

			return $select;
		}

		/**
     * Get final price expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param Zend_Db_Expr $price
     * @param Zend_Db_Expr $specialPrice
     * @param Zend_Db_Expr $specialFrom
     * @param Zend_Db_Expr $specialTo
     * 
     * @return Zend_Db_Expr 
     */
		function getFinalPriceExpr($write, $price, $specialPrice, $specialFrom, $specialTo) {
			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$currentDate = $write->getDatePartSql( 'cwd.website_date' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$groupPrice = $write->getCheckSql( 'gp.price IS NULL', $price, 'gp.price' );
				}

				$specialToDate = $specialFromDate = $write->getDatePartSql( $specialFrom );
				$specialFromUse = $write->getDatePartSql( $specialTo );
				$write->getCheckSql( $specialToDate . ' >= ' . $currentDate, '1', '0' );
				$write->getCheckSql( $specialFrom . ' IS NULL', '1', $specialFromUse );
				$write->getCheckSql( $specialTo . ' IS NULL', '1', $specialToUse );
				$specialToHas = $specialFromHas = $specialToUse = $write->getCheckSql( $specialFromDate . ' <= ' . $currentDate, '1', '0' );
				$finalPrice = $write->getCheckSql( $specialFromHas . ' > 0 AND ' . $specialToHas . ' > 0' . ( ' AND ' . $specialPrice . ' < ' . $price ), $specialPrice, $price );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$finalPrice = $write->getCheckSql( $groupPrice . ' < ' . $finalPrice, $groupPrice, $finalPrice );
				}
			} 
else {
				$currentDate = ;
				new Zend_Db_Expr( 'IF(IF(' . $specialFrom . ' IS NULL, 1, ' . ( 'IF(DATE(' . $specialFrom . ') <= ' . $currentDate . ', 1, 0)) > 0 AND IF(' . $specialTo . ' IS NULL, 1, ' ) . ( 'IF(DATE(' . $specialTo . ') >= ' . $currentDate . ', 1, 0)) > 0 AND ' . $specialPrice . ' < ' . $price . ', ' ) . ( $specialPrice . ', ' . $price . ')' ) );
				$finalPrice = new Zend_Db_Expr( 'cwd.date' );
			}

			return $finalPrice;
		}

		/**
     * Get bundle special price expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param Zend_Db_Expr $price
     * 
     * @return Zend_Db_Expr
     */
		function getBundleSpecialPriceExpr($write, $specialPrice, $specialFrom, $specialTo) {
			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$currentDate = new Zend_Db_Expr( 'cwd.website_date' );
			} 
else {
				$currentDate = new Zend_Db_Expr( 'cwd.date' );
			}


			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$specialExpr = $write->getCheckSql( $write->getCheckSql( $specialFrom . ' IS NULL', '1', $write->getCheckSql( $specialFrom . ' <= ' . $currentDate, '1', '0' ) ) . ' > 0 AND ' . $write->getCheckSql( $specialTo . ' IS NULL', '1', $write->getCheckSql( $specialTo . ' >= ' . $currentDate, '1', '0' ) ) . ( ' > 0 AND ' . $specialPrice . ' > 0 ' ), $specialPrice, '0' );
			} 
else {
				$specialExpr = new Zend_Db_Expr( 'IF(IF(' . $specialFrom . ' IS NULL, 1, ' . ( 'IF(' . $specialFrom . ' <= ' . $currentDate . ', 1, 0)) > 0 AND IF(' . $specialTo . ' IS NULL, 1, ' ) . ( 'IF(' . $specialTo . ' >= ' . $currentDate . ', 1, 0)) > 0 AND ' . $specialPrice . ' > 0, ' . $specialPrice . ', 0)' ) );
			}

			return $specialExpr;
		}

		/**
     * Get bundle tier expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * 
     * @return Zend_Db_Expr 
     */
		function getBundleTierExpr($write) {
			return new Zend_Db_Expr( 'tp.min_price' );
		}

		/**
     * Get bundle tier price expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param int $priceType
     * @param Zend_Db_Expr $price
     * 
     * @return Zend_Db_Expr
     */
		function getBundleTierPriceExpr($write, $priceType, $price) {
			$tierExpr = $this->getBundleTierExpr( $write );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				if ($priceType == PRICE_TYPE_FIXED) {
					$tierPrice = $write->getCheckSql( $tierExpr . ' IS NOT NULL', 'ROUND(' . $price . ' - ' . '(' . $price . ' * (' . $tierExpr . ' / 100)), 4)', 'NULL' );
				} 
else {
					$tierPrice = $write->getCheckSql( $tierExpr . ' IS NOT NULL', '0', 'NULL' );
				}
			} 
else {
				if ($priceType == PRICE_TYPE_FIXED) {
					$tierPrice = new Zend_Db_Expr( 'IF(' . $tierExpr . ' IS NOT NULL, ROUND(' . $price . ' - (' . $price . ' * (' . $tierExpr . ' / 100)), 4), NULL)' );
				} 
else {
					$tierPrice = new Zend_Db_Expr( 'IF(' . $tierExpr . ' IS NOT NULL, 0, NULL)' );
				}
			}

			return $tierPrice;
		}

		/**
     * Get bundle group expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * 
     * @return Zend_Db_Expr 
     */
		function getBundleGroupExpr($write) {
			if ($this->getVersionHelper(  )->isGe1700(  )) {
				return $write->getCheckSql( 'gp.price IS NOT NULL AND gp.price > 0 AND gp.price < 100', 'gp.price', '0' );
			}

		}

		/**
     * Get bundle group price expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param int $priceType
     * @param Zend_Db_Expr $price
     * 
     * @return Zend_Db_Expr
     */
		function getBundleGroupPriceExpr($write, $priceType, $price) {
			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$groupPriceExpr = $this->getBundleGroupExpr( $write );

				if ($priceType == PRICE_TYPE_FIXED) {
					$groupPrice = $write->getCheckSql( $groupPriceExpr . ' > 0', 'ROUND(' . $price . ' - ' . '(' . $price . ' * (' . $groupPriceExpr . ' / 100)), 4)', 'NULL' );
				} 
else {
					$groupPrice = $write->getCheckSql( $groupPriceExpr . ' > 0', $groupPriceExpr, 'NULL' );
				}

				return $groupPrice;
			}

		}

		/**
     * Get bundle origional price expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param Zend_Db_Expr $price
     * 
     * @return Zend_Db_Expr
     */
		function getBundleOrigPriceExpr($write, $price) {
			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$origPrice = $write->getCheckSql( $price . ' IS NULL', '0', $price );
			} 
else {
				$origPrice = new Zend_Db_Expr( ( 'IF(' . $price . ' IS NULL, 0, ' . $price . ')' ) );
			}

			return $origPrice;
		}

		/**
     * Get bundle final price expression
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param int $priceType
     * @param Zend_Db_Expr $price
     * 
     * @return Zend_Db_Expr
     */
		function getBundleFinalPriceExpr($write, $priceType, $price, $specialExpr, $groupPrice = null) {
			if ($this->getVersionHelper(  )->isGe1600(  )) {
				if ($priceType == PRICE_TYPE_FIXED) {
					$finalPrice = $write->getCheckSql( $specialExpr . ' > 0', 'ROUND(' . $price . ' * (' . $specialExpr . '  / 100), 4)', $price );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$finalPrice = $write->getCheckSql( $groupPrice . ' IS NOT NULL AND ' . $groupPrice . ' < ' . $finalPrice, $groupPrice, $finalPrice );
					}
				} 
else {
					$finalPrice = new Zend_Db_Expr( '0' );
				}
			} 
else {
				if ($priceType == PRICE_TYPE_FIXED) {
					$finalPrice = new Zend_Db_Expr( ( 'IF(' . $specialExpr . ' > 0, ROUND(' . $price . ' * (' . $specialExpr . ' / 100), 4), ' . $price . ')' ) );
				} 
else {
					$finalPrice = new Zend_Db_Expr( '0' );
				}
			}

			return $finalPrice;
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
			$select = $write->select(  )->from( array( 'i' => $table ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'cw.website_id = i.website_id', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.group_id = cw.default_group_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'cs.store_id = csg.default_store_id', array(  ) )->join( array( 'o' => $this->getTable( 'catalog/product_option' ) ), 'o.product_id = i.entity_id', array( 'option_id' ) )->join( array( 'ot' => $this->getTable( 'catalog/product_option_type_value' ) ), 'ot.option_id = o.option_id', array(  ) )->join( array( 'otpd' => $this->getTable( 'catalog/product_option_type_price' ) ), 'otpd.option_type_id = ot.option_type_id AND otpd.store_id = 0', array(  ) )->joinLeft( array( 'otps' => $this->getTable( 'catalog/product_option_type_price' ) ), 'otps.option_type_id = otpd.option_type_id AND otpd.store_id = cs.store_id', array(  ) )->group( array( 'i.entity_id', 'i.customer_group_id', 'i.website_id', 'o.option_id', 'i.stock_id' ) );

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
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'group_price' => $groupPrice, 'stock_id' => 'i.stock_id' ) );
			} 
else {
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'stock_id' => 'i.stock_id' ) );
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
			$select = $write->select(  )->from( array( 'i' => $table ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'cw.website_id = i.website_id', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.group_id = cw.default_group_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'cs.store_id = csg.default_store_id', array(  ) )->join( array( 'o' => $this->getTable( 'catalog/product_option' ) ), 'o.product_id = i.entity_id', array( 'option_id' ) )->join( array( 'opd' => $this->getTable( 'catalog/product_option_price' ) ), 'opd.option_id = o.option_id AND opd.store_id = 0', array(  ) )->joinLeft( array( 'ops' => $this->getTable( 'catalog/product_option_price' ) ), 'ops.option_id = opd.option_id AND ops.store_id = cs.store_id', array(  ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$optPriceType = $write->getCheckSql( 'ops.option_price_id > 0', 'ops.price_type', 'opd.price_type' );
				$optPriceValue = $write->getCheckSql( 'ops.option_price_id > 0', 'ops.price', 'opd.price' );
				$minPriceRound = new Zend_Db_Expr( 'ROUND(i.price * (' . $optPriceValue . ' / 100), 4)' );
				$priceExpr = $write->getCheckSql( $optPriceType . ' = \'fixed\'', $optPriceValue, $minPriceRound );
				$minPrice = $write->getCheckSql( $priceExpr . ' > 0 AND o.is_require > 1', $priceExpr, 0 );
				$maxPrice = $maxPrice;
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
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'group_price' => $groupPrice, 'stock_id' => 'i.stock_id' ) );
			} 
else {
				$select->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'tier_price' => $tierPrice, 'stock_id' => 'i.stock_id' ) );
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
				$select->from( array( $table ), array( 'entity_id', 'customer_group_id', 'website_id', 'min_price' => 'SUM(min_price)', 'max_price' => 'SUM(max_price)', 'tier_price' => 'SUM(tier_price)', 'group_price' => 'SUM(group_price)', 'stock_id' ) );
			} 
else {
				$select->from( array( $table ), array( 'entity_id', 'customer_group_id', 'website_id', 'min_price' => 'SUM(min_price)', 'max_price' => 'SUM(max_price)', 'tier_price' => 'SUM(tier_price)', 'stock_id' ) );
			}

			$select->group( array( 'entity_id', 'customer_group_id', 'website_id', 'stock_id' ) );
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
			$select = $write->select(  )->join( array( 'io' => $table ), '(i.entity_id = io.entity_id) AND (i.customer_group_id = io.customer_group_id) AND ' . '(i.website_id = io.website_id) AND (i.stock_id = io.stock_id)', array(  ) );

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
			$select = $write->select(  )->from( array( 'i' => $table ), null )->join( array( 'l' => $this->getTable( 'catalog/product_super_link' ) ), 'l.parent_id = i.entity_id', array( 'parent_id', 'product_id' ) )->columns( array( 'customer_group_id', 'website_id' ), 'i' )->join( array( 'a' => $this->getTable( 'catalog/product_super_attribute' ) ), 'l.parent_id = a.product_id', array(  ) )->join( array( 'cp' => $this->getValueTable( 'catalog/product', 'int' ) ), 'l.product_id = cp.entity_id AND cp.attribute_id = a.attribute_id AND cp.store_id = 0', array(  ) )->joinLeft( array( 'apd' => $this->getTable( 'catalog/product_super_attribute_pricing' ) ), 'a.product_super_attribute_id = apd.product_super_attribute_id' . ' AND apd.website_id = 0 AND cp.value = apd.value_index', array(  ) )->joinLeft( array( 'apw' => $this->getTable( 'catalog/product_super_attribute_pricing' ) ), 'a.product_super_attribute_id = apw.product_super_attribute_id' . ' AND apw.website_id = i.website_id AND cp.value = apw.value_index', array(  ) )->join( array( 'le' => $this->getTable( 'catalog/product' ) ), 'le.entity_id = l.product_id', array(  ) )->where( 'le.required_options=0' )->group( array( 'l.parent_id', 'i.customer_group_id', 'i.website_id', 'l.product_id', 'i.stock_id' ) );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$priceExpression = $write->getCheckSql( 'apw.value_id IS NOT NULL', 'apw.pricing_value', 'apd.pricing_value' );
				$percentExpr = $write->getCheckSql( 'apw.value_id IS NOT NULL', 'apw.is_percent', 'apd.is_percent' );
				$roundExpr = 'ROUND(i.price * (' . $priceExpression . ' / 100), 4)';
				$roundPriceExpr = $write->getCheckSql( $percentExpr . ' = 1', $roundExpr, $priceExpression );
				$priceColumn = $write->getCheckSql( $priceExpression . ' IS NULL', '0', $roundPriceExpr );
				$priceColumn = new Zend_Db_Expr( ( 'SUM(' . $priceColumn . ')' ) );
				$tierPrice = $groupPriceExp;
				$tierRoundPriceExp = $write->getCheckSql( $percentExpr . ' = 1', $roundExpr, $tierPrice );
				$tierPriceExp = $write->getCheckSql( $tierPrice . ' IS NULL', '0', $tierRoundPriceExp );
				$tierPriceColumn = $write->getCheckSql( 'MIN(i.tier_price) IS NOT NULL', ( 'SUM(' . $tierPriceExp . ')' ), 'NULL' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$groupPrice = $groupPriceExp;
					$groupRoundPriceExp = $write->getCheckSql( $percentExpr . ' = 1', $roundExpr, $groupPrice );
					$groupPriceExp = $write->getCheckSql( $groupPrice . ' IS NULL', '0', $groupRoundPriceExp );
					$groupPriceColumn = $write->getCheckSql( 'MIN(i.group_price) IS NOT NULL', ( 'SUM(' . $groupPriceExp . ')' ), 'NULL' );
				}


				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$select->columns( array( 'price' => $priceColumn, 'tier_price' => $tierPriceColumn, 'group_price' => $groupPriceColumn, 'stock_id' => 'i.stock_id' ) );
				} 
else {
					$select->columns( array( 'price' => $priceColumn, 'tier_price' => $tierPriceColumn, 'stock_id' => 'i.stock_id' ) );
				}
			} 
else {
				( new Zend_Db_Expr( 'IF(i.tier_price IS NOT NULL, SUM(IF((@tier_price:=' . 'IF(apw.value_id, apw.pricing_value, apd.pricing_value)) IS NULL, 0, IF(' . 'IF(apw.value_id, apw.is_percent, apd.is_percent) = 1, ' . 'ROUND(i.price * (@tier_price / 100), 4), @tier_price))), NULL)' ) )->columns( 'i.stock_id' );
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
				$select->from( array( $table ), array( 'parent_id', 'customer_group_id', 'website_id', 'MIN(price)', 'MAX(price)', 'MIN(tier_price)', 'MIN(group_price)', 'stock_id' ) );
			} 
else {
				$select->from( array( $table ), array( 'parent_id', 'customer_group_id', 'website_id', 'MIN(price)', 'MAX(price)', 'MIN(tier_price)', 'stock_id' ) );
			}

			$select->group( array( 'parent_id', 'customer_group_id', 'website_id', 'stock_id' ) );
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
			$select = $write->select(  )->join( array( 'io' => $table ), '(i.entity_id = io.entity_id) AND (i.customer_group_id = io.customer_group_id) AND ' . '(i.website_id = io.website_id) AND (i.stock_id = io.stock_id)', array(  ) );

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

			$select = $write->select(  )->from( array( 'i' => $table ), array( 'entity_id', 'customer_group_id', 'website_id' ) )->join( array( 'dl' => $dlType->getBackend(  )->getTable(  ) ), 'dl.entity_id = i.entity_id AND dl.attribute_id = ' . $dlType->getAttributeId(  ) . ' AND dl.store_id = 0', array(  ) )->join( array( 'dll' => $this->getTable( 'downloadable/link' ) ), 'dll.product_id = i.entity_id', array(  ) )->join( array( 'dlpd' => $this->getTable( 'downloadable/link_price' ) ), 'dll.link_id = dlpd.link_id AND dlpd.website_id = 0', array(  ) )->joinLeft( array( 'dlpw' => $this->getTable( 'downloadable/link_price' ) ), 'dlpd.link_id = dlpw.link_id AND dlpw.website_id = i.website_id', array(  ) )->where( 'dl.value = ?', 1 )->group( array( 'i.entity_id', 'i.customer_group_id', 'i.website_id', 'i.stock_id' ) )->columns( array( 'min_price' => $minPrice, 'max_price' => $maxPrice, 'stock_id' => 'i.stock_id' ) );
			return $select;
		}

		/**
     * Get downloadable link final price select
     * 
     * @param Varien_Db_Adapter_Interface $write
     * @param string $table
     * 
     * @return Zend_Db_Select
     */
		function getDownloadableLinkFinalPriceSelect($write, $table) {
			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$ifTierPrice = $write->getCheckSql( 'i.tier_price IS NOT NULL', '(i.tier_price + id.min_price)', 'NULL' );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$ifGroupPrice = $write->getCheckSql( 'i.group_price IS NOT NULL', '(i.group_price + id.min_price)', 'NULL' );
				}

				$tierPrice = new Zend_Db_Expr( $ifTierPrice );
			} 
else {
				$tierPrice = new Zend_Db_Expr( 'IF(i.tier_price IS NOT NULL, i.tier_price + id.min_price, NULL)' );
			}

			$select = ( array( 'min_price' => new Zend_Db_Expr( 'i.min_price + id.min_price' ), 'max_price' => new Zend_Db_Expr( 'i.max_price + id.max_price' ), 'tier_price' => $tierPrice ) );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				( array( 'group_price' => new Zend_Db_Expr( $ifGroupPrice ) ) );
			}

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
				$columns = array( 'entity_id' => 'entity_id', 'customer_group_id' => 'customer_group_id', 'website_id' => 'website_id', 'tax_class_id' => 'tax_class_id', 'price' => 'orig_price', 'final_price' => 'price', 'min_price' => 'min_price', 'max_price' => 'max_price', 'tier_price' => 'tier_price', 'group_price' => 'group_price', 'stock_id' => 'stock_id' );
			} 
else {
				$columns = array( 'entity_id' => 'entity_id', 'customer_group_id' => 'customer_group_id', 'website_id' => 'website_id', 'tax_class_id' => 'tax_class_id', 'price' => 'orig_price', 'final_price' => 'price', 'min_price' => 'min_price', 'max_price' => 'max_price', 'tier_price' => 'tier_price', 'stock_id' => 'stock_id' );
			}

			return $columns;
		}

		/**
     * Add price index filter
     * 
     * @param Varien_Data_Collection_Db $collection
     * 
     * @return Varien_Data_Collection_Db
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

					$joinCond = $oldJoinCond . ' AND price_index.stock_id = ' . $stockId;
					$fromPart['price_index']['joinCondition'] = $joinCond;
					$select->setPart( FROM, $fromPart );
				}
			}

			return $collection;
		}
	}

?>