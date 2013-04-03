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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Container extends Mage_Adminhtml_Block_Widget {
		private $_gridBlockType = null;
		private $_formBlockType = null;

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'innoexts/core/widget/grid/editable/container.phtml' );
		}

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
     * Retrieve core helper
     * 
     * @return Mage_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'core' );
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
     * Check if edit function enabled
     * 
     * @return bool
     */
		function canEdit() {
			return true;
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
     * Get grid block type
     * 
     * @return string
     */
		function getGridBlockType() {
			return $this->_gridBlockType;
		}

		/**
     * Get form block type
     * 
     * @return string
     */
		function getFormBlockType() {
			return $this->_formBlockType;
		}

		/**
     * Prepare Layout data
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Container
     */
		function _prepareLayout() {
			$layout = $this->getLayout(  );

			if (( $this->canEdit(  ) && !$this->hasForm(  ) )) {
				$this->setChild( 'form', $layout->createBlock( $this->getFormBlockType(  ) ) );
			}


			if (!$this->hasGrid(  )) {
				$this->setChild( 'grid', $layout->createBlock( $this->getGridBlockType(  ) ) );
			}

			return parent::_prepareLayout(  );
		}

		/**
     * Get grid
     * 
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
		function getGrid() {
			return $this->getChild( 'grid' );
		}

		/**
     * Check if grid exists
     * 
     * @return bool
     */
		function hasGrid() {
			$grid = $this->getGrid(  );

			if (!empty( $$grid )) {
				return true;
			}

			return false;
		}

		/**
     * Retrieve grid HTML
     *
     * @return string
     */
		function getGridHtml() {
			if ($this->hasGrid(  )) {
				return $this->getChildHtml( 'grid' );
			}

		}

		/**
     * Get js object name
     * 
     * @return string
     */
		function getGridJsObjectName() {
			if ($this->hasGrid(  )) {
				return $this->getGrid(  )->getJsObjectName(  );
			}

		}

		/**
     * Get form
     * 
     * @return Mage_Adminhtml_Block_Widget_Form
     */
		function getForm() {
			return $this->getChild( 'form' );
		}

		/**
     * Check if form exists
     * 
     * @return bool
     */
		function hasForm() {
			$form = $this->getForm(  );

			if (!empty( $$form )) {
				return true;
			}

			return false;
		}

		/**
     * Retrieve form HTML
     * 
     * @return string
     */
		function getFormHtml() {
			if ($this->hasForm(  )) {
				return $this->getChildHtml( 'form' );
			}

		}

		/**
     * Get form html id prefix
     * 
     * @return string
     */
		function getFormHtmlIdPrefix() {
			if ($this->hasForm(  )) {
				return $this->getForm(  )->getFormHtmlIdPrefix(  );
			}

		}

		/**
     * Get form html identifier
     * 
     * @return string
     */
		function getFormHtmlId() {
			if ($this->hasForm(  )) {
				return $this->getForm(  )->getHtmlId(  );
			}

		}

		/**
     * Get form field names
     * 
     * @return array
     */
		function getFormFieldNames() {
			if ($this->hasForm(  )) {
				return $this->getForm(  )->getFieldNames(  );
			}

			return array(  );
		}

		/**
     * Get form field names json
     * 
     * @return string
     */
		function getFormFieldNamesJson() {
			return $this->getCoreHelper(  )->jsonEncode( $this->getFormFieldNames(  ) );
		}

		/**
     * Get form defaults
     * 
     * @return array
     */
		function getFormDefaults() {
			if ($this->hasForm(  )) {
				return $this->getForm(  )->getDefaults(  );
			}

			return array(  );
		}

		/**
     * Get form defaults json
     * 
     * @return string
     */
		function getFormDefaultsJson() {
			return $this->getCoreHelper(  )->jsonEncode( $this->getFormDefaults(  ) );
		}

		/**
     * Get form js object name
     * 
     * @return string
     */
		function getFormJsObjectName() {
			if ($this->hasForm(  )) {
				return $this->getForm(  )->getJsObjectName(  );
			}

		}

		/**
     * Escape JavaScript string
     * 
     * @param string $string
     * 
     * @return string
     */
		function escapeJs($string) {
			return addcslashes( $string, '\'\'');
		}
	}

?>