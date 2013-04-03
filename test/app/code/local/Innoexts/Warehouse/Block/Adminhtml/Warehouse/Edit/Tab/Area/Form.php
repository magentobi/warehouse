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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Area_Form extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Form {
		private $_formFieldNameSuffix = 'warehouse_area';
		private $_formHtmlIdPrefix = 'warehouse_area_';
		private $_formFieldsetId = 'warehouse_area_fieldset';
		private $_formFieldsetLegend = 'Area';
		private $_modelName = 'warehouse_area';

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'warehouseAreaTabForm' );
		}

		/**
     * Get warehouse helper
     * 
     * @return Varien_Object
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve registered product model
     *
     * @return Mage_Catalog_Model_Product
     */
		function getWarehouse() {
			return Mage::registry( 'warehouse' );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * 
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'catalog/warehouses/' . $action );
		}

		/**
     * Retrieve save URL
     *
     * @return string
     */
		function getSaveUrl() {
			$model = $this->getModel(  );
			$params = array( 'warehouse_id' => $this->getWarehouse(  )->getId(  ) );

			if ($model->getId(  )) {
				$params['warehouse_area_id'] = $model->getId(  );
			}

			return $this->getUrl( '*/*/saveArea', $params );
		}

		/**
     * Get is zip range options
     * 
     * @return array
     */
		function getIsZipRangeOptions() {
			$helper = $this->getTextHelper(  );
			return array( '0' => $helper->__( 'No' ), '1' => $helper->__( 'Yes' ) );
		}

		/**
     * Prepare form before rendering HTML
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Area_Form
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$fieldset = $this->getFieldset(  );

			if (!$fieldset) {
				return $this;
			}

			$helper = $this->getWarehouseHelper(  );
			$model = $this->getModel(  );
			$isElementDisabled = !$this->isSaveAllowed(  );
			$fieldset->addField( 'warehouse_area_id', 'hidden', array( 'name' => 'warehouse_area_id', 'value' => $model->getId(  ), 'default' => '' ) );
			$fieldset->addField( 'is_zip_range', 'select', array( 'name' => 'is_zip_range', 'label' => $helper->__( 'Zip/Postal Code is Range' ), 'title' => $helper->__( 'Zip/Postal Code is Range' ), 'required' => false, 'options' => $this->getIsZipRangeOptions(  ), 'value' => ($model->getIsZipRange(  ) ? '1' : '0'), 'default' => '0', 'disabled' => $isElementDisabled ), 'region_id' );
			$fieldset->removeField( 'zip' );
			$fieldset->addField( 'zip', 'text', array( 'name' => 'zip', 'label' => $helper->__( 'Zip/Postal Code' ), 'title' => $helper->__( 'Zip/Postal Code' ), 'note' => $helper->__( '\'*\' - matches any.' ), 'required' => false, 'value' => $this->getZipValue(  ), 'default' => '', 'disabled' => $isElementDisabled ), 'is_zip_range' );
			$fieldset->addField( 'from_zip', 'text', array( 'name' => 'from_zip', 'label' => $helper->__( 'Zip/Postal Code From' ), 'title' => $helper->__( 'Zip/Postal Code From' ), 'required' => true, 'value' => $model->getFromZip(  ), 'disabled' => $isElementDisabled, 'class' => 'validate-digits' ), 'zip' );
			$fieldset->addField( 'to_zip', 'text', array( 'name' => 'to_zip', 'label' => $helper->__( 'Zip/Postal Code To' ), 'title' => $helper->__( 'Zip/Postal Code To' ), 'required' => true, 'value' => $model->getToZip(  ), 'disabled' => $isElementDisabled, 'class' => 'validate-digits' ), 'from_zip' );
			$fieldset->addField( 'submit_button', 'note', array( 'text' => $this->getButtonHtml( $helper->__( 'Submit' ), $this->getJsObjectName(  ) . '.submit(\'' . $this->getSaveUrl(  ) . '\');', 'save' ) ) );
			return $this;
		}
	}

?>