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

	class Innoexts_Warehouse_Block_Adminhtml_Shipping_Carrier_Tablerate_Grid extends Mage_Adminhtml_Block_Shipping_Carrier_Tablerate_Grid {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Prepare page
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Shipping_Carrier_Tablerate_Grid
     */
		function _preparePage() {
			$this->getCollection(  )->getSelect(  )->order( array( 'warehouse_id', 'dest_country_id', 'dest_region_id', 'dest_zip', 'condition_value', 'price' ) );
			parent::_preparePage(  );
			return $this;
		}

		/**
     * Prepare table columns
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Shipping_Carrier_Tablerate_Grid
     */
		function _prepareColumns() {
			$this->addColumn( 'warehouse_id', array( 'header' => $this->getWarehouseHelper(  )->__( 'Warehouse' ), 'index' => 'warehouse_id', 'default' => '*' ) );
			return parent::_prepareColumns(  );
		}
	}

?>