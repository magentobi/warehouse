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

	class Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Container {
		private $_blockGroup = 'shippingtablerate';
		private $_controller = 'adminhtml_tablerate';
		private $_headerLabel = 'Shipping Table Rates';
		private $_addLabel = 'Add New Rate';
		private $_website = null;

		/**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getShippingTablerateHelper() {
			return Mage::helper( 'shippingtablerate' );
		}

		/**
     * Retrieve text helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getTextHelper() {
			return $this->getShippingTablerateHelper(  );
		}

		/**
     * Get website
     * 
     * @return Mage_Core_Model_Website
     */
		function getWebsite() {
			if (is_null( $this->_website )) {
				$this->_website = $this->getShippingTablerateHelper(  )->getWebsite(  );
			}

			return $this->_website;
		}

		/**
     * Get website identifier
     * 
     * @return mixed
     */
		function getWebsiteId() {
			return $this->getShippingTablerateHelper(  )->getWebsiteId( $this->getWebsite(  ) );
		}

		/**
     * Check is allowed action
     * 
     * @param   string $action
     * 
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'sales/shipping/tablerates/' . $action );
		}

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'shippingtablerate/tablerate.phtml' );
		}

		/**
     * Get create URL
     * 
     * @return string
     */
		function getCreateUrl() {
			return $this->getUrl( '*/*/new', array( 'website' => $this->getWebsiteId(  ) ) );
		}
	}

?>