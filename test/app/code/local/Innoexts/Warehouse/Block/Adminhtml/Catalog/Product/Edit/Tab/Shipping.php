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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Shipping extends Mage_Adminhtml_Block_Widget_Form {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
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
     * Prepare form before rendering HTML
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Shipping
     */
		function _prepareForm() {
			$product = $this->getProduct(  );
			$helper = $this->getWarehouseHelper(  );
			$form = new Varien_Data_Form(  );
			$form->setFieldNameSuffix( 'product' );
			$form->addFieldset( 'shipping', array( 'legend' => $helper->__( 'Shipping Carriers' ) ) );
			$fieldset = $form->setHtmlIdPrefix( 'product_' );
			$stockShippingCarriersElement = $fieldset->addField( 'stock_shipping_carriers', 'text', array( 'name' => 'stock_shipping_carriers', 'label' => $helper->__( 'Shipping Carriers' ), 'title' => $helper->__( 'Shipping Carriers' ), 'required' => false, 'value' => $product->getStockShippingCarriers(  ) ) );
			$stockShippingCarriersElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_catalog_product_edit_tab_stock_shipping_carrier_renderer' ) );
			$this->setForm( $form );
			return parent::_prepareForm(  );
		}
	}

?>