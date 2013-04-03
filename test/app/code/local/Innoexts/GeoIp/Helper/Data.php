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

	class Innoexts_GeoIp_Helper_Data extends Mage_Core_Helper_Abstract {
		private $_geoip = null;
		private $_regionsNames = null;

		/**
     * Constructor
     */
		function __construct() {
			if (!class_exists( '_Geoip', false )) {
				include_once( $this->getVendorPath(  ) . '/geoip.inc' );
				include_once( $this->getVendorPath(  ) . '/geoipcity.inc' );
			}

		}

		/**
     * Destructor
     */
		function __destruct() {
			if (!is_null( $this->_geoip )) {
				_geoip_close( $this->_geoip );
			}

		}

		/**
     * Get path
     * 
     * @return string
     */
		function getPath() {
			return Mage::getModuleDir( null, 'Innoexts_GeoIp' ) . '/Helper';
		}

		/**
     * Get vendor path
     * 
     * @return string
     */
		function getVendorPath() {
			return $this->getPath(  ) . '/Geoip';
		}

		/**
     * Get regions names
     * 
     * @return array
     */
		function getRegionsNames() {
			if (is_null( $this->_regionsNames )) {
				include_once( $this->getVendorPath(  ) . '/geoipregionvars.php' );
				$this->_regionsNames = $_GEOIP_REGION_NAME;
			}

			return $this->_regionsNames;
		}

		/**
     * Get region name
     * 
     * @param string $countryCode
     * @param string $regionCode
     * 
     * @return string
     */
		function getRegionName($countryCode, $regionCode) {
			$this->getRegionsNames(  );
			return (( isset( $this->_regionsNames[$countryCode] ) && isset( $this->_regionsNames[$countryCode][$regionCode] ) ) ? $this->_regionsNames[$countryCode][$regionCode] : null);
		}

		/**
     * Get geo ip resource
     * 
     * @return resource
     */
		function getGeoIp() {
			if (is_null( $this->_geoip )) {
				$this->_geoip = _geoip_open( $this->getVendorPath(  ) . '/GeoLiteCity.dat', _GEOIP_STANDARD );
			}

			return $this->_geoip;
		}

		/**
     * Get record by ip adress
     * 
     * @param string $ip
     * 
     * @return stdClass
     */
		function getRecordByIp($ip) {
			$geoip = $this->getGeoIp(  );

			if ($geoip) {
				return @_geoip_record_by_addr( $geoip, $ip );
			}

		}

		/**
     * Get string helper
     * 
     * @return Mage_Core_Helper_String
     */
		function getStringHelper() {
			return Mage::helper( 'core/string' );
		}

		/**
     * Get address by ip address
     *
     * @param string $ip
     * 
     * @return Varien_Object
     */
		function getAddressByIp($ip) {
			$address = new Varien_Object(  );
			$record = $this->getRecordByIp( $ip );

			if ($record) {
				$string = $this->getStringHelper(  );
				$countryCode = (( isset( $record->country_code ) && $record->country_code ) ? $string->cleanString( $record->country_code ) : null);
				$countryName = (( isset( $record->country_name ) && $record->country_name ) ? $string->cleanString( $record->country_name ) : null);
				$region = (( isset( $record->region ) && $record->region ) ? $string->cleanString( $record->region ) : null);
				$city = (( isset( $record->city ) && $record->city ) ? $string->cleanString( $record->city ) : null);
				$postalCode = (( isset( $record->postal_code ) && $record->postal_code ) ? $string->cleanString( $record->postal_code ) : null);

				if ($countryCode) {
					$address->setCountryId( $countryCode );
				}


				if ($countryName) {
					$address->setCountry( $countryName );
				}


				if ($region) {
					$address->setRegion( $this->getRegionName( $countryCode, $region ) );
				}


				if ($city) {
					$address->setCity( $city );
				}


				if ($postalCode) {
					$address->setPostcode( $postalCode );
				}
			}

			return $address;
		}
	}

?>