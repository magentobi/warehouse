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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Customer_Group extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'customer_group_fieldset';
		private $_formFieldsetLegend = 'Customer Groups';
		private $_title = 'Customer Groups';

		/**
     * Prepare form before rendering HTML
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Customer_Group
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );
			$customerGroupIdsElement = $helper = $fieldset->addField( 'customer_group_ids', 'text', array( 'name' => 'customer_group_ids', 'label' => $helper->__( 'Customer Groups' ), 'title' => $helper->__( 'Customer Groups' ), 'required' => false, 'value' => $model->getCustomerGroupIds(  ) ) );
			$customerGroupIdsElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_warehouse_edit_tab_customer_group_renderer' ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>