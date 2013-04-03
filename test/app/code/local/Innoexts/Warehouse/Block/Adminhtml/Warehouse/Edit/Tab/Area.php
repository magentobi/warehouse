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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Area extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Container {
		private $_gridBlockType = 'warehouse/adminhtml_warehouse_edit_tab_area_grid';
		private $_formBlockType = 'warehouse/adminhtml_warehouse_edit_tab_area_form';
		private $_title = 'Areas';

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'warehouseAreaTab' );
			$this->setTemplate( 'warehouse/warehouse/edit/tab/area.phtml' );
		}

		/**
     * Get warehouse helper
     * 
     * @return Varien_Object
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve Tab class
     * 
     * @return string
     */
		function getTabClass() {
			return 'ajax';
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

		/**
     * Retrieve registered warehouse
     *
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			return Mage::registry( 'warehouse' );
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
     * Check if edit function enabled
     * 
     * @return bool
     */
		function canEdit() {
			$warehouse = $this->getWarehouse(  );
			return (( $this->isSaveAllowed(  ) && $warehouse->getId(  ) ) ? true : false);
		}
	}

?>