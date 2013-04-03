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

	class Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Edit extends Innoexts_Core_Block_Adminhtml_Widget_Form_Container {
		private $_objectId = 'tablerate_id';
		private $_blockGroup = 'shippingtablerate';
		private $_blockSubGroup = 'adminhtml';
		private $_controller = 'tablerate';
		private $_addLabel = 'New Rate';
		private $_editLabel = 'Edit Rate \'%s\'';
		private $_saveLabel = 'Save Rate';
		private $_deleteLabel = 'Delete Rate';
		private $_modelName = 'shippingtablerate';
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
     * @return  bool
     */
		function isAllowedAction($action) {
			return $this->getAdminSession(  )->isAllowed( 'sales/shipping/tablerates/' . $action );
		}

		/**
     * Preparing block layout
     *
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Edit
     */
		function _prepareLayout() {
			$json = Mage::helper( 'innoexts_core' )->getDirectoryHelper(  )->getRegionJson2(  );
			$this->_formScripts[] = 'var updater = new RegionUpdater("shippingtablerate_dest_country_id", "none", "shippingtablerate_dest_region_id", ' . $json . ', "disable")';
			parent::_prepareLayout(  );
			return $this;
		}

		/**
     * Get Url parameters
     * 
     * @return array
     */
		function getUrlParams() {
			return array( 'website' => $this->getWebsiteId(  ) );
		}

		/**
     * Get URL for back (reset) button
     * 
     * @return string
     */
		function getBackUrl() {
			return $this->getUrl( '*/*/', $this->getUrlParams(  ) );
		}

		/**
     * Get URL for delete button
     */
		function getDeleteUrl() {
			return $this->getUrl( '*/*/delete', array_merge( array( $this->_objectId => $this->getRequest(  )->getParam( $this->_objectId ) ), $this->getUrlParams(  ) ) );
		}

		/**
     * Get form action URL
     *
     * @return string
     */
		function getFormActionUrl() {
			if ($this->hasFormActionUrl(  )) {
				return $this->getData( 'form_action_url' );
			}

			return $this->getUrl( '*/' . $this->_controller . '/save', $this->getUrlParams(  ) );
		}
	}

?>