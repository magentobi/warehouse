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

	class Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Edit_Form extends Innoexts_Core_Block_Adminhtml_Widget_Form {
		private $_formFieldNameSuffix = 'shippingtablerate';
		private $_formHtmlIdPrefix = 'shippingtablerate_';
		private $_formFieldsetId = 'shippingtablerate_fieldset';
		private $_formFieldsetLegend = '';
		private $_modelName = 'shippingtablerate';

		/**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getShippingTablerateHelper() {
			return Mage::helper( 'shippingtablerate' );
		}

		/**
     * Retrieve text helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getTextHelper() {
			return $this->getShippingTablerateHelper(  );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'sales/shipping/tablerates/' . $action );
		}

		/**
     * Get country values
     * 
     * @return array
     */
		function getCountryValues() {
			$countries = Mage::getModel( 'adminhtml/system_config_source_country' )->toOptionArray( false );

			if (isset( $countries[0] )) {
				$countries[0]['label'] = '*';
			}

			return $countries;
		}

		/**
     * Get region values
     * 
     * @return array
     */
		function getRegionValues() {
			$regions = array( array( 'value' => '', 'label' => '*' ) );
			$model = $this->getModel(  );
			$destCountryId = $model->getDestCountryId(  );

			if ($destCountryId) {
				$regionCollection = Mage::getModel( 'directory/region' )->getCollection(  )->addCountryFilter( $destCountryId );
				$regions = $regionCollection->toOptionArray(  );

				if (isset( $regions[0] )) {
					$regions[0]['label'] = '*';
				}
			}

			return $regions;
		}

		/**
     * Get condition name values
     * 
     * @return array
     */
		function getConditionNameValues() {
			return Mage::getModel( 'adminhtml/system_config_source_shipping_tablerate' )->toOptionArray(  );
		}

		/**
     * Get zip value
     * 
     * @return string
     */
		function getZipValue() {
			$model = $this->getModel(  );
			$destZip = $model->getDestZip(  );
			return (( $destZip == '*' || $destZip == '' ) ? '*' : $destZip);
		}

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Edit_Form
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$textHelper = $this->getTextHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = ($this->isSaveAllowed(  ) ? false : true);
			$form = $this->getForm(  );
			$form->setUseContainer( true );
			$form->setId( 'edit_form' );
			$form->setAction( $this->getData( 'action' ) );
			$form->setMethod( 'post' );
			$fieldset = $this->getFieldset(  );

			if ($model->getId(  )) {
				$fieldset->addField( 'pk', 'hidden', array( 'name' => 'pk', 'value' => $model->getId(  ) ) );
			}

			$fieldset->addField( 'website_id', 'hidden', array( 'name' => 'website_id', 'value' => $model->getWebsiteId(  ) ) );
			$fieldset->addField( 'dest_country_id', 'select', array( 'name' => 'dest_country_id', 'label' => $textHelper->__( 'Dest Country' ), 'title' => $textHelper->__( 'Dest Country' ), 'required' => false, 'value' => $model->getDestCountryId(  ), 'values' => $this->getCountryValues(  ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'dest_region_id', 'select', array( 'name' => 'dest_region_id', 'label' => $textHelper->__( 'Dest Region/State' ), 'title' => $textHelper->__( 'Dest Region/State' ), 'required' => false, 'value' => $model->getDestRegionId(  ), 'values' => $this->getRegionValues(  ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'dest_zip', 'text', array( 'name' => 'dest_zip', 'label' => $textHelper->__( 'Dest Zip/Postal Code' ), 'title' => $textHelper->__( 'Dest Zip/Postal Code' ), 'note' => $textHelper->__( '* or blank - matches any' ), 'required' => false, 'value' => $this->getZipValue(  ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'condition_name', 'select', array( 'name' => 'condition_name', 'label' => $textHelper->__( 'Condition Name' ), 'title' => $textHelper->__( 'Condition Name' ), 'required' => true, 'value' => $model->getConditionName(  ), 'values' => $this->getConditionNameValues(  ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'condition_value', 'text', array( 'name' => 'condition_value', 'label' => $textHelper->__( 'Condition Value' ), 'title' => $textHelper->__( 'Condition Value' ), 'required' => true, 'value' => floatval( $model->getConditionValue(  ) ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'price', 'text', array( 'name' => 'price', 'label' => $textHelper->__( 'Price' ), 'title' => $textHelper->__( 'Price' ), 'required' => true, 'value' => floatval( $model->getPrice(  ) ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'cost', 'text', array( 'name' => 'cost', 'label' => $textHelper->__( 'Cost' ), 'title' => $textHelper->__( 'Cost' ), 'required' => true, 'value' => floatval( $model->getCost(  ) ), 'disabled' => $isElementDisabled ) );
			$fieldset->addField( 'note', 'textarea', array( 'name' => 'note', 'label' => $textHelper->__( 'Notes' ), 'title' => $textHelper->__( 'Notes' ), 'required' => false, 'value' => $model->getNote(  ), 'disabled' => $isElementDisabled ) );
			$this->dispatchPrepareFormEvent(  );
			return $this;
		}
	}

?>