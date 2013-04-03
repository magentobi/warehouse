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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Origin extends Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Abstract {
		private $_formFieldsetId = 'origin_fieldset';
		private $_formFieldsetLegend = 'Origin';
		private $_title = 'Origin';

		/**
     * Retrieve countries values
     * 
     * @return  array
     */
		function getCountryValues() {
			$source = new Mage_Adminhtml_Model_System_Config_Source_Country(  );
			return $source->toOptionArray( false );
		}

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Origin
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$fieldset = $this->getFieldset(  );
			$fieldset->addField( 'origin_country_id', 'select', array( 'name' => 'origin_country_id', 'label' => $helper->__( 'Country' ), 'title' => $helper->__( 'Country' ), 'required' => true, 'disabled' => $isElementDisabled, 'values' => $this->getCountryValues(  ), 'class' => 'origin_country_id', 'value' => $model->getOriginCountryId(  ) ) );
			$fieldset->addField( 'origin_region_id', 'text', array( 'name' => 'origin_region_id', 'label' => $helper->__( 'Region/State' ), 'title' => $helper->__( 'Region/State' ), 'required' => true, 'disabled' => $isElementDisabled, 'class' => 'origin_region_id', 'value' => ($model->getOriginRegionId(  ) ? $model->getOriginRegionId(  ) : $model->getOriginRegion(  )) ) );
			$fieldset->addField( 'origin_postcode', 'text', array( 'name' => 'origin_postcode', 'label' => $helper->__( 'ZIP/Postal Code' ), 'title' => $helper->__( 'ZIP/Postal Code' ), 'required' => true, 'disabled' => $isElementDisabled, 'value' => $model->getOriginPostcode(  ) ) );
			$fieldset->addField( 'origin_city', 'text', array( 'name' => 'origin_city', 'label' => $helper->__( 'City' ), 'title' => $helper->__( 'City' ), 'required' => true, 'disabled' => $isElementDisabled, 'value' => $model->getOriginCity(  ) ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>