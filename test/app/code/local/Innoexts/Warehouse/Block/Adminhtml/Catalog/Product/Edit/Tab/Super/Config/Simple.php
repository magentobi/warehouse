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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Super_Config_Simple extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Config_Simple {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Prepare form
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Super_Config_Simple
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$form = $this->getForm(  );
			$fieldset = $form->getElement( 'simple_product' );

			if (!$fieldset) {
				return $this;
			}

			$fieldset->removeField( 'simple_product_inventory_qty' );
			$fieldset->removeField( 'simple_product_inventory_is_in_stock' );
			$stockHiddenFields = array( 'use_config_min_qty', 'use_config_min_sale_qty', 'use_config_max_sale_qty', 'use_config_backorders', 'use_config_notify_stock_qty', 'is_qty_decimal' );
			foreach ($stockHiddenFields as $fieldName) {
				$fieldset->removeField( 'simple_product_inventory_' . $fieldName );
			}

			return $this;
		}
	}

?>