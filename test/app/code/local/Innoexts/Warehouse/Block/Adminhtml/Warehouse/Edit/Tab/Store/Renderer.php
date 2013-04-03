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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Store_Renderer extends Mage_Adminhtml_Block_Widget {
		private $_element = null;

		/**
     * Constructor
     */
		function __construct() {
			$this->setTemplate( 'warehouse/warehouse/edit/tab/store/renderer.phtml' );
		}

		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Set form element
     * 
     * @param Varien_Data_Form_Element_Abstract $element
     */
		function setElement($element) {
			$this->_element = $element;
			return $this;
		}

		/**
     * Get form element
     * 
     * @return Varien_Data_Form_Element_Abstract
     */
		function getElement() {
			return $this->_element;
		}

		/**
     * Render block
     * 
     * @param Varien_Data_Form_Element_Abstract $element
     * 
     * @return string
     */
		function render($element) {
			$this->setElement( $element );
			return $this->toHtml(  );
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
     * Get websites
     * 
     * @return array
     */
		function getWebsites() {
			return Mage::app(  )->getWebsites(  );
		}
	}

?>