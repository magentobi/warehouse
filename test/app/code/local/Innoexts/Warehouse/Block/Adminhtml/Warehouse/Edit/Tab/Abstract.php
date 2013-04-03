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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract extends Innoexts_Core_Block_Adminhtml_Widget_Form {
		private $_formFieldNameSuffix = 'warehouse';
		private $_formHtmlIdPrefix = 'warehouse_';
		private $_formFieldsetId = null;
		private $_formFieldsetLegend = 'Tab';
		private $_modelName = 'warehouse';
		private $_title = 'Tab';

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

		/**
     * Return Tab label
     *
     * @return string
     */
		function getTabLabel() {
			return $this->getWarehouseHelper(  )->__( $this->_title );
		}

		/**
     * Return Tab title
     *
     * @return string
     */
		function getTabTitle() {
			return $this->getWarehouseHelper(  )->__( $this->_title );
		}

		/**
     * Can show tab in tabs
     *
     * @return boolean
     */
		function canShowTab() {
			return true;
		}

		/**
     * Tab is hidden
     *
     * @return boolean
     */
		function isHidden() {
			return false;
		}
	}

?>