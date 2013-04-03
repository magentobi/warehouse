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

	class Innoexts_CustomerLocator_Helper_Data extends Mage_Core_Helper_Abstract {
		/**
     * Get session
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
		function getSession() {
			return Mage::getSingleton( 'customerlocator/session' );
		}

		/**
     * Get config
     * 
     * @return Innoexts_CustomerLocator_Model_Config
     */
		function getConfig() {
			return Mage::getSingleton( 'customerlocator/config' );
		}

		/**
     * Get customer address
     * 
     * @return Varien_Object
     */
		function getCustomerAddress() {
			return $this->getSession(  )->getAddress(  );
		}

		/**
     * Set customer address
     * 
     * @param Varien_Object $address
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function setCustomerAddress($address) {
			$this->getSession(  )->setAddress( $address );
			return $this;
		}

		/**
     * Set customer address identifier
     * 
     * @param int $addressId
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function setCustomerAddressId($addressId) {
			$this->getSession(  )->setAddressId( $addressId );
			return $this;
		}

		/**
     * Unset customer address
     * 
     * @param Varien_Object $address
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
		function unsetCustomerAddress() {
			$this->getSession(  )->unsetAddress(  );
			return $this;
		}

		/**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
		function getStore() {
			return Mage::app(  )->getStore(  );
		}

		/**
     * Get attributes
     * 
     * @return array
     */
		function getAttributes() {
			$config = $this->getConfig(  );
			return array( $config->getCountryAttribute(  ) => $this->__( 'Country' ), $config->getRegionAttribute(  ) => $this->__( 'Region / State' ), $config->getPostcodeAttribute(  ) => $this->__( 'Zip / Postal Code' ), $config->getCityAttribute(  ) => $this->__( 'City' ) );
		}

		/**
     * Get attributes options
     * 
     * @return array
     */
		function getAttributesOptions() {
			$options = array(  );
			$attributes = $this->getAttributes(  );
			foreach ($attributes as $name) {
				array_push( $options, array( 'value' => $attribute, 'label' => $name ) );
			}

			return $options;
		}

		/**
     * Get attribute name
     * 
     * @param string $attribute
     * 
     * @return string
     */
		function getAttributeName($attribute) {
			$attributes = $this->getAttributes(  );

			if (isset( $attributes[$attribute] )) {
				return $attributes[$attribute];
			}

		}

		/**
     * Check if customer can change current address
     * 
     * @return bool
     */
		function isAllowModification() {
			return $this->getConfig(  )->isAllowModification( $this->getStore(  ) );
		}

		/**
     * Check if default shipping address should be applied
     * 
     * @return bool
     */
		function isUseDefaultShippingAddress() {
			return $this->getConfig(  )->isUseDefaultShippingAddress( $this->getStore(  ) );
		}

		/**
     * Check if country is allowed
     *
     * @return bool
     */
		function isCountryAllowed() {
			return $this->getConfig(  )->isCountryAllowed( $this->getStore(  ) );
		}

		/**
     * Check if region is allowed
     *
     * @return bool
     */
		function isRegionAllowed() {
			return $this->getConfig(  )->isRegionAllowed( $this->getStore(  ) );
		}

		/**
     * Check if city is allowed
     * 
     * @return bool
     */
		function isCityAllowed() {
			return $this->getConfig(  )->isCityAllowed( $this->getStore(  ) );
		}

		/**
     * Check if postal code is allowed
     *
     * @return bool
     */
		function isPostcodeAllowed() {
			return $this->getConfig(  )->isPostcodeAllowed( $this->getStore(  ) );
		}

		/**
     * Check if country is required
     *
     * @return bool
     */
		function isCountryRequired() {
			return $this->getConfig(  )->isCountryRequired( $this->getStore(  ) );
		}

		/**
     * Check if region is required
     *
     * @return bool
     */
		function isRegionRequired() {
			return $this->getConfig(  )->isRegionRequired( $this->getStore(  ) );
		}

		/**
     * Check if city is required
     * 
     * @return bool
     */
		function isCityRequired() {
			return $this->getConfig(  )->isCityRequired( $this->getStore(  ) );
		}

		/**
     * Check if postal code is required
     * 
     * @return bool
     */
		function isPostcodeRequired() {
			return $this->getConfig(  )->isPostcodeRequired( $this->getStore(  ) );
		}

		/**
     * Get default address
     * 
     * @return Varien_Object
     */
		function getDefaultAddress() {
			return $this->getConfig(  )->getDefaultAddress( $this->getStore(  ) );
		}
	}

?>