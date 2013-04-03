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

	class Innoexts_Core_Block_Adminhtml_Widget_Form extends Mage_Adminhtml_Block_Widget_Form {
		private $_formFieldNameSuffix = null;
		private $_formHtmlIdPrefix = null;
		private $_formFieldsetId = null;
		private $_formFieldsetLegend = null;
		private $_modelName = null;

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return $this;
		}

		/**
     * Retrieve admin session model
     *
     * @return Mage_Admin_Model_Session
     */
		function getAdminSession() {
			return Mage::getSingleton( 'admin/session' );
		}

		/**
     * Get form field name suffix
     * 
     * @return string
     */
		function getFormFieldNameSuffix() {
			return $this->_formFieldNameSuffix;
		}

		/**
     * Get form html identifier prefix
     * 
     * @return string
     */
		function getFormHtmlIdPrefix() {
			return $this->_formHtmlIdPrefix;
		}

		/**
     * Get form field set identifier
     * 
     * @return string
     */
		function getFormFieldsetId() {
			return $this->_formFieldsetId;
		}

		/**
     * Get form field set legend
     * 
     * @return string
     */
		function getFormFieldsetLegend() {
			return $this->getTextHelper(  )->__( $this->_formFieldsetLegend );
		}

		/**
     * Get model name
     * 
     * @return string
     */
		function getModelName() {
			return $this->_modelName;
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * 
     * @return  bool
     */
		function isAllowedAction($action) {
			return true;
		}

		/**
     * Check if save action allowed
     * 
     * @return bool
     */
		function isSaveAllowed() {
			return $this->isAllowedAction( 'save' );
		}

		/**
     * Retrieve registered model
     *
     * @return Varien_Object
     */
		function getModel() {
			$model = Mage::registry( $this->getModelName(  ) );

			if (!$model) {
				$model = new Varien_Object(  );
			}

			return $model;
		}

		/**
     * Get Js object name
     * 
     * @return string
     */
		function getJsObjectName() {
			return $this->getId(  ) . 'JsObject';
		}

		/**
     * Get fieldset
     * 
     * @return Varien_Data_Form_Element_Fieldset
     */
		function getFieldset() {
			$form = $this->getForm(  );

			if ($form) {
				return $form->getElement( $this->getFormFieldsetId(  ) );
			}

		}

		/**
     * Get fields
     * 
     * @return array of Varien_Data_Form_Element_Abstract
     */
		function getFields() {
			$fields = array(  );
			$fieldset = $this->getFieldset(  );

			if ($fieldset) {
				foreach ($fieldset->getElements(  ) as $element) {

					if (( !( $element instanceof Varien_Data_Form_Element_Button ) && !( $element instanceof Varien_Data_Form ) )) {
						if ($element->getData( 'name' )) {
							$fields[$element->getData( 'name' )] = $element;
							continue;
						}

						continue;
					}
				}
			}

			return $fields;
		}

		/**
     * Get field names
     * 
     * @return array
     */
		function getFieldNames() {
			return array_keys( $this->getFields(  ) );
		}

		/**
     * Get defaults
     * 
     * @return array
     */
		function getDefaults() {
			$defaults = array(  );
			foreach ($this->getFields(  ) as $name => $field) {
				$defaults[$name] = $field->getData( 'default' );
			}

			return $defaults;
		}

		/**
     * Prepare form before rendering
     *
     * @return Innoexts_Core_Block_Adminhtml_Widget_Form
     */
		function _prepareForm() {
			$form = new Varien_Data_Form(  );

			if ($this->getFormFieldNameSuffix(  )) {
				$form->setFieldNameSuffix( $this->getFormFieldNameSuffix(  ) );
			}


			if ($this->getFormHtmlIdPrefix(  )) {
				$form->setHtmlIdPrefix( $this->getFormHtmlIdPrefix(  ) );
			}

			$form->addFieldset( $this->getFormFieldsetId(  ), array( 'legend' => $this->getFormFieldsetLegend(  ) ) );
			$this->setForm( $form );
			return $this;
		}

		/**
     * Dispatch prepare form event
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Form
     */
		function dispatchPrepareFormEvent() {
			Mage::dispatchEvent( $this->getFormHtmlIdPrefix(  ) . '_prepare_form', array( 'form' => $this->getForm(  ) ) );
			return $this;
		}
	}

?>