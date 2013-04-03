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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Shipping_Carrier extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'shipping_carrier_fieldset';
		private $_formFieldsetLegend = 'Shipping Carriers';
		private $_title = 'Shipping Carriers';

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Shipping_Carrier
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );
			$shippingCarriersElement = $fieldset->addField( 'shipping_carriers', 'text', array( 'name' => 'shipping_carriers', 'label' => $helper->__( 'Shipping Carriers' ), 'title' => $helper->__( 'Shipping Carriers' ), 'required' => false, 'value' => $model->getShippingCarriers(  ) ) );
			$shippingCarriersElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_warehouse_edit_tab_shipping_carrier_renderer' ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>