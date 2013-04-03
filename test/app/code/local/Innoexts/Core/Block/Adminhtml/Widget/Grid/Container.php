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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Container extends Mage_Adminhtml_Block_Widget_Grid_Container {
		private $_headerLabel = null;
		private $_addLabel = null;

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return $this;
		}

		/**
     * Get header label
     * 
     * @return string
     */
		function getHeaderLabel() {
			return $this->_headerLabel;
		}

		/**
     * Get add label
     * 
     * @return string
     */
		function getAddLabel() {
			return $this->_addLabel;
		}

		/**
     * Get admin session
     * 
     * @return @return Mage_Admin_Model_Session
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
     * Check if save action allowed
     * 
     * @return bool
     */
		function isSaveAllowed() {
			return $this->isAllowedAction( 'save' );
		}

		/**
     * Check if delete action allowed
     * 
     * @return bool
     */
		function isDeleteAllowed() {
			return $this->isAllowedAction( 'delete' );
		}

		/**
     * Add buttons
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Grid_Container
     */
		function _addButtons() {
			if ($this->isSaveAllowed(  )) {
				$this->_updateButton( 'add', 'label', $this->getTextHelper(  )->__( $this->getAddLabel(  ) ) );
			} 
else {
				$this->_removeButton( 'add' );
			}

			return $this;
		}

		/**
     * Constructor
     */
		function __construct() {
			$this->_headerText = $this->getTextHelper(  )->__( $this->getHeaderLabel(  ) );
			parent::__construct(  );
			$this->_addButtons(  );
		}
	}

?>