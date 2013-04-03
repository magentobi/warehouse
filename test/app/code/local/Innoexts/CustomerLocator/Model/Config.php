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

	class Innoexts_CustomerLocator_Model_Config extends Varien_Object {
		/**
     * Check if customer can change current address
     * 
     * @param mixed $store
     * 
     * @return boolean
     */
		function isAllowModification($store = null) {
			return Mage::getStoreConfigFlag( XML_PATH_CUSTOMER_LOCATOR_OPTION_ALLOW_MODIFICATION, $store );
		}

		/**
     * Check if default shipping address should be applied
     * 
     * @param mixed $store
     * 
     * @return boolean
     */
		function isUseDefaultShippingAddress($store = null) {
			return Mage::getStoreConfigFlag( XML_PATH_CUSTOMER_LOCATOR_OPTION_USE_DEFAULT_SHIPPING_ADDRESS, $store );
		}

		/**
     * Get country attribute
     * 
     * @return string
     */
		function getCountryAttribute() {
			return COUNTRY_ATTRIBUTE;
		}

		/**
     * Get region attribute
     * 
     * @return string
     */
		function getRegionAttribute() {
			return REGION_ATTRIBUTE;
		}

		/**
     * Get postcode attribute
     * 
     * @return string
     */
		function getPostcodeAttribute() {
			return POSTCODE_ATTRIBUTE;
		}

		/**
     * Get city attribute
     * 
     * @return string
     */
		function getCityAttribute() {
			return CITY_ATTRIBUTE;
		}

		/**
     * Get allowed attributes
     * 
     * @param mixed $store
     * 
     * @return array 
     */
		function getAllowedAttributes($store = null) {
			$attributes = Mage::getStoreConfig( XML_PATH_CUSTOMER_LOCATOR_OPTION_ALLOW_ATTRIBUTES, $store );
			return explode( ',', $attributes );
		}

		/**
     * Get required attributes
     * 
     * @param mixed $store
     * 
     * @return array 
     */
		function getRequiredAttributes($store = null) {
			$attributes = Mage::getStoreConfig( XML_PATH_CUSTOMER_LOCATOR_OPTION_REQUIRE_ATTRIBUTES, $store );
			return explode( ',', $attributes );
		}

		/**
     * Check if country is allowed
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isCountryAllowed($store = null) {
			return (in_array( $this->getCountryAttribute(  ), $this->getAllowedAttributes( $store ) ) ? true : false);
		}

		/**
     * Check if region is allowed
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isRegionAllowed($store = null) {
			return (in_array( $this->getRegionAttribute(  ), $this->getAllowedAttributes( $store ) ) ? true : false);
		}

		/**
     * Check if postcode is allowed
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isPostcodeAllowed($store = null) {
			return (in_array( $this->getPostcodeAttribute(  ), $this->getAllowedAttributes( $store ) ) ? true : false);
		}

		/**
     * Check if city is allowed
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isCityAllowed($store = null) {
			return (in_array( $this->getCityAttribute(  ), $this->getAllowedAttributes( $store ) ) ? true : false);
		}

		/**
     * Check if country is required
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isCountryRequired($store = null) {
			return (( $this->isCountryAllowed(  ) && in_array( $this->getCountryAttribute(  ), $this->getRequiredAttributes( $store ) ) ) ? true : false);
		}

		/**
     * Check if region is required
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isRegionRequired($store = null) {
			return (( $this->isRegionAllowed(  ) && in_array( $this->getRegionAttribute(  ), $this->getRequiredAttributes( $store ) ) ) ? true : false);
		}

		/**
     * Check if postcode is required
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isPostcodeRequired($store = null) {
			return (( $this->isPostcodeAllowed(  ) && in_array( $this->getPostcodeAttribute(  ), $this->getRequiredAttributes( $store ) ) ) ? true : false);
		}

		/**
     * Check if city is required
     * 
     * @param mixed $store
     * 
     * @return bool
     */
		function isCityRequired($store = null) {
			return (( $this->isCityAllowed(  ) && in_array( $this->getCityAttribute(  ), $this->getRequiredAttributes( $store ) ) ) ? true : false);
		}

		/**
     * Get default country identifier
     * 
     * @param mixed $store
     * 
     * @return boolean
     */
		function getDefaultCountryId($store = null) {
			return Mage::getStoreConfig( XML_PATH_CUSTOMER_LOCATOR_DEFAULT_ADDRESS_COUNTRY_ID, $store );
		}

		/**
     * Get default region identifier
     * 
     * @param mixed $store
     * 
     * @return boolean
     */
		function getDefaultRegionId($store = null) {
			return Mage::getStoreConfig( XML_PATH_CUSTOMER_LOCATOR_DEFAULT_ADDRESS_REGION_ID, $store );
		}

		/**
     * Get default postcode
     * 
     * @param mixed $store
     * 
     * @return boolean
     */
		function getDefaultPostcode($store = null) {
			return Mage::getStoreConfig( XML_PATH_CUSTOMER_LOCATOR_DEFAULT_ADDRESS_POSTCODE, $store );
		}

		/**
     * Get default city
     * 
     * @param mixed $store
     * 
     * @return boolean
     */
		function getDefaultCity($store = null) {
			return Mage::getStoreConfig( XML_PATH_CUSTOMER_LOCATOR_DEFAULT_ADDRESS_CITY, $store );
		}

		/**
     * Get default address
     * 
     * @param mixed $store
     * 
     * @return Varien_Object
     */
		function getDefaultAddress($store = null) {
			$location = new Varien_Object(  );
			$location->setCountryId( $this->getDefaultCountryId( $store ) )->setRegionId( $this->getDefaultRegionId( $store ) )->setPostcode( $this->getDefaultPostcode( $store ) )->setCity( $this->getDefaultCity( $store ) );
			return $location;
		}
	}

?>