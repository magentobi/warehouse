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

	class Innoexts_CustomerLocator_Block_Customer_Address extends Mage_Core_Block_Template {
		private $_address = null;

		/**
     * Get customer locator helper
     *
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function getCustomerLocatorHelper() {
			return Mage::helper( 'customerlocator' );
		}

		/**
     * Get address
     *
     * @return Varien_Object
     */
		function getAddress() {
			if (is_null( $this->_address )) {
				$this->_address = $this->getCustomerLocatorHelper(  )->getCustomerAddress(  );
			}

			return $this->_address;
		}

		/**
     * Get country identifier
     * 
     * @return string
     */
		function getCountryId() {
			return $this->getAddress(  )->getCountryId(  );
		}

		/**
     * Get region identifier
     * 
     * @return string
     */
		function getRegionId() {
			return $this->getAddress(  )->getRegionId(  );
		}

		/**
     * Get region
     * 
     * @return string
     */
		function getRegion() {
			return $this->getAddress(  )->getRegion(  );
		}

		/**
     * Get city
     * 
     * @return string
     */
		function getCity() {
			return $this->getAddress(  )->getCity(  );
		}

		/**
     * Get postal code
     * 
     * @return string
     */
		function getPostcode() {
			return $this->getAddress(  )->getPostcode(  );
		}

		/**
     * Get customer session
     * 
     * @return Mage_Customer_Model_Session
     */
		function getCustomerSession() {
			return Mage::getSingleton( 'customer/session' );
		}

		/**
     * Check if customer is logged in
     * 
     * @return bool
     */
		function isCustomerLoggedIn() {
			return $this->getCustomerSession(  )->isLoggedIn(  );
		}

		/**
     * Get customer
     * 
     * @return Mage_Customer_Model_Customer
     */
		function getCustomer() {
			return $this->getCustomerSession(  )->getCustomer(  );
		}

		/**
     * Get addresses
     * 
     * @return Mage_Customer_Model_Customer
     */
		function getAddresses() {
			$addresses = array(  );

			if ($this->isCustomerLoggedIn(  )) {
				$addresses = $this->getCustomer(  )->getAddresses(  );
			}

			return $addresses;
		}

		/**
     * Check if customer has addresses
     * 
     * @return bool
     */
		function hasAddresses() {
			return (count( $this->getAddresses(  ) ) ? true : false);
		}

		/**
     * Get addresses options
     * 
     * @return array 
     */
		function getAddressesOptions() {
			$helper = $this->getCustomerLocatorHelper(  );
			$options = array(  );
			$addresses = $this->getAddresses(  );
			array_push( $options, array( 'value' => '', 'label' => $helper->__( 'Please select address' ) ) );
			foreach ($addresses as $address) {
				array_push( $options, array( 'value' => $address->getId(  ), 'label' => $address->format( 'oneline' ) ) );
			}

			return $options;
		}

		/**
     * Get customer address html select
     * 
     * @return string
     */
		function getAddressHtmlSelect() {
			$helper = $this->getCustomerLocatorHelper(  );
			$addressId = null;
			$select = $this->getLayout(  )->createBlock( 'core/html_select' )->setName( 'address_id' )->setId( 'address_id' )->setTitle( $helper->__( 'Address' ) )->setClass( 'validate-select' )->setValue( $addressId )->setOptions( $this->getAddressesOptions(  ) )->setExtraParams( 'onchange="customerAddressIdForm.submit();"' );
			return $select->getHtml(  );
		}
	}

?>