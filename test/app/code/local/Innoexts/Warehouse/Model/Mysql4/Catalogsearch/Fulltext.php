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

	class Innoexts_Warehouse_Model_Mysql4_Catalogsearch_Fulltext extends Mage_CatalogSearch_Model_Mysql4_Fulltext {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
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
     * Retrieve searchable products per store
     *
     * @param int $storeId
     * @param array $staticFields
     * @param array|int $productIds
     * @param int $lastProductId
     * @param int $limit
     * 
     * @return array
     */
		function _getSearchableProducts($storeId, $staticFields, $productIds = null, $lastProductId = 0, $limit = 100) {
			$store = Mage::app(  )->getStore( $storeId );
			$adapter = $this->_getWriteAdapter(  );
			$conditionSelect = $adapter->select(  );
			$conditionSelect->from( array( 'stock_status_2' => $this->getTable( 'cataloginventory/stock_status' ) ), array( 'stock_id' ) );
			$conditionSelect->where( '(stock_status_2.product_id = stock_status.product_id) AND ' . '(stock_status_2.website_id = stock_status.website_id)' );
			$conditionSelect->order( 'stock_status_2.stock_status DESC' );
			$conditionSelect->limit( 1 );
			$adapter->select(  )->useStraightJoin( true )->from( array( 'e' => $this->getTable( 'catalog/product' ) ), array_merge( array( 'entity_id', 'type_id' ), $staticFields ) )->join( array( 'website' => $this->getTable( 'catalog/product_website' ) ), 'website.product_id=e.entity_id AND website.website_id = ' . $adapter->quote( $store->getWebsiteId(  ) ), array(  ) )->join( array( 'stock_status' => $this->getTable( 'cataloginventory/stock_status' ) ), '(stock_status.product_id=e.entity_id) AND ' . '(stock_status.website_id = ' . $adapter->quote( $store->getWebsiteId(  ) ) . ') AND ' . '(stock_status.stock_id = (' . $conditionSelect->assemble(  ) . '))', array( 'in_stock' => 'stock_status' ) );

			if (!is_null( $productIds )) {
				$select->where( 'e.entity_id IN(?)', $productIds );
			}

			$select->where( 'e.entity_id > ?', $lastProductId )->limit( $limit )->order( 'e.entity_id' );
			$result = $select = $adapter->fetchAll( $select );

			if ($this->getVersionHelper(  )->isGe1700(  )) {
				return $result;
			}


			if (( ( $this->_engine && $this->_engine->allowAdvancedIndex(  ) ) && 0 < count( $result ) )) {
				return $this->_engine->addAdvancedIndex( $result, $storeId, $productIds );
			}

			return $result;
		}
	}

?>