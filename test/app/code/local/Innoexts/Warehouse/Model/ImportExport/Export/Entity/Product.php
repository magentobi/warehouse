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

	class Innoexts_Warehouse_Model_ImportExport_Export_Entity_Product extends Mage_ImportExport_Model_Export_Entity_Product {
		/**
     * Get warehouse helper
     * 
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Prepare catalog inventory
     *
     * @param  array $productIds
     * 
     * @return array
     */
		function _prepareCatalogInventory($productIds) {
			$select = $this->_connection->select(  )->from( Mage::getResourceModel( 'cataloginventory/stock_item' )->getMainTable(  ) )->where( 'product_id IN (?)', $productIds );
			$select->where( 'stock_id=' . $this->getWarehouseHelper(  )->getDefaultStockId(  ) );
			$stmt = $this->_connection->query( $select );
			$stockItemRows = array(  );

			if ($stockItemRow = $stmt->fetch(  )) {
				$productId = $stockItemRow['product_id'];
				unset( $stockItemRow[item_id] );
				unset( $stockItemRow[product_id] );
				unset( $stockItemRow[low_stock_date] );
				unset( $stockItemRow[stock_id] );
				unset( $stockItemRow[stock_status_changed_automatically] );
				$stockItemRows[$productId] = $stockItemRow;
			}

			return $stockItemRows;
		}
	}

?>