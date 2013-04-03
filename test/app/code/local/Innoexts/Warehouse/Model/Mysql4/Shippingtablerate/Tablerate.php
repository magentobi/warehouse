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

	class Innoexts_Warehouse_Model_Mysql4_Shippingtablerate_Tablerate extends Innoexts_ShippingTablerate_Model_Mysql4_Tablerate {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Load table rate by request
     * 
     * @param Innoexts_ShippingTablerate_Model_Tablerate $tablerate
     * @param Varien_Object $request
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Shippingtablerate_Tablerate
     */
		function loadByRequest($tablerate, $request) {
			$adapter = $this->_getReadAdapter(  );
			$select = $adapter->select(  )->from( $this->getMainTable(  ) );
			$conditions = array(  );

			if ($request->getId(  )) {
				array_push( $conditions, '(pk != ' . $adapter->quote( $request->getId(  ) ) . ')' );
			}

			$websiteId = ($request->getWebsiteId(  ) ? $request->getWebsiteId(  ) : '0');
			$warehouseId = ($request->getWarehouseId(  ) ? $request->getWarehouseId(  ) : '0');
			$destCountryId = ($request->getDestCountryId(  ) ? $request->getDestCountryId(  ) : '0');
			$destRegionId = ($request->getDestRegionId(  ) ? $request->getDestRegionId(  ) : '0');
			$destZip = ($request->getDestZip(  ) ? $request->getDestZip(  ) : '');
			$conditionName = ($request->getConditionName(  ) ? $request->getConditionName(  ) : '');
			$conditionValue = ($request->getConditionValue(  ) ? $request->getConditionValue(  ) : '');
			array_push( $conditions, '(website_id = ' . $adapter->quote( $websiteId ) . ')' );
			array_push( $conditions, '(warehouse_id = ' . $adapter->quote( $warehouseId ) . ')' );
			array_push( $conditions, '(dest_country_id = ' . $adapter->quote( $destCountryId ) . ')' );
			array_push( $conditions, '(dest_region_id = ' . $adapter->quote( $destRegionId ) . ')' );
			array_push( $conditions, '(dest_zip = ' . $adapter->quote( $destZip ) . ')' );
			array_push( $conditions, '(condition_name = ' . $adapter->quote( $conditionName ) . ')' );
			array_push( $conditions, '(condition_value = ' . $adapter->quote( $conditionValue ) . ')' );
			$select->where( implode( ' AND ', $conditions ) );
			$select->limit( 1 );
			$row = $adapter->fetchRow( $select );

			if (( $row && !empty( $$row ) )) {
				$tablerate->setData( $row );
			}

			$this->_afterLoad( $tablerate );
			return $this;
		}
	}

?>