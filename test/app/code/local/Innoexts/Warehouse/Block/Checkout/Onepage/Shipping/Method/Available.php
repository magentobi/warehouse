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

	class Innoexts_Warehouse_Block_Checkout_Onepage_Shipping_Method_Available extends Mage_Checkout_Block_Onepage_Abstract {
		private $_singleModeRenderer = null;
		private $_multipleModeRenderer = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Set single mode renderer
     * 
     * @param string $block
     * @param string $template
     * 
     * @return Innoexts_Warehouse_Block_Checkout_Onepage_Shipping_Method_Available
     */
		function setSingleModeRenderer($block, $template) {
			$this->_singleModeRenderer = array( 'block' => $block, 'template' => $template, 'renderer' => null );
			return $this;
		}

		/**
     * Get single mode renderer
     *
     * @return Mage_Core_Block_Abstract
     */
		function getSingleModeRenderer() {
			if (!is_null( $this->_singleModeRenderer )) {
				if (is_null( $this->_singleModeRenderer['renderer'] )) {
					$this->_singleModeRenderer['block'];
					$template = $block = $this->_singleModeRenderer['template'];
					$this->_singleModeRenderer['renderer'] = $this->getLayout(  )->createBlock( $block )->setTemplate( $template )->setRenderedBlock( $this );
				}

				return $this->_singleModeRenderer['renderer'];
			}

		}

		/**
     * Get single mode html
     * 
     * @return string
     */
		function getSingleModeHtml() {
			$renderer = $this->getSingleModeRenderer(  );

			if ($renderer) {
				return $renderer->toHtml(  );
			}

		}

		/**
     * Set multiple mode renderer
     * 
     * @param string $block
     * @param string $template
     * 
     * @return Innoexts_Warehouse_Block_Checkout_Onepage_Shipping_Method_Available
     */
		function setMultipleModeRenderer($block, $template) {
			$this->_multipleModeRenderer = array( 'block' => $block, 'template' => $template, 'renderer' => null );
			return $this;
		}

		/**
     * Get multiple mode renderer
     *
     * @return Mage_Core_Block_Abstract
     */
		function getMultipleModeRenderer() {
			if (!is_null( $this->_multipleModeRenderer )) {
				if (is_null( $this->_multipleModeRenderer['renderer'] )) {
					$block = $this->_multipleModeRenderer['block'];
					$template = $this->_multipleModeRenderer['template'];
					$renderer = $this->getLayout(  )->createBlock( $block )->setTemplate( $template )->setRenderedBlock( $this );
					$this->_multipleModeRenderer['renderer'] = $renderer;
				}

				return $this->_multipleModeRenderer['renderer'];
			}

		}

		/**
     * Get multiple mode html
     * 
     * @return string
     */
		function getMultipleModeHtml() {
			$renderer = $this->getMultipleModeRenderer(  );

			if ($renderer) {
				return $renderer->toHtml(  );
			}

		}
	}

?>