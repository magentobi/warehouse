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

	class Innoexts_Core_Block_Adminhtml_Widget_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
		private $_modelName = null;
		private $_childBlockTypePrefix = null;

		/**
     * Get model name
     * 
     * @return string
     */
		function getModelName() {
			return $this->_modelName;
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
     * Translate html content
     * 
     * @param string $html
     * 
     * @return string
     */
		function _translateHtml($html) {
			Mage::getSingleton( 'core/translate_inline' )->processResponseBody( $html );
			return $html;
		}

		/**
     * Get block content
     * 
     * @param string $block
     * 
     * @return string
     */
		function _getBlockContent($block) {
			return $this->_translateHtml( $this->getLayout(  )->createBlock( $block )->toHtml(  ) );
		}

		/**
     * Get child block type prefix
     * 
     * @return string
     */
		function getChildBlockTypePrefix() {
			return $this->_childBlockTypePrefix;
		}

		/**
     * Get child block content
     * 
     * @param string $name
     * 
     * @return string
     */
		function getChildBlockContent($name) {
			return $this->_getBlockContent( $this->getChildBlockTypePrefix(  ) . $name );
		}
	}

?>