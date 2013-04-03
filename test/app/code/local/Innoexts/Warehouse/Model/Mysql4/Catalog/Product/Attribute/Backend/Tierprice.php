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

	class Innoexts_Warehouse_Model_Mysql4_Catalog_Product_Attribute_Backend_Tierprice extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Tierprice {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Load tier prices for product
     *
     * @param int $productId
     * @param int $websiteId
     * @param string $stockId
     * 
     * @return array
     */
		function loadPriceData2($productId, $websiteId = null, $stockId = null) {
			$adapter = $this->_getReadAdapter(  );
			$columns = array( 'price_id' => $this->getIdFieldName(  ), 'website_id' => 'website_id', 'stock_id' => 'stock_id', 'all_groups' => 'all_groups', 'cust_group' => 'customer_group_id', 'price_qty' => 'qty', 'price' => 'value' );
			$select = $adapter->select(  )->from( $this->getMainTable(  ), $columns )->where( 'entity_id=?', $productId )->order( 'qty' );

			if (!is_null( $websiteId )) {
				if ($websiteId == '0') {
					$select->where( 'website_id = ?', $websiteId );
				} 
else {
					$select->where( 'website_id IN(?)', array( 0, $websiteId ) );
				}
			}


			if (!is_null( $stockId )) {
				if ($stockId) {
					$select->where( 'stock_id = ?', $stockId );
				} 
else {
					$select->where( '(stock_id IS NULL) OR (stock_id = ?)', $stockId );
				}
			}

			return $adapter->fetchAll( $select );
		}

		/**
     * Delete tier prices
     *
     * @param int $productId
     * @param int $websiteId
     * @param int $priceId
     * 
     * @return int number of affected rows
     */
		function deletePriceData2($productId, $websiteId = null, $priceId = null) {
			$adapter = $this->_getWriteAdapter(  );
			$conds = array( $adapter->quoteInto( 'entity_id = ?', $productId ) );

			if (!is_null( $websiteId )) {
				$conds[] = $adapter->quoteInto( 'website_id = ?', $websiteId );
			}


			if (!is_null( $priceId )) {
				$conds[] = $adapter->quoteInto( $this->getIdFieldName(  ) . ' = ?', $priceId );
			}

			$where = implode( ' AND ', $conds );
			return $adapter->delete( $this->getMainTable(  ), $where );
		}
	}

?>