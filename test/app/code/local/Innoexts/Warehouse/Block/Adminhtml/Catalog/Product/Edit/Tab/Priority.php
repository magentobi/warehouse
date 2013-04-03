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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Priority extends Mage_Adminhtml_Block_Widget_Form {
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
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Priority
     */
		function _prepareForm() {
			$product = $this->getProduct(  );
			$helper = $this->getWarehouseHelper(  );
			$form = new Varien_Data_Form(  );
			$form->setHtmlIdPrefix( 'product_' );
			$form->setFieldNameSuffix( 'product' );
			$fieldset = $form->addFieldset( 'priority', array( 'legend' => $helper->__( 'Priority' ) ) );
			$stockPrioritiesElement = $fieldset->addField( 'stock_priorities', 'text', array( 'name' => 'stock_priorities', 'label' => $helper->__( 'Warehouse Priority' ), 'title' => $helper->__( 'Warehouse Priority' ), 'required' => false, 'value' => $product->getStockPriorities(  ) ) );
			$stockPrioritiesElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_catalog_product_edit_tab_stock_priority_renderer' ) );
			$this->setForm( $form );
			return parent::_prepareForm(  );
		}
	}

?>