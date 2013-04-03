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

	class Innoexts_ShippingTablerate_Adminhtml_TablerateController extends Innoexts_Core_Controller_Adminhtml_Action {
		private $_modelNames = array( 'shippingtablerate' => 'shippingtablerate/tablerate' );

		/**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getShippingTablerateHelper() {
			return Mage::helper( 'shippingtablerate' );
		}

		/**
     * Get website id
     * 
     * @return integer
     */
		function getWebsiteId() {
			return $this->getShippingTablerateHelper(  )->getWebsiteId(  );
		}

		/**
     * Set redirect into responce
     *
     * @param   string $path
     * @param   array $arguments
     * 
     * @return Innoexts_ShippingTablerate_Adminhtml_TablerateController
     */
		function _redirect($path, $arguments = array(  )) {
			$arguments = array_merge( array( 'website' => $this->getWebsiteId(  ) ), $arguments );
			parent::_redirect( $path, $arguments );
			return $this;
		}

		/**
     * Get model
     * 
     * @param string $type
     * 
     * @return Mage_Core_Model_Abstract
     */
		function _getModel($type) {
			$model = parent::_getModel( $type );
			$model->setWebsiteId( $this->getWebsiteId(  ) );
			return $model;
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
					return $adminSession->isAllowed( 'sales/shipping/tablerates/save' );
				}

				case 'delete': {
					return $adminSession->isAllowed( 'sales/shipping/tablerates/delete' );
				}
			}

			return $adminSession->isAllowed( 'sales/shipping/tablerates' );
		}

		/**
     * Index action
     */
		function indexAction() {
			$helper = $this->getShippingTablerateHelper(  );
			$this->_indexAction( 'shippingtablerate', false, 'sales/shipping/tablerates', array( $helper->__( 'Sales' ), $helper->__( 'Shipping' ), $helper->__( 'Shipping Table Rates' ) ) );
		}

		/**
     * Grid action
     */
		function gridAction() {
			$this->_gridAction( 'shippingtablerate', true );
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
			$helper = $this->getShippingTablerateHelper(  );
			$this->_editAction( 'shippingtablerate', false, 'sales/shipping/tablerates', 'tablerate_id', '', $helper->__( 'New Rate' ), $helper->__( 'Edit Rate' ), array( $helper->__( 'Sales' ), $helper->__( 'Shipping' ), $helper->__( 'Shipping Table Rates' ) ), $helper->__( 'This rate no longer exists.' ) );
		}

		/**
     * Save action
     */
		function saveAction() {
			$helper = $this->getShippingTablerateHelper(  );
			$this->_saveAction( 'shippingtablerate', false, 'tablerate_id', '', 'edit', $helper->__( 'The rate has been saved.' ), $helper->__( 'An error occurred while saving the rate.' ) );
		}

		/**
     * Delete action
     */
		function deleteAction() {
			$helper = $this->getShippingTablerateHelper(  );
			$this->_deleteAction( 'shippingtablerate', false, 'tablerate_id', '', 'edit', $helper->__( 'Unable to find a rate to delete.' ), $helper->__( 'The rate has been deleted.' ) );
		}

		/**
     * Mass delete action
     */
		function massDeleteAction() {
			$helper = $this->getShippingTablerateHelper(  );
			$this->_massDeleteAction( 'shippingtablerate', false, 'tablerate_id', '', $helper->__( 'Please select rate(s).' ), $helper->__( 'Total of %d record(s) have been deleted.' ) );
		}

		/**
     * Export rates to CSV format
     */
		function exportCsvAction() {
			$this->_exportCsvAction( 'shipping_table_rates.csv', 'shippingtablerate/adminhtml_tablerate_grid' );
		}

		/**
     * Export rates to XML format
     */
		function exportXmlAction() {
			$this->_exportXmlAction( 'shipping_table_rates.xml', 'shippingtablerate/adminhtml_tablerate_grid' );
		}
	}

?>