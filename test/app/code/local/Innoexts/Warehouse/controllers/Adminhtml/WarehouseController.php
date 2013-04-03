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

	class Innoexts_Warehouse_Adminhtml_WarehouseController extends Innoexts_Core_Controller_Adminhtml_Action {
		private $_modelNames = array( 'warehouse' => 'warehouse/warehouse', 'warehouse_area' => 'warehouse/warehouse_area' );

		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Check is allowed action
     * 
     * @return bool
     */
		function _isAllowed() {
			$adminSession = $this->getAdminSession(  );
			switch ($this->getRequest(  )->getActionName(  )) {
				case 'new': {
				}

				case 'save': {
					return $adminSession->isAllowed( 'catalog/warehouses/save' );
				}

				case 'delete': {
					return $adminSession->isAllowed( 'catalog/warehouses/delete' );
				}
			}

			return $adminSession->isAllowed( 'catalog/warehouses' );
		}

		/**
     * Index action
     */
		function indexAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_indexAction( 'warehouse', false, 'catalog/warehouses', array( $helper->__( 'Catalog' ), $helper->__( 'Manage Warehouses' ) ) );
		}

		/**
     * Grid action
     */
		function gridAction() {
			$this->_gridAction( 'warehouse', true );
		}

		/**
     * New action
     */
		function newAction() {
			$this->_forward( 'edit' );
		}

		/**
     * Edit action
     */
		function editAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_editAction( 'warehouse', false, 'catalog/warehouses', 'warehouse_id', '', $helper->__( 'New Warehouse' ), $helper->__( 'Edit Warehouse' ), array( $helper->__( 'Catalog' ), $helper->__( 'Manage Warehouses' ) ), $helper->__( 'This warehouse no longer exists.' ) );
		}

		/**
     * Save action
     */
		function saveAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_saveAction( 'warehouse', false, 'warehouse_id', '', 'edit', $helper->__( 'The warehouse has been saved.' ), $helper->__( 'An error occurred while saving the warehouse.' ) );
		}

		/**
     * Delete action
     */
		function deleteAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_deleteAction( 'warehouse', false, 'warehouse_id', '', 'edit', $helper->__( 'Unable to find a warehouse to delete.' ), $helper->__( 'The warehouse has been deleted.' ) );
		}

		/**
     * Initialize warehouse
     * 
     * @return Innoexts_Warehouse_Adminhtml_WarehouseController
     */
		function _initWarehouse() {
			$helper = $this->getWarehouseHelper(  );
			$this->_initModel( 'warehouse', true, 'warehouse_id', '', $helper->__( 'This warehouse no longer exists.' ) );
			return $this;
		}

		/**
     * Get products grid
     */
		function productsGridAction() {
			$this->_initWarehouse(  );
			$this->_gridAction( 'catalog_product', true );
		}

		/**
     * Get sales orders grid
     */
		function salesOrdersGridAction() {
			$this->_initWarehouse(  );
			$this->_gridAction( 'sales_order', true );
		}

		/**
     * Get sales invoices grid
     */
		function salesInvoicesGridAction() {
			$this->_initWarehouse(  );
			$this->_gridAction( 'sales_invoice', true );
		}

		/**
     * Get sales shipments grid
     */
		function salesShipmentsGridAction() {
			$this->_initWarehouse(  );
			$this->_gridAction( 'sales_shipment', true );
		}

		/**
     * Get sales credit memos grid
     */
		function salesCreditmemosGridAction() {
			$this->_initWarehouse(  );
			$this->_gridAction( 'sales_creditmemo', true );
		}

		/**
     * Get area grid
     */
		function areaGridAction() {
			$this->_initWarehouse(  );
			$this->_gridAction( 'warehouse_area', true );
		}

		/**
     * Edit area action
     */
		function editAreaAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_editAction( 'warehouse_area', true, null, 'warehouse_area_id', null, null, null, array(  ), $helper->__( 'This area no longer exists.' ) );
			return $this;
		}

		/**
     * Prepare save
     * 
     * @param string $type
     * @param Mage_Core_Model_Abstract $model
     * 
     * @return Innoexts_Warehouse_Adminhtml_WarehouseController
     */
		function _prepareSave($type, $model) {
			if ($type == 'warehouse_area') {
				$warehouseId = $this->getRequest(  )->getParam( 'warehouse_id' );
				$model->setWarehouseId( $warehouseId );
			}

			return $this;
		}

		/**
     * Save area action
     */
		function saveAreaAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_saveAction( 'warehouse_area', true, 'warehouse_area_id', null, null, $helper->__( 'The area has been saved.' ), $helper->__( 'An error occurred while saving the area: %s.' ) );
			return $this;
		}

		/**
     * Delete area action
     */
		function deleteAreaAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_deleteAction( 'warehouse_area', true, 'warehouse_area_id', null, null, $helper->__( 'This warehouse no longer exists.' ), $helper->__( 'The area has been deleted.' ) );
		}
	}

?>