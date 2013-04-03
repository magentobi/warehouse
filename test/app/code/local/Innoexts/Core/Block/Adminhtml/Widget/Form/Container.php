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

	class Innoexts_Core_Block_Adminhtml_Widget_Form_Container extends Mage_Adminhtml_Block_Widget_Form_Container {
		private $_blockSubGroup = null;
		private $_addLabel = null;
		private $_editLabel = null;
		private $_saveLabel = null;
		private $_saveAndContinueLabel = null;
		private $_deleteLabel = null;
		private $_saveAndContinueEnabled = null;
		private $_tabEnabled = null;
		private $_tabsBlockType = null;
		private $_tabsBlockId = null;
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
     * Get add label
     * 
     * @return string
     */
		function getAddLabel() {
			return $this->_addLabel;
		}

		/**
     * Get edit label
     * 
     * @return string
     */
		function getEditLabel() {
			return $this->_editLabel;
		}

		/**
     * Get save label
     * 
     * @return string
     */
		function getSaveLabel() {
			return $this->_saveLabel;
		}

		/**
     * Get save and continue label
     * 
     * @return string
     */
		function getSaveAndContinueLabel() {
			return $this->_saveAndContinueLabel;
		}

		/**
     * Get delete label
     * 
     * @return string
     */
		function getDeleteLabel() {
			return $this->_deleteLabel;
		}

		/**
     * Check if save and continue is enabled
     * 
     * @return bool
     */
		function isSaveAndContinueEnabled() {
			return $this->_saveAndContinueEnabled;
		}

		/**
     * Check if tab is enabled
     * 
     * @return bool
     */
		function isTabEnabled() {
			return $this->_tabEnabled;
		}

		/**
     * Get tabs block type
     * 
     * @return string
     */
		function getTabsBlockType() {
			return $this->_tabsBlockType;
		}

		/**
     * Get tabs block identifier
     * 
     * @return string
     */
		function getTabsBlockId() {
			return $this->_tabsBlockId;
		}

		/**
     * Get tabs block
     * 
     * @return Mage_Adminhtml_Block_Widget_Tabs
     */
		function getTabsBlock() {
			return $this->getLayout(  )->getBlock( $this->getTabsBlockType(  ) );
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
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->_addButtons(  );
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
     * Check if delete action allowed
     * 
     * @return bool
     */
		function isDeleteAllowed() {
			return $this->isAllowedAction( 'delete' );
		}

		/**
     * Get Save and continue URL
     * 
     * @return  string
     */
		function getSaveAndContinueUrl() {
			$params = array( '_current' => true, 'back' => 'edit' );

			if ($this->isTabEnabled(  )) {
				$params['active_tab'] = '{{tab_id}}';
			}

			return $this->getUrl( '*/*/save', $params );
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
     * Get header text
     * 
     * @return  string
     */
		function getHeaderText() {
			$textHelper = $this->getTextHelper(  );
			$model = $this->getModel(  );

			if (( $model && $model->getId(  ) )) {
				return $textHelper->__( $this->getEditLabel(  ), $this->htmlEscape( $model->getTitle(  ) ) );
			}

			return $textHelper->__( $this->getAddLabel(  ) );
		}

		/**
     * Add buttons
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Form_Container
     */
		function _addButtons() {
			if ($this->isSaveAllowed(  )) {
				$this->_updateButton( 'save', 'label', $this->getSaveLabel(  ) );

				if ($this->isSaveAndContinueEnabled(  )) {
					$this->_addButton( 'saveandcontinue', array( 'label' => $this->getSaveAndContinueLabel(  ), 'onclick' => 'saveAndContinueEdit(\'' . $this->getSaveAndContinueUrl(  ) . '\')', 'class' => 'save' ), -100 );
				}
			} 
else {
				$this->_removeButton( 'save' );
			}


			if ($this->isDeleteAllowed(  )) {
				$this->_updateButton( 'delete', 'label', $this->getDeleteLabel(  ) );
				return null;
			}

			$this->_removeButton( 'delete' );
		}

		/**
     * Preparing block layout
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Form_Container
     */
		function __prepareLayout() {
			if ($this->isSaveAndContinueEnabled(  )) {
				$tabsBlock = $this->getTabsBlock(  );

				if ($tabsBlock) {
					$tabsBlockJsObject = $tabsBlock->getJsObjectName(  );
					$tabsBlockPrefix = $tabsBlock->getId(  ) . '_';
				} 
else {
					$tabsBlockJsObject = $this->getTabsBlockId(  ) . 'JsTabs';
					$tabsBlockPrefix = $this->getTabsBlockId(  ) . '_';
				}

				$this->_formScripts[] = 'function saveAndContinueEdit(urlTemplate) {
    var tabsIdValue = ' . $tabsBlockJsObject . '.activeTab.id;
    var tabsBlockPrefix = \'' . $tabsBlockPrefix . '\';
    if (tabsIdValue.startsWith(tabsBlockPrefix)) {
        tabsIdValue = tabsIdValue.substr(tabsBlockPrefix.length);
    }
    var template = new Template(urlTemplate, /(^|.|\r|\n)({{(\w+)}})/);
    var url = template.evaluate({tab_id:tabsIdValue});
    editForm.submit(url);
}';
			}

			return $this;
		}

		/**
     * Get form block type
     * 
     * @return string
     */
		function getFormBlockType() {
			return $this->_blockGroup . '/' . ($this->_blockSubGroup ? $this->_blockSubGroup . '_' : '') . $this->_controller . '_' . $this->_mode . '_form';
		}

		/**
     * Preparing block layout
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Form_Container
     */
		function _prepareLayout() {
			$this->__prepareLayout(  );

			if (( ( $this->_blockGroup && $this->_controller ) && $this->_mode )) {
				$this->setChild( 'form', $this->getLayout(  )->createBlock( $this->getFormBlockType(  ) ) );
			}

			foreach ($this->_buttons as $level => $buttons) {
				foreach ($buttons as $id => $data) {
					$childId = $this->_prepareButtonBlockId( $id );
					$this->_addButtonChildBlock( $childId );
				}
			}

			return $this;
		}
	}

?>