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

	class Innoexts_Warehouse_Block_Adminhtml_Shippingtablerate_Tablerate_Edit_Form extends Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Edit_Form {
		/**
     * Retrieve warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get warehouse values
     * 
     * @return array
     */
		function getWarehouseValues() {
			$helper = $this->getWarehouseHelper(  );
			return $helper->getWarehousesOptions( false, '*', '0' );
		}

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Shippingtablerate_Tablerate_Edit_Form
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $model = $this->getFieldset(  );
			$fieldset->addField( 'warehouse_id', 'select', array( 'name' => 'warehouse_id', 'label' => $helper->__( 'Warehouse' ), 'title' => $helper->__( 'Warehouse' ), 'required' => false, 'value' => $model->getWarehouseId(  ), 'values' => $this->getWarehouseValues(  ), 'disabled' => $isElementDisabled ), 'website_id' );
			return $this;
		}
	}

?>