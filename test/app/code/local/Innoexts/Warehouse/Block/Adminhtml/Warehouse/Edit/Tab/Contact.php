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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Contact extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'contact_fieldset';
		private $_formFieldsetLegend = 'Contact';
		private $_title = 'Contact';

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Contact
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );
			$fieldset->addField( 'notify', 'checkbox', array( 'name' => 'notify', 'label' => $helper->__( 'Notify?' ), 'title' => $helper->__( 'Notify?' ), 'required' => false, 'disabled' => $isElementDisabled, 'checked' => ($model->getNotify(  ) ? true : false), 'value' => 1 ) );
			$fieldset->addField( 'contact_name', 'text', array( 'name' => 'contact_name', 'label' => $helper->__( 'Name' ), 'title' => $helper->__( 'Name' ), 'required' => false, 'disabled' => $isElementDisabled, 'value' => $model->getContactName(  ) ) );
			$fieldset->addField( 'contact_email', 'text', array( 'name' => 'contact_email', 'label' => $helper->__( 'Email' ), 'title' => $helper->__( 'Email' ), 'required' => false, 'disabled' => $isElementDisabled, 'value' => $model->getContactEmail(  ) ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>