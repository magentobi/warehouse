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

	class Innoexts_Warehouse_Block_Adminhtml_Shippingtablerate_Tablerate_Grid extends Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid {
		/**
     * Retrieve warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get warehouses options
     * 
     * @return array
     */
		function getWarehousesOptions() {
			$helper = $this->getWarehouseHelper(  );
			$options = array(  );
			$warehouses = $helper->getWarehousesOptions( false, '*', '0' );
			foreach ($warehouses as $warehouse) {
				$options[$warehouse['value']] = $warehouse['label'];
			}

			return $options;
		}

		/**
     * Prepare columns
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Shippingtablerate_Tablerate_Grid
     */
		function _prepareColumns() {
			$helper = $this->getWarehouseHelper(  );
			$this->addColumn( 'warehouse_id', array( 'header' => $helper->__( 'Warehouse' ), 'align' => 'left', 'index' => 'warehouse_id', 'type' => 'options', 'options' => $this->getWarehousesOptions(  ) ) );
			parent::_prepareColumns(  );
			return $this;
		}
	}

?>