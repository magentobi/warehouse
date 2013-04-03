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

	class Innoexts_Warehouse_Block_Bundle_Adminhtml_Catalog_Product_Edit_Tab_Attributes extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes {
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
			$product = $this->getProduct(  );

			if (( ( $product->getPriceType(  ) == PRICE_TYPE_FIXED && $form->getElement( 'price' ) ) && $this->getGroup(  ) )) {
				$group = $this->getGroup(  );
				$fieldset = $form->getElement( 'group_fields' . $group->getId(  ) );

				if ($fieldset) {
					$fieldset->addField( 'batch_prices', 'text', array( 'name' => 'batch_prices', 'label' => $helper->__( 'Batch Price' ), 'title' => $helper->__( 'Batch Price' ), 'required' => false, 'value' => $product->getBatchPrices(  ) ), 'price' );
					$form->getElement( 'batch_prices' )->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_catalog_product_edit_tab_batchprice_renderer' ) );
				}
			}

			return $this;
		}
	}

?>