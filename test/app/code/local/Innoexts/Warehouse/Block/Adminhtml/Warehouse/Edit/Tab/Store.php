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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Store extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'store_fieldset';
		private $_formFieldsetLegend = 'Stores';
		private $_title = 'Stores';

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Store
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );
			$storeIdsElement = $fieldset->addField( 'store_ids', 'text', array( 'name' => 'store_ids', 'label' => $helper->__( 'Stores' ), 'title' => $helper->__( 'Stores' ), 'required' => false, 'value' => $model->getStoreIds(  ) ) );
			$storeIdsElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_warehouse_edit_tab_store_renderer' ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>