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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Container {
		private $_blockGroup = 'warehouse';
		private $_controller = 'adminhtml_warehouse';
		private $_headerLabel = 'Manage Warehouses';
		private $_addLabel = 'Add New Warehouse';

		/**
     * Retrieve warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * 
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'catalog/warehouses/' . $action );
		}
	}

?>