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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Currency extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'currency_fieldset';
		private $_formFieldsetLegend = 'Currencies';
		private $_title = 'Currencies';

		/**
     * Prepare form before rendering HTML
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Currency
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );
			$currenciesElement = $fieldset->addField( 'currencies', 'text', array( 'name' => 'currencies', 'label' => $helper->__( 'Currencies' ), 'title' => $helper->__( 'Currencies' ), 'required' => false, 'value' => $model->getCurrencies(  ) ) );
			$currenciesElement->setRenderer( $this->getLayout(  )->createBlock( 'warehouse/adminhtml_warehouse_edit_tab_currency_renderer' ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>