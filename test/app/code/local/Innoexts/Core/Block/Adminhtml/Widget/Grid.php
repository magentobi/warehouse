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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid {
		private $_objectId = null;

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return $this;
		}

		/**
     * Get object identifier
     * 
     * @return string
     */
		function getObjectId() {
			return $this->_objectId;
		}

		/**
     * Prepare collection object
     *
     * @return Varien_Data_Collection
     */
		function __prepareCollection() {
		}

		/**
     * Prepare collection object
     *
     * @return Innoexts_Core_Block_Adminhtml_Widget_Grid
     */
		function _prepareCollection() {
			$collection = $this->__prepareCollection(  );
			$this->setCollection( $collection );
			parent::_prepareCollection(  );
			return $this;
		}

		/**
     * Get row URL
     * 
     * @param   Varien_Object $row
     * 
     * @return  string
     */
		function getRowUrl($row) {
			return $this->getUrl( '*/*/edit', array( $this->getObjectId(  ) => $row->getId(  ) ) );
		}

		/**
     * Get grid URL
     * 
     * @return string
     */
		function getGridUrl() {
			return $this->getUrl( '*/*/grid', array( '_current' => true ) );
		}

		/**
     * Get admin session
     * 
     * @return Mage_Admin_Model_Session
     */
		function getAdminSession() {
			return Mage::getSingleton( 'admin/session' );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * 
     * @return  bool
     */
		function isAllowedAction($action) {
			return true;
		}

		/**
     * Check if view action allowed
     * 
     * @return bool
     */
		function isViewAllowed() {
			return $this->isAllowedAction( 'view' );
		}
	}

?>