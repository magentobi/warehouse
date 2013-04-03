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

	class Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Indexer_Price extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Price {
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
     * Process product save.
     * Method is responsible for index support
     * when product was saved and changed attribute(s) has an effect on price.
     *
     * @param Mage_Index_Model_Event $event
     * @return Mage_Catalog_Model_Resource_Product_Indexer_Price
     */
		function catalogProductSave($event) {
			$productId = $event->getEntityPk(  );
			$data = $event->getNewData(  );

			if (!isset( $data['reindex_price'] )) {
				return $this;
			}

			$this->clearTemporaryIndexTable(  );
			$this->_prepareWebsiteDateTable(  );
			$indexer = $this->_getIndexer( $data['product_type_id'] );
			$processIds = array( $productId );

			if ($indexer->getIsComposite(  )) {
				$this->_copyRelationIndexData( $productId );
				$this->_prepareBatchPriceIndex( $productId );
				$this->_prepareBatchSpecialPriceIndex( $productId );
				$this->_prepareTierPriceIndex( $productId );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$this->_prepareGroupPriceIndex( $productId );
				}

				$indexer->reindexEntity( $productId );
			} 
else {
				$parentIds = $this->getProductParentsByChild( $productId );

				if ($parentIds) {
					$processIds = array_merge( $processIds, array_keys( $parentIds ) );
					$this->_copyRelationIndexData( array_keys( $parentIds ), $productId );
					$this->_prepareBatchPriceIndex( $processIds );
					$this->_prepareBatchSpecialPriceIndex( $processIds );
					$this->_prepareTierPriceIndex( $processIds );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$this->_prepareGroupPriceIndex( $processIds );
					}

					$indexer->reindexEntity( $productId );
					$parentByType = array(  );
					foreach ($parentIds as $parentId => $parentType) {
						$parentByType[$parentType][$parentId] = $parentId;
					}

					foreach ($parentByType as $parentType => $entityIds) {
						$this->_getIndexer( $parentType )->reindexEntity( $entityIds );
					}
				} 
else {
					$this->_prepareBatchPriceIndex( $productId );
					$this->_prepareBatchSpecialPriceIndex( $productId );
					$this->_prepareTierPriceIndex( $productId );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$this->_prepareGroupPriceIndex( $productId );
					}

					$indexer->reindexEntity( $productId );
				}
			}

			$this->_copyIndexDataToMainTable( $processIds );
			return $this;
		}

		/**
     * Rebuild all index data
     *
     * @return Mage_Catalog_Model_Resource_Product_Indexer_Price
     */
		function reindexAll() {
			$this->useIdxTable( true );

			if ($this->getVersionHelper(  )->isGe1620(  )) {
				$this->beginTransaction(  );
				$this->clearTemporaryIndexTable(  );
				$this->_prepareWebsiteDateTable(  );
				$this->_prepareBatchPriceIndex(  );
				$this->_prepareBatchSpecialPriceIndex(  );
				$this->_prepareTierPriceIndex(  );

				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$this->_prepareGroupPriceIndex(  );
				}

				$indexers = $this->getTypeIndexers(  );
				foreach ($indexers as $indexer) {
					$indexer->reindexAll(  );
				}

				$this->syncData(  );
				$this->commit(  );
			}

			$this->useIdxTable( true );
			$this->clearTemporaryIndexTable(  );
			$this->_prepareWebsiteDateTable(  );
			$this->_prepareBatchPriceIndex(  );
			$this->_prepareBatchSpecialPriceIndex(  );
			$this->_prepareTierPriceIndex(  );
			$indexers = $this->getTypeIndexers(  );
			foreach ($indexers as $indexer) {

				if ($this->getVersionHelper(  )->isGe1610(  )) {
					if (( !$this->_allowTableChanges && is_callable( array( $indexer, 'setAllowTableChanges' ) ) )) {
						$indexer->setAllowTableChanges( false );
					}
				}

				$indexer->reindexAll(  );

				if ($this->getVersionHelper(  )->isGe1610(  )) {
					if (( !$this->_allowTableChanges && is_callable( array( $indexer, 'setAllowTableChanges' ) ) )) {
						$indexer->setAllowTableChanges( true );
						continue;
					}

					continue;
				}
			}

			$this->syncData(  );
			return $this;
		}

		/**
     * Retrieve catalog_product attribute instance by attribute code
     *
     * @param string $attributeCode
     * 
     * @return Mage_Catalog_Model_Resource_Eav_Attribute
     */
		function _getAttribute($attributeCode) {
			return Mage::getSingleton( 'eav/config' )->getAttribute( ENTITY, $attributeCode );
		}

		/**
     * Add attribute join condition to select and return Zend_Db_Expr
     * attribute value definition
     * If $condition is not empty apply limitation for select
     *
     * @param Varien_Db_Select $select
     * @param string $attrCode              the attribute code
     * @param string|Zend_Db_Expr $entity   the entity field or expression for condition
     * @param string|Zend_Db_Expr $store    the store field or expression for condition
     * @param Zend_Db_Expr $condition       the limitation condition
     * @param bool $required                if required or has condition used INNER join, else - LEFT
     * 
     * @return Zend_Db_Expr                 the attribute value expression
     */
		function _addAttributeToSelect($select, $attrCode, $entity, $store, $condition = null, $required = false) {
			$attribute = $this->_getAttribute( $attrCode );
			$attributeId = $attribute->getAttributeId(  );
			$attributeTable = $attribute->getBackend(  )->getTable(  );
			$adapter = $this->_getReadAdapter(  );
			$joinType = (( !is_null( $condition ) || $required ) ? 'join' : 'joinLeft');

			if ($attribute->isScopeGlobal(  )) {
				$alias = 'ta_' . $attrCode;
				$select->$joinType( array( $alias => $attributeTable ), $alias . '.entity_id = ' . $entity . ' AND ' . $alias . '.attribute_id = ' . $attributeId . ( ' AND ' . $alias . '.store_id = 0' ), array(  ) );
				$expression = new Zend_Db_Expr( $alias . '.value' );
			} 
else {
				$dAlias = 'tad_' . $attrCode;
				$sAlias = 'tas_' . $attrCode;
				$select->$joinType( array( $dAlias => $attributeTable ), $dAlias . '.entity_id = ' . $entity . ' AND ' . $dAlias . '.attribute_id = ' . $attributeId . ( ' AND ' . $dAlias . '.store_id = 0' ), array(  ) );
				$select->joinLeft( array( $sAlias => $attributeTable ), $sAlias . '.entity_id = ' . $entity . ' AND ' . $sAlias . '.attribute_id = ' . $attributeId . ( ' AND ' . $sAlias . '.store_id = ' . $store ), array(  ) );

				if ($this->getVersionHelper(  )->isGe1600(  )) {
					$expression = $adapter->getCheckSql( $adapter->getIfNullSql( $sAlias . '.value_id', -1 ) . ' > 0', $sAlias . '.value', $dAlias . '.value' );
				} 
else {
					$expression = new Zend_Db_Expr( 'IF(' . $sAlias . '.value_id > 0, ' . $sAlias . '.value, ' . $dAlias . '.value)' );
				}
			}


			if (!is_null( $condition )) {
				$select->where( $expression . $condition );
			}

			return $expression;
		}

		/**
     * Prepare batch price index table
     *
     * @param int|array $entityIds the entity ids limitation
     * 
     * @return Mage_Catalog_Model_Resource_Product_Indexer_Price
     */
		function _prepareBatchPriceIndex($entityIds = null) {
			$helper = $this->getWarehouseHelper(  );
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$table = $indexerHelper->getBatchPriceIndexTable(  );
			$select = $write->select(  )->from( array( 'e' => $this->getTable( 'catalog/product' ) ), array(  ) )->join( array( 'cis' => $this->getTable( 'cataloginventory/stock' ) ), '', array(  ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), '', array(  ) )->join( array( 'cwd' => $this->_getWebsiteDateTable(  ) ), '(cw.website_id = cwd.website_id)', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.website_id = cw.website_id AND cw.default_group_id = csg.group_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'csg.default_store_id = cs.store_id AND cs.store_id != 0', array(  ) )->join( array( 'pw' => $this->getTable( 'catalog/product_website' ) ), 'pw.product_id = e.entity_id AND pw.website_id = cw.website_id', array(  ) );
			$price = $this->_addAttributeToSelect( $select, 'price', 'e.entity_id', 'cs.store_id' );
			$select->joinLeft( array( 'cbgp' => $this->getTable( 'catalog/product_batch_price' ) ), implode( ' AND ', array( '(cbgp.product_id = e.entity_id)', '(cbgp.stock_id = cis.stock_id)', '(cbgp.website_id = 0)' ) ), array(  ) );

			if ($helper->getProductPriceHelper(  )->isWebsiteScope(  )) {
				$select->joinLeft( array( 'cbp' => $this->getTable( 'catalog/product_batch_price' ) ), implode( ' AND ', array( '(cbp.product_id = e.entity_id)', '(cbp.stock_id = cis.stock_id)', '(cbp.website_id = cw.website_id)' ) ), array(  ) );
			}


			if ($helper->getProductPriceHelper(  )->isWebsiteScope(  )) {
				$price = new Zend_Db_Expr( 'IF (cbp.price IS NOT NULL, cbp.price, IF (cbgp.price IS NOT NULL, ROUND(cbgp.price * cwd.rate, 4), ' . $price . '))' );
			} 
else {
				$price = new Zend_Db_Expr( ( 'IF (cbgp.price IS NOT NULL, ROUND(cbgp.price * cwd.rate, 4), ' . $price . ')' ) );
			}

			$columns = array( 'entity_id' => new Zend_Db_Expr( 'e.entity_id' ), 'stock_id' => new Zend_Db_Expr( ($isMultipleMode ? $helper->getDefaultStockId(  ) : 'cis.stock_id') ), 'website_id' => new Zend_Db_Expr( 'cw.website_id' ), 'price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MAX(' . $price . ')' ) : $price) ), 'min_price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MIN(' . $price . ')' ) : 'NULL') ), 'max_price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MAX(' . $price . ')' ) : 'NULL') ) );

			if ($isMultipleMode) {
				$group = array( 'e.entity_id', 'cw.website_id' );
			} 
else {
				$group = array( 'e.entity_id', 'cis.stock_id', 'cw.website_id' );
			}

			$where = '(cw.website_id <> 0)';
			$select->where( $where )->columns( $columns )->group( $group );

			if (!empty( $$entityIds )) {
				$select->where( 'e.entity_id IN(?)', $entityIds );
			}

			$write->delete( $table );
			$query = $select->insertFromSelect( $table );
			$write->query( $query );
			return $this;
		}

		/**
     * Prepare batch special price index table
     *
     * @param int|array $entityIds the entity ids limitation
     * 
     * @return Mage_Catalog_Model_Resource_Product_Indexer_Price
     */
		function _prepareBatchSpecialPriceIndex($entityIds = null) {
			$helper = $this->getWarehouseHelper(  );
			$indexerHelper = $this->getProductPriceIndexerHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$table = $indexerHelper->getBatchSpecialPriceIndexTable(  );
			$specialPrice = $select = $write->select(  )->from( array( 'e' => $this->getTable( 'catalog/product' ) ), array(  ) )->join( array( 'cis' => $this->getTable( 'cataloginventory/stock' ) ), '', array(  ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), '', array(  ) )->join( array( 'cwd' => $this->_getWebsiteDateTable(  ) ), '(cw.website_id = cwd.website_id)', array(  ) )->join( array( 'csg' => $this->getTable( 'core/store_group' ) ), 'csg.website_id = cw.website_id AND cw.default_group_id = csg.group_id', array(  ) )->join( array( 'cs' => $this->getTable( 'core/store' ) ), 'csg.default_store_id = cs.store_id AND cs.store_id != 0', array(  ) )->join( array( 'pw' => $this->getTable( 'catalog/product_website' ) ), 'pw.product_id = e.entity_id AND pw.website_id = cw.website_id', array(  ) );
			$select->joinLeft( array( 'cbgp' => $this->getTable( 'catalog/product_batch_special_price' ) ), implode( ' AND ', array( '(cbgp.product_id = e.entity_id)', '(cbgp.stock_id = cis.stock_id)', '(cbgp.website_id = 0)' ) ), array(  ) );

			if ($helper->getProductPriceHelper(  )->isWebsiteScope(  )) {
				$select->joinLeft( array( 'cbp' => $this->getTable( 'catalog/product_batch_special_price' ) ), implode( ' AND ', array( '(cbp.product_id = e.entity_id)', '(cbp.stock_id = cis.stock_id)', '(cbp.website_id = cw.website_id)' ) ), array(  ) );
			}


			if ($helper->getProductPriceHelper(  )->isWebsiteScope(  )) {
				$price = new Zend_Db_Expr( 'IF (cbp.price IS NOT NULL, cbp.price, IF (cbgp.price IS NOT NULL, ROUND(cbgp.price * cwd.rate, 4), ' . $specialPrice . '))' );
			} 
else {
				$price = new Zend_Db_Expr( ( 'IF (cbgp.price IS NOT NULL, ROUND(cbgp.price * cwd.rate, 4), ' . $specialPrice . ')' ) );
			}

			$columns = array( 'entity_id' => new Zend_Db_Expr( 'e.entity_id' ), 'stock_id' => new Zend_Db_Expr( ($isMultipleMode ? $helper->getDefaultStockId(  ) : 'cis.stock_id') ), 'website_id' => new Zend_Db_Expr( 'cw.website_id' ), 'price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MAX(' . $price . ')' ) : $price) ), 'min_price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MIN(' . $price . ')' ) : 'NULL') ), 'max_price' => new Zend_Db_Expr( ($isMultipleMode ? ( 'MAX(' . $price . ')' ) : 'NULL') ) );

			if ($isMultipleMode) {
				$group = array( 'e.entity_id', 'cw.website_id' );
			} 
else {
				$group = array( 'e.entity_id', 'cis.stock_id', 'cw.website_id' );
			}

			$where = '(cw.website_id <> 0)';
			$select->where( $where )->columns( $columns )->group( $group );

			if (!empty( $$entityIds )) {
				$select->where( 'e.entity_id IN(?)', $entityIds );
			}

			$write->delete( $table );
			$select->insertFromSelect( $table );
			$query = $this->_addAttributeToSelect( $select, 'special_price', 'e.entity_id', 'cs.store_id' );
			$write->query( $query );
			return $this;
		}

		/**
     * Prepare tier price index table
     *
     * @param int|array $entityIds the entity ids limitation
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Indexer_Price
     */
		function _prepareTierPriceIndex($entityIds = null) {
			$helper = $this->getWarehouseHelper(  );
			$isMultipleMode = $helper->isMultipleMode(  );
			$write = $this->_getWriteAdapter(  );
			$table = $this->_getTierPriceIndexTable(  );
			$write->delete( $table );

			if ($this->getVersionHelper(  )->isGe1600(  )) {
				$price = $write->getCheckSql( 'tp.website_id = 0', 'ROUND(tp.value * cwd.rate, 4)', 'tp.value' );
			} 
else {
				$price = new Zend_Db_Expr( 'IF (tp.website_id=0, ROUND(tp.value * cwd.rate, 4), tp.value)' );
			}


			if ($isMultipleMode) {
				$group = array( 'tp.entity_id', 'cg.customer_group_id', 'cw.website_id' );
			} 
else {
				$group = array( 'tp.entity_id', 'cg.customer_group_id', 'cw.website_id', 'cis.stock_id' );
			}

			$columns = array( 'entity_id' => 'tp.entity_id', 'customer_group_id' => 'cg.customer_group_id', 'website_id' => 'cw.website_id', 'stock_id' => new Zend_Db_Expr( ($isMultipleMode ? $helper->getDefaultStockId(  ) : 'cis.stock_id') ), 'min_price' => new Zend_Db_Expr( ( 'MIN(' . $price . ')' ) ) );
			$select = $write->select(  )->from( array( 'tp' => $this->getValueTable( 'catalog/product', 'tier_price' ) ), array(  ) )->join( array( 'cg' => $this->getTable( 'customer/customer_group' ) ), 'tp.all_groups = 1 OR (tp.all_groups = 0 AND tp.customer_group_id = cg.customer_group_id)', array(  ) )->join( array( 'cw' => $this->getTable( 'core/website' ) ), 'tp.website_id = 0 OR tp.website_id = cw.website_id', array(  ) )->join( array( 'cwd' => $this->_getWebsiteDateTable(  ) ), 'cw.website_id = cwd.website_id', array(  ) )->join( array( 'cis' => $this->getTable( 'cataloginventory/stock' ) ), '(tp.stock_id IS NULL) OR (tp.stock_id = cis.stock_id)', array(  ) )->where( 'cw.website_id != 0' )->columns( $columns )->group( $group );

			if (!empty( $$entityIds )) {
				$select->where( 'tp.entity_id IN(?)', $entityIds );
			}

			$query = $select->insertFromSelect( $table );
			$write->query( $query );
			return $this;
		}
	}

?>