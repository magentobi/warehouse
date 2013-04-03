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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Area_Grid extends Innoexts_Core_Block_Adminhtml_Widget_Grid {
		/**
     * Get country options
     * 
     * @return array
     */
		function getCountryOptions() {
			$options = array(  );
			$countries = Mage::getModel( 'adminhtml/system_config_source_country' )->toOptionArray( false );

			if (isset( $countries[0] )) {
				$countries[0] = array( 'value' => '0', 'label' => '*' );
			}

			foreach ($countries as $country) {
				$options[$country['value']] = $country['label'];
			}

			return $options;
		}

		/**
     * Get child block type prefix
     * 
     * @return string
     */
		function getAreaChildBlockTypePrefix() {
			return 'innoexts_core/adminhtml_widget_grid_area_';
		}

		/**
     * Add columns to grid
     *
     * @return Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Grid
     */
		function _prepareColumns() {
			$textHelper = $this->getTextHelper(  );
			$this->addColumn( 'country_id', array( 'header' => $textHelper->__( 'Country' ), 'align' => 'left', 'index' => 'country_id', 'filter_index' => 'main_table.country_id', 'type' => 'options', 'options' => $this->getCountryOptions(  ) ) );
			$this->addColumn( 'region', array( 'header' => $textHelper->__( 'Region/State' ), 'align' => 'left', 'index' => 'region', 'filter_index' => 'region_table.code', 'filter' => $this->getAreaChildBlockTypePrefix(  ) . 'column_filter_region', 'default' => '*' ) );
			$this->addColumn( 'zip', array( 'header' => $textHelper->__( 'Zip/Postal Code' ), 'align' => 'left', 'index' => 'zip', 'filter' => $this->getAreaChildBlockTypePrefix(  ) . 'column_filter_zip', 'renderer' => $this->getAreaChildBlockTypePrefix(  ) . 'column_renderer_zip', 'default' => '*' ) );
			return $this;
		}
	}

?>