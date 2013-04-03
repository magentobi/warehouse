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

	class Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Zone_Price extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Container {
		private $_gridBlockType = 'warehouseplus/adminhtml_catalog_product_edit_tab_zone_price_grid';
		private $_formBlockType = 'warehouseplus/adminhtml_catalog_product_edit_tab_zone_price_form';
		private $_title = 'Zone Discounts';

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'productZonePriceTab' );
			$this->setTemplate( 'warehouseplus/catalog/product/edit/tab/zone/price.phtml' );
		}

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return Mage::helper( 'warehouseplus' );
		}

		/**
     * Retrieve registered product model
     *
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			return Mage::registry( 'product' );
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
			return $this->getTextHelper(  )->__( $this->_title );
		}

		/**
     * Return Tab title
     * 
     * @return string
     */
		function getTabTitle() {
			return $this->getTextHelper(  )->__( $this->_title );
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
     * Check is allowed action
     * 
     * @param   string $action
     * 
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'catalog/products' );
		}

		/**
     * Check if edit function enabled
     * 
     * @return bool
     */
		function canEdit() {
			return (( $this->isSaveAllowed(  ) && $this->getProduct(  )->getId(  ) ) ? true : false);
		}
	}

?>