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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Inventory extends Mage_Adminhtml_Block_Widget_Form {
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
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Inventory
     */
		function _prepareForm() {
			$helper = $this->getWarehouseHelper(  );
			$product = $this->getProduct(  );
			$form = new Varien_Data_Form(  );
			$form->setFieldNameSuffix( 'product' );
			$form->addFieldset( 'multipleinventory', array( 'legend' => $helper->__( 'Inventory' ) ) );
			$fieldset = $form->setHtmlIdPrefix( 'product_' );
			$stocksDataElement = $fieldset->addField( 'stocks_data', 'text', array( 'name' => 'stocks_data', 'label' => $helper->__( 'Inventory' ), 'title' => $helper->__( 'Inventory' ), 'required' => true, 'class' => 'requried-entry', 'value' => $product->getStocksItems(  ) ) );
			$stocksDataElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_catalog_product_edit_tab_inventory_renderer' ) );
			$this->setForm( $form );
			return parent::_prepareForm(  );
		}
	}

?>