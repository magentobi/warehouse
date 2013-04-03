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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Form extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Form {
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
			$countryId = $model->getCountryId(  );

			if ($countryId) {
				$regionCollection = Mage::getModel( 'directory/region' )->getCollection(  )->addCountryFilter( $countryId );
				$regions = $regionCollection->toOptionArray(  );

				if (isset( $regions[0] )) {
					$regions[0]['label'] = '*';
				}
			}

			return $regions;
		}

		/**
     * Get zip value
     * 
     * @return string
     */
		function getZipValue() {
			$zip = ($this->getModel(  ) ? $this->getModel(  )->getZip(  ) : null);
			return (( $zip == '*' || $zip == '' ) ? '*' : $zip);
		}

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Form
     */
		function _prepareForm() {
			$this->getFieldset(  );
			$fieldset = parent::_prepareForm(  );

			if ($fieldset) {
				$this->getTextHelper(  );
				$model = $helper = $this->getModel(  );
				$isElementDisabled = !$this->isSaveAllowed(  );
				$fieldset->addField( 'country_id', 'select', array( 'name' => 'country_id', 'label' => $helper->__( 'Country' ), 'title' => $helper->__( 'Country' ), 'required' => false, 'value' => $model->getCountryId(  ), 'default' => '0', 'values' => $this->getCountryValues(  ), 'disabled' => $isElementDisabled ) );
				$fieldset->addField( 'region_id', 'select', array( 'name' => 'region_id', 'label' => $helper->__( 'Region/State' ), 'title' => $helper->__( 'Region/State' ), 'required' => false, 'value' => $model->getRegionId(  ), 'default' => '0', 'values' => $this->getRegionValues(  ), 'disabled' => $isElementDisabled ) );
				$fieldset->addField( 'zip', 'text', array( 'name' => 'zip', 'label' => $helper->__( 'Zip/Postal Code' ), 'title' => $helper->__( 'Zip/Postal Code' ), 'note' => $helper->__( '* or blank - matches any' ), 'required' => false, 'value' => $this->getZipValue(  ), 'default' => '', 'disabled' => $isElementDisabled ) );
			}

			return $this;
		}
	}

?>