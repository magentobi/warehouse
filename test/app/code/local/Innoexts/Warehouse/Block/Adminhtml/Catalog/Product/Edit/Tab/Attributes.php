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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes {
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
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$form = $this->getForm(  );
			$layout = $this->getLayout(  );
			$product = $this->getProduct(  );
			$group = $this->getGroup(  );
			$blockTypePrefix = 'warehouse/adminhtml_catalog_product_edit_tab';

			if (!$group) {
				return $this;
			}

			$fieldset = $form->getElement( 'group_fields' . $group->getId(  ) );

			if (!$fieldset) {
				return $this;
			}


			if ($form->getElement( 'price' )) {
				$batchPricesElement = $fieldset->addField( 'batch_prices', 'text', array( 'name' => 'batch_prices', 'label' => $helper->__( 'Batch Price' ), 'title' => $helper->__( 'Batch Price' ), 'required' => false, 'value' => $product->getBatchPrices(  ) ), 'price' );
				$batchPricesElement->setRenderer( $layout->createBlock( $blockTypePrefix . '_batchprice_renderer' ) );
			}


			if ($form->getElement( 'special_price' )) {
				$batchSpecialPricesElement = $fieldset->addField( 'batch_special_prices', 'text', array( 'name' => 'batch_special_prices', 'label' => $helper->__( 'Batch Special Price' ), 'title' => $helper->__( 'Batch Special Price' ), 'required' => false, 'value' => $product->getBatchSpecialPrices(  ) ), 'special_price' );
				$batchSpecialPricesElement->setRenderer( $layout->createBlock( $blockTypePrefix . '_batchspecialprice_renderer' ) );
			}

			return $this;
		}
	}

?>