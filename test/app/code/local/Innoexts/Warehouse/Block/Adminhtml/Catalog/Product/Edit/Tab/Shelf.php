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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Shelf extends Mage_Adminhtml_Block_Widget_Form {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
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
     * Prepare form before rendering HTML
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Shelf
     */
		function _prepareForm() {
			$product = $this->getProduct(  );
			$helper = $this->getWarehouseHelper(  );
			$form = new Varien_Data_Form(  );
			$form->setHtmlIdPrefix( 'product_' );
			$fieldset = $form->setFieldNameSuffix( 'product' );
			$fieldset->addField( 'shelves', 'text', array( 'name' => 'shelves', 'label' => $helper->__( 'Shelves' ), 'title' => $helper->__( 'Shelves' ), 'required' => false, 'value' => $product->getShelves(  ) ) );
			$shelvesElement = $form->addFieldset( 'shelves_info', array( 'legend' => $helper->__( 'Shelves' ) ) );
			$shelvesElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_catalog_product_edit_tab_shelf_renderer' ) );
			$this->setForm( $form );
			return parent::_prepareForm(  );
		}
	}

?>