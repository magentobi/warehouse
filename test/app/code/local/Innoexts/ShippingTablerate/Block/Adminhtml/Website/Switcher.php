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

	class Innoexts_ShippingTablerate_Block_Adminhtml_Website_Switcher extends Mage_Adminhtml_Block_Template {
		private $_website = null;
		private $_websiteVarName = 'website';
		private $_hasDefaultOption = false;

		/**
     * Get shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getShippingTablerateHelper() {
			return Mage::helper( 'shippingtablerate' );
		}

		/**
     * Get text helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getTextHelper() {
			return $this->getShippingTablerateHelper(  );
		}

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'shippingtablerate/website/switcher.phtml' );
			$this->setUseConfirm( true );
			$this->setUseAjax( true );
			$this->setDefaultWebsiteName( $this->getTextHelper(  )->__( 'All Websites' ) );
		}

		/**
     * Get websites
     *
     * @return array
     */
		function getWebsites() {
			return $this->getShippingTablerateHelper(  )->getWebsites(  );
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
     * Set/Get whether the switcher should show default option
     * 
     * @param bool $hasDefaultOption
     * 
     * @return bool
     */
		function hasDefaultOption($hasDefaultOption = null) {
			if (null !== $hasDefaultOption) {
				$this->_hasDefaultOption = $hasDefaultOption;
			}

			return $this->_hasDefaultOption;
		}

		/**
     * Get switch URL
     * 
     * @return string
     */
		function getSwitchUrl() {

			if ($url = $this->getData( 'switch_url' )) {
				return $url;
			}

			return $this->getUrl( '*/*/*', array( '_current' => true, $this->_websiteVarName => null ) );
		}
	}

?>