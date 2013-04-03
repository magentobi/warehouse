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

	class Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Grid extends Innoexts_Core_Block_Adminhtml_Widget_Grid {
		private $_addButtonLabel = null;
		private $_formJsObjectName = null;

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return $this;
		}

		/**
     * Get add button label
     * 
     * @return string
     */
		function getAddButtonLabel() {
			return $this->getTextHelper(  )->__( $this->_addButtonLabel );
		}

		/**
     * Get form js object name
     * 
     * @return string
     */
		function getFormJsObjectName() {
			return $this->_formJsObjectName;
		}

		/**
     * Prepare layout
     * 
     * @return Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Grid
     */
		function _prepareLayout() {
			$this->setChild( 'add_button', $this->getLayout(  )->createBlock( 'adminhtml/widget_button' )->setData( array( 'label' => $this->getAddButtonLabel(  ), 'onclick' => $this->getFormJsObjectName(  ) . '.doAdd()', 'class' => 'task' ) ) );
			parent::_prepareLayout(  );
			return $this;
		}

		/**
     * Get main button HTML
     * 
     * @return string
     */
		function getMainButtonsHtml() {
			$html = parent::getMainButtonsHtml(  );
			return $this->getChildHtml( 'add_button' ) . $html;
		}
	}

?>