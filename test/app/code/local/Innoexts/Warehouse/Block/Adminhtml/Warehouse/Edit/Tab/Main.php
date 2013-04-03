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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Main extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'main_fieldset';
		private $_formFieldsetLegend = 'General';
		private $_title = 'General';

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Main
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );

			if ($model->getId(  )) {
				$fieldset->addField( 'warehouse_id', 'hidden', array( 'name' => 'warehouse_id', 'value' => $model->getId(  ) ) );
			}

			$fieldset->addField( 'code', 'text', array( 'name' => 'code', 'label' => $helper->__( 'Code' ), 'title' => $helper->__( 'Code' ), 'required' => true, 'disabled' => $isElementDisabled, 'value' => $model->getCode(  ) ) );
			$fieldset->addField( 'title', 'text', array( 'name' => 'title', 'label' => $helper->__( 'Title' ), 'title' => $helper->__( 'Title' ), 'required' => true, 'disabled' => $isElementDisabled, 'value' => $model->getTitle(  ) ) );
			$fieldset->addField( 'description', 'textarea', array( 'name' => 'description', 'label' => $helper->__( 'Description' ), 'title' => $helper->__( 'Description' ), 'required' => false, 'disabled' => $isElementDisabled, 'value' => $model->getDescription(  ) ) );
			$config = $helper->getConfig(  );

			if ($config->isPriorityEnabled(  )) {
				$fieldset->addField( 'priority', 'text', array( 'name' => 'priority', 'label' => $helper->__( 'Priority' ), 'title' => $helper->__( 'Priority' ), 'required' => false, 'disabled' => $isElementDisabled, 'value' => $model->getPriority(  ) ) );
			}

			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>