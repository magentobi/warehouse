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

	class Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Indexer_Price extends Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Indexer_Price {
		/**
     * Prepare batch price index table
     * 
     * @param int|array $entityIds
     * @param string $attributeCode
     * @param string $table
     * @param string $indexTable
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Indexer_Price
     */
		function __prepareBatchPriceIndex($entityIds = null, $attributeCode, $table, $indexTable) {
			$helper = $this->getWarehouseHelper(  );
			$priceHelper = $helper->getProductPriceHelper(  );
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$select = $write->select(  )->from( array( 'e' => $this->getTable( 'catalog/product' ) ), array(  ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), '', array(  ) )->join( array( 'cwd' => $this->_getWebsiteDateTable(  ) ), '(cw.website_id = cwd.website_id)', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), '(csg.website_id = cw.website_id) AND (cw.website_id != 0)', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), '(csg.group_id = cs.group_id) AND (cs.store_id != 0)', array(  ) )->join( array( 'pw' => $this->getTable( 'catalog/product_website' ) ), '(pw.product_id = e.entity_id) AND (pw.website_id = cw.website_id)', array(  ) )->join( array( 'cis' => $this->getTable( 'cataloginventory/stock' ) ), '', array(  ) )->joinLeft( array( 'cr' => $this->getTable( 'directory/currency_rate' ) ), ( '(cr.currency_from = ' . $indexerHelper->getBaseCurrencyExpr( 'cw.website_id' ) . ')' ), array(  ) );
			$this->_addAttributeToSelect( $select, $attributeCode, 'e.entity_id', 'cs.store_id' );
			$select->joinLeft( array( 'cbgp' => $table ), implode( ' AND ', array( '(cbgp.product_id = e.entity_id)', '(cbgp.stock_id = cis.stock_id)', '(cbgp.currency = cr.currency_to)', '(cbgp.store_id = 0)' ) ), array(  ) );

			if (!$priceHelper->isGlobalScope(  )) {
				if ($priceHelper->isWebsiteScope(  )) {
					$select->joinLeft( array( 'cbp' => $table ), implode( ' AND ', array( '(cbp.product_id = e.entity_id)', '(cbp.stock_id = cis.stock_id)', '(cbp.currency = cr.currency_to)', '(csg.group_id = cw.default_group_id) AND (cbp.store_id = csg.default_store_id)' ) ), array(  ) );
				} 
else {
					$select->joinLeft( array( 'cbp' => $table ), implode( ' AND ', array( '(cbp.product_id = e.entity_id)', '(cbp.stock_id = cis.stock_id)', '(cbp.currency = cr.currency_to)', '(cbp.store_id = cs.store_id)' ) ), array(  ) );
				}
			}

			$rate = new Zend_Db_Expr( 'cr.rate' );

			if (!$priceHelper->isGlobalScope(  )) {
				new Zend_Db_Expr( 'IF (
                cbp.price IS NOT NULL, 
                ROUND(cbp.price / ' . $rate . ', 4), 
                IF (
                    cbgp.price IS NOT NULL, 
                    ROUND(ROUND(cbgp.price * cwd.rate, 4) / ' . $rate . ', 4), 
                    ' . $price . '
                )
            )' );
				$price = ;
			} 
else {
				new Zend_Db_Expr( 'IF (
                cbgp.price IS NOT NULL, 
                ROUND(ROUND(cbgp.price * cwd.rate, 4) / ' . $rate . ', 4), 
				$price = ' . $price . '
)' );

			}

			$currency = $price = $indexerHelper->getCurrencyExpr( 'cw.website_id' );
			$columns = array( 'entity_id' => new Zend_Db_Expr( 'e.entity_id' ), 'stock_id' => new Zend_Db_Expr( ($isMultipleMode ? $helper->getDefaultStockId(  ) : 'cis.stock_id') ), 'currency' => $currency, 'store_id' => new Zend_Db_Expr( 'cs.store_id' ), 'price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MAX(' . $price . ')' ) : $price) ), 'min_price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MIN(' . $price . ')' ) : 'NULL') ), 'max_price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MAX(' . $price . ')' ) : 'NULL') ) );

			if ($isMultipleMode) {
				$group = array( 'e.entity_id', 'cr.currency_to', 'cs.store_id' );
			} 
else {
				$group = array( 'e.entity_id', 'cis.stock_id', 'cr.currency_to', 'cs.store_id' );
			}

			$where = '(cw.website_id <> 0)';
			$select->where( $where )->columns( $columns )->group( $group );

			if (!empty( $$entityIds )) {
				$select->where( 'e.entity_id IN(?)', $entityIds );
			}

			$write->delete( $indexTable );
			$query = $select->insertFromSelect( $indexTable );
			$write->query( $query );
			return $this;
		}

		/**
     * Prepare batch price index table
     *
     * @param int|array $entityIds
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Indexer_Price
     */
		function _prepareBatchPriceIndex($entityIds = null) {
			return $this->__prepareBatchPriceIndex( $entityIds, 'price', $this->getTable( 'catalog/product_batch_price' ), $this->getProductPriceIndexerHelper(  )->getBatchPriceIndexTable(  ) );
		}

		/**
     * Prepare batch special price index table
     *
     * @param int|array $entityIds the entity ids limitation
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Indexer_Price
     */
		function _prepareBatchSpecialPriceIndex($entityIds = null) {
			return $this->__prepareBatchPriceIndex( $entityIds, 'special_price', $this->getTable( 'catalog/product_batch_special_price' ), $this->getProductPriceIndexerHelper(  )->getBatchSpecialPriceIndexTable(  ) );
		}

		/**
     * Prepare tier price index table
     *
     * @param int|array $entityIds the entity ids limitation
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Indexer_Price
     */
		function _prepareTierPriceIndex($entityIds = null) {
			$helper = $this->getWarehouseHelper(  );
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$table = $this->_getTierPriceIndexTable(  );
			$write->delete( $table );
			$currency = $indexerHelper->getCurrencyExpr( 'cw.website_id' );
			$rate = new Zend_Db_Expr( 'cr.rate' );
			$price = new Zend_Db_Expr( 'IF (tp.website_id=0, ROUND(tp.value * cwd.rate, 4), tp.value)' );
			new Zend_Db_Expr( 'IF (
            (tp.currency IS NOT NULL) AND (tp.currency <> \'\'), 
            ROUND(' . $price . ' / ' . $rate . ', 4), 
			$price = ' . $price . '
)' );

			$columns = array( 'entity_id' => new Zend_Db_Expr( 'tp.entity_id' ), 'customer_group_id' => new Zend_Db_Expr( 'cg.customer_group_id' ), 'website_id' => new Zend_Db_Expr( 'cw.website_id' ), 'stock_id' => new Zend_Db_Expr( new Zend_Db_Expr( ($isMultipleMode ? $helper->getDefaultStockId(  ) : 'cis.stock_id') ) ), 'currency' => $currency, 'store_id' => new Zend_Db_Expr( 'cs.store_id' ), 'min_price' => new Zend_Db_Expr( ( 'MIN(' . $price . ')' ) ) );

			if ($isMultipleMode) {
				$group = array( 'tp.entity_id', 'cg.customer_group_id', 'cw.website_id', $currency, 'cs.store_id' );
			} 
else {
				$group = array( 'tp.entity_id', 'cg.customer_group_id', 'cw.website_id', 'cis.stock_id', $currency, 'cs.store_id' );
			}

			$select = $write->select(  )->from( array( 'tp' => $this->getValueTable( 'catalog/product', 'tier_price' ) ), array(  ) )->join( array( 'cg' => $this->getTable( 'customer/customer_group' ) ), 'tp.all_groups = 1 OR (tp.all_groups = 0 AND tp.customer_group_id = cg.customer_group_id)', array(  ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'tp.website_id = 0 OR tp.website_id = cw.website_id', array(  ) )->join( array( 'cwd' => $this->_getWebsiteDateTable(  ) ), 'cw.website_id = cwd.website_id', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.website_id = cw.website_id', array(  ) )->joinLeft( array( 'cr' => $this->getTable( 'directory/currency_rate' ) ), implode( ' AND ', array( ( '(cr.currency_from = ' . $indexerHelper->getBaseCurrencyExpr( 'cw.website_id' ) . ')' ), '((tp.currency IS NULL) OR (tp.currency = cr.currency_to))' ) ), array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), '(csg.group_id = cs.group_id) AND ((tp.store_id = 0) OR (tp.store_id = cs.store_id))', array(  ) )->join( array( 'cis' => $this->getTable( 'cataloginventory/stock' ) ), '(tp.stock_id IS NULL) OR (tp.stock_id = cis.stock_id)', array(  ) )->where( '(cw.website_id != 0) AND (cs.store_id != 0)' )->columns( $columns )->group( $group );

			if (!empty( $$entityIds )) {
				$select->where( 'tp.entity_id IN(?)', $entityIds );
			}

			$query = $select->insertFromSelect( $table );
			$write->query( $query );
			return $this;
		}
	}

?>