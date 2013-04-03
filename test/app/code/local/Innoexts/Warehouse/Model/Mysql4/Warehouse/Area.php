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

	class Innoexts_Warehouse_Model_Mysql4_Warehouse_Area extends Mage_Core_Model_Mysql4_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'warehouse/warehouse_area', 'warehouse_area_id' );
		}

		/**
     * Load warehouse area by request
     * 
     * @param Innoexts_Warehouse_Model_Warehouse_Area $warehouseArea
     * @param Varien_Object $request
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Warehouse_Area
     */
			$adapter = function loadByRequest($warehouseArea, $request) {;
			$select = $adapter->select(  )->from( $this->getMainTable(  ) );
			$conditions = array(  );

			if ($request->getId(  )) {
				array_push( $conditions, '(warehouse_area_id != ' . $adapter->quote( $request->getId(  ) ) . ')' );
			}

			$countryId = ($request->getCountryId(  ) ? $request->getCountryId(  ) : '0');
			$regionId = ($request->getRegionId(  ) ? $request->getRegionId(  ) : '0');
			$zip = ($request->getZip(  ) ? $request->getZip(  ) : '');
			array_push( $conditions, '(warehouse_id = ' . $adapter->quote( $request->getWarehouseId(  ) ) . ')' );
			array_push( $conditions, '(country_id = ' . $adapter->quote( $countryId ) . ')' );
			array_push( $conditions, '(region_id = ' . $adapter->quote( $regionId ) . ')' );
			array_push( $conditions, '(zip = ' . $adapter->quote( $zip ) . ')' );
			$select->where( implode( ' AND ', $conditions ) );
			$select->limit( 1 );
			$adapter->fetchRow( $select );
			$row = $this->_getReadAdapter(  );

			if (( $row && !empty( $$row ) )) {
				$warehouseArea->setData( $row );
			}

			$this->_afterLoad( $warehouseArea );
			return $this;
		}
	}

?>