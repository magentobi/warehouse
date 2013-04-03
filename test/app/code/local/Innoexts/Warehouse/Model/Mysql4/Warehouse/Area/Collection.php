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

	class Innoexts_Warehouse_Model_Mysql4_Warehouse_Area_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
		private $_countryTable = null;
		private $_regionTable = null;

		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'warehouse/warehouse_area' );
			$this->_countryTable = $this->getTable( 'directory/country' );
			$this->_regionTable = $this->getTable( 'directory/country_region' );
		}

		/**
     * Initialize select, add country iso3 code and region name
     *
     * @return void
     */
		function _initSelect() {
			parent::_initSelect(  );
			$this->_select->joinLeft( array( 'country_table' => $this->_countryTable ), 'country_table.country_id = main_table.country_id', array( 'country' => 'iso2_code' ) )->joinLeft( array( 'region_table' => $this->_regionTable ), 'region_table.region_id = main_table.region_id', array( 'region' => 'code' ) );
		}

		/**
     * Add warehouse filter to collection
     *
     * @param int $warehouseId
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Warehouse_Area_Collection
     */
		function setWarehouseFilter($warehouseId) {
			return $this->addFieldToFilter( 'warehouse_id', $warehouseId );
		}
	}

?>