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

	class Innoexts_CustomerLocator_Model_Session extends Mage_Core_Model_Session_Abstract {
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
     * Retrieve customer sharing configuration model
     *
     * @return Mage_Customer_Model_Config_Share
     */
		function getCustomerConfigShare() {
			return Mage::getSingleton( 'customer/config_share' );
		}

		/**
     * Get core helper
     * 
     * @return Innoexts_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'innoexts_core' );
		}

		/**
     * Get address helper
     * 
     * @return Innoexts_Core_Helper_Address
     */
		function getAddressHelper() {
			return $this->getCoreHelper(  )->getAddressHelper(  );
		}

		/**
     * Get geo ip helper
     * 
     * @return Innoexts_GeoIp_Helper_Data
     */
		function getGeoIpHelper() {
			return Mage::helper( 'innoexts_geoip' );
		}

		/**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
		function getStore() {
			return $this->getCustomerLocatorHelper(  )->getStore(  );
		}

		/**
     * Constructor
     */
		function __construct() {
			$namespace = 'customerlocator';

			if ($this->getCustomerConfigShare(  )->isWebsiteScope(  )) {
				$namespace .= '_' . $this->getStore(  )->getWebsite(  )->getCode(  );
			}

			$this->init( $namespace );
			Mage::dispatchEvent( 'customerlocator_session_init', array( 'customerlocator_session' => $this ) );
		}

		/**
     * Get current address
     * 
     * @return Varien_Object
     */
		function _getAddress() {
			if (is_null( $this->_address )) {
				$helper = $this->getCustomerLocatorHelper(  );
				$address = new Varien_Object(  );

				if ($helper->isCountryAllowed(  )) {
					$address->setCountryId( $this->getCountryId(  ) );
				}


				if ($helper->isRegionAllowed(  )) {
					$address->setRegionId( $this->getRegionId(  ) );
					$address->setRegion( $this->getRegion(  ) );
				}


				if ($helper->isCityAllowed(  )) {
					$address->setCity( $this->getCity(  ) );
				}


				if ($helper->isPostcodeAllowed(  )) {
					$address->setPostcode( $this->getPostcode(  ) );
				}

				$this->_address = $address;
			}

			return $this->_address;
		}

		/**
     * Check if address is empty
     * 
     * @return bool
     */
		function isAddressEmpty() {
			$this->_getAddress(  );
			return $this->getAddressHelper(  )->isEmpty( $this->_address );
		}

		/**
     * Get ip address
     * 
     * @return string
     */
		function getIp() {
			$ip = Mage::helper( 'core/http' )->getRemoteAddr(  );
			return ($ip ? long2ip( ip2long( $ip ) ) : null);
		}

		/**
     * Get geo ip address
     * 
     * @return Varien_Object
     */
		function getGeoIpAddress() {
			$address = null;
			$addressHelper = $this->getAddressHelper(  );
			$ip = $this->getIp(  );

			if ($ip) {
				$_address = $this->getGeoIpHelper(  )->getAddressByIp( $ip );

				if ($_address) {
					$_address = $addressHelper->cast( $_address );

					if (!$addressHelper->isEmpty( $_address )) {
						$address = $_address;
					}
				}
			}

			return $address;
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
     * Get customer default address
     * 
     * @return Varien_Object
     */
		function getCustomerDefaultAddress() {
			$address = null;
			$addressHelper = $this->getAddressHelper(  );

			if ($this->isCustomerLoggedIn(  )) {
				$_address = $this->getCustomer(  )->getDefaultShippingAddress(  );

				if ($_address) {
					$_address = $addressHelper->cast( $_address );

					if (!$addressHelper->isEmpty( $_address )) {
						$address = $addressHelper;
					}
				}
			}

			return $address;
		}

		/**
     * Get default address
     * 
     * @return Varien_Object
     */
		function getDefaultAddress() {
			$addressHelper = $this->getAddressHelper(  );
			$address = $this->getCustomerLocatorHelper(  )->getDefaultAddress(  );
			return $addressHelper->cast( $address );
		}

		/**
     * Locate address
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
		function locateAddress() {
			$helper = $this->getCustomerLocatorHelper(  );
			$address = null;

			if ($helper->isUseDefaultShippingAddress(  )) {
				$address = $this->getCustomerDefaultAddress(  );
			}


			if (!$address) {
				$address = $this->getGeoIpAddress(  );
			}


			if (!$address) {
				$address = $this->getDefaultAddress(  );
			}

			$this->setAddress( $address );
			return $this;
		}

		/**
     * Set shipping address
     * 
     * @param Varien_Object $shippingAddress
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
		function setAddress($address) {
			$helper = $this->getCustomerLocatorHelper(  );
			$address = $this->getAddressHelper(  )->cast( $address );
			$this->unsetAddress(  );

			if ($helper->isCountryAllowed(  )) {
				$this->setCountryId( $address->getCountryId(  ) );
			}


			if ($helper->isRegionAllowed(  )) {
				$this->setRegionId( $address->getRegionId(  ) );
				$this->setRegion( $address->getRegion(  ) );
			}


			if ($helper->isCityAllowed(  )) {
				$this->setCity( $address->getCity(  ) );
			}


			if ($helper->isPostcodeAllowed(  )) {
				$this->setPostcode( $address->getPostcode(  ) );
			}

			$this->_address = $address;
			return $this;
		}

		/**
     * Set address identifier 
     * 
     * @param int $addressId
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
		function setAddressId($addressId) {
			$addressHelper = $this->getAddressHelper(  );

			if ($this->isCustomerLoggedIn(  )) {
				$address = $this->getCustomer(  )->getAddressById( $addressId );

				if ($address) {
					$address = $addressHelper->cast( $address );

					if (!$addressHelper->isEmpty( $address )) {
						$this->setAddress( $address );
						$this->setData( 'address_id', $addressId );
					}
				}
			}

			return $this;
		}

		/**
     * Retrieve address
     * 
     * @return Varien_Object
     */
		function getAddress() {
			$this->_getAddress(  );

			if ($this->isAddressEmpty(  )) {
				$this->locateAddress(  );
			}

			return $this->_address;
		}

		/**
     * Unset address
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
		function unsetAddress() {
			$this->setCountryId( null );
			$this->setRegionId( null );
			$this->setRegion( null );
			$this->setCity( null );
			$this->setPostcode( null );
			$this->_address = null;
			return $this;
		}
	}

?>