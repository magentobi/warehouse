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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Renderer_Abstract extends Mage_Adminhtml_Block_Widget {
		private $_element = null;

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
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Renderer_Abstract
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
     * Get registered product
     *
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			return Mage::registry( 'product' );
		}

		/**
     * Get product identifier
     * 
     * @return int
     */
		function getProductId() {
			$product = $this->getProduct(  );

			if (!$product) {
				return null;
			}

			return (int)$product->getId(  );
		}

		/**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
		function getStore() {
			if (is_null( $this->_store )) {
				$storeId = (int)$this->getRequest(  )->getParam( 'store', 0 );
				$this->_store = Mage::app(  )->getStore( $storeId );
			}

			return $this->_store;
		}

		/**
     * Is product new
     * 
     * @return bool
     */
		function isNew() {
			if ($this->getProductId(  )) {
				return false;
			}

			return true;
		}

		/**
     * Get values
     * 
     * @return array
     */
		function getValues() {
			return array(  );
		}
	}

?>