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

	class Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Zone_Price_Form extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Form {
		private $_formFieldNameSuffix = 'product_zone_price';
		private $_formHtmlIdPrefix = 'product_zone_price_';
		private $_formFieldsetId = 'product_zone_price_fieldset';
		private $_formFieldsetLegend = 'Discount';

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'productZonePriceTabForm' );
		}

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve registered product model
     * 
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			return Mage::registry( 'product' );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'catalog/products' );
		}

		/**
     * Retrieve save URL
     * 
     * @return string
     */
		function getSaveUrl() {
			$model = $this->getModel(  );
			$params = array( 'id' => $this->getProduct(  )->getId(  ) );

			if ($model->getId(  )) {
				$params['zone_price_id'] = $model->getId(  );
			}

			return $this->getUrl( '*/catalog_product_zone/savePrice', $params );
		}

		/**
     * Get price type values
     * 
     * @return array
     */
		function getPriceTypeValues() {
			$helper = $this->getTextHelper(  );
			return array( array( 'value' => 'fixed', 'label' => $helper->__( 'Fixed' ) ), array( 'value' => 'percent', 'label' => $helper->__( 'Percent' ) ) );
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
     * @return Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Zone_Price_Form
     */
		function _prepareForm() {
			parent::_prepareForm(  );
			$fieldset = $this->getFieldset(  );

			if ($fieldset) {
				$helper = $this->getTextHelper(  );
				$model = $this->getModel(  );
				$isElementDisabled = !$this->isSaveAllowed(  );
				$fieldset->addField( 'zone_price_id', 'hidden', array( 'name' => 'zone_price_id', 'value' => $model->getId(  ), 'default' => '' ) );
				$fieldset->addField( 'is_zip_range', 'select', array( 'name' => 'is_zip_range', 'label' => $helper->__( 'Zip/Postal Code is Range' ), 'title' => $helper->__( 'Zip/Postal Code is Range' ), 'required' => false, 'options' => $this->getIsZipRangeOptions(  ), 'value' => ($model->getIsZipRange(  ) ? '1' : '0'), 'default' => '0', 'disabled' => $isElementDisabled ), 'region_id' );
				$fieldset->removeField( 'zip' );
				$fieldset->addField( 'zip', 'text', array( 'name' => 'zip', 'label' => $helper->__( 'Zip/Postal Code' ), 'title' => $helper->__( 'Zip/Postal Code' ), 'note' => $helper->__( '\'*\' - matches any.' ), 'required' => false, 'value' => $this->getZipValue(  ), 'default' => '', 'disabled' => $isElementDisabled ), 'is_zip_range' );
				$fieldset->addField( 'from_zip', 'text', array( 'name' => 'from_zip', 'label' => $helper->__( 'Zip/Postal Code From' ), 'title' => $helper->__( 'Zip/Postal Code From' ), 'required' => true, 'value' => $model->getFromZip(  ), 'disabled' => $isElementDisabled, 'class' => 'validate-digits' ), 'zip' );
				$fieldset->addField( 'to_zip', 'text', array( 'name' => 'to_zip', 'label' => $helper->__( 'Zip/Postal Code To' ), 'title' => $helper->__( 'Zip/Postal Code To' ), 'required' => true, 'value' => $model->getToZip(  ), 'disabled' => $isElementDisabled, 'class' => 'validate-digits' ), 'from_zip' );
				$fieldset->addField( 'price', 'text', array( 'name' => 'price', 'label' => $helper->__( 'Amount' ), 'title' => $helper->__( 'Amount' ), 'required' => true, 'value' => floatval( $model->getPrice(  ) ), 'default' => '0', 'disabled' => $isElementDisabled ) );
				$fieldset->addField( 'price_type', 'select', array( 'name' => 'price_type', 'label' => $helper->__( 'Apply' ), 'title' => $helper->__( 'Apply' ), 'required' => true, 'value' => $model->getPriceType(  ), 'default' => 'fixed', 'values' => $this->getPriceTypeValues(  ), 'disabled' => $isElementDisabled ) );
				$fieldset->addField( 'submit_button', 'note', array( 'text' => $this->getButtonHtml( $helper->__( 'Submit' ), $this->getJsObjectName(  ) . '.submit(\'' . $this->getSaveUrl(  ) . '\');', 'save' ) ) );
			}

			return $this;
		}
	}

?>