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

	class Innoexts_Core_Model_Area_Abstract extends Innoexts_Core_Model_Abstract {
		/**
     * Get address helper
     * 
     * @return Innoexts_Core_Helper_Address
     */
		function getAddressHelper() {
			return $this->getCoreHelper(  )->getAddressHelper(  );
		}

		/**
     * Filter country
     * 
     * @param mixed $value
     * 
     * @return string
     */
		function filterCountry($country) {
			if ($country) {
				$country = $this->getAddressHelper(  )->castCountryId( $country );

				if ($country) {
					return $country;
				}
			}

			return '0';
		}

		/**
     * Get country filter
     * 
     * @return Zend_Filter
     */
		function getCountryFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterCountry' ) ) ) );
		}

		/**
     * Filter region
     * 
     * @param mixed $value
     * @param string $countryField
     * 
     * @return string
     */
		function filterRegion($region, $countryField) {
			$countryId = $this->filterCountry( $this->getData( $countryField ) );

			if (( $countryId && $region )) {
				$region = $this->getAddressHelper(  )->castRegionId( $countryId, $region );
			}


			if ($region) {
				return $region;
			}

			return '0';
		}

		/**
     * Get destination region filter
     * 
     * @param string $countryField
     * 
     * @return Zend_Filter
     */
		function getRegionFilter($countryField) {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterRegion' ), 'options' => array( $countryField ) ) ) );
		}

		/**
     * Filter zip
     * 
     * @param mixed $value
     * 
     * @return string
     */
		function filterZip($value) {
			return (( $value == '' || $value == '*' ) ? '' : $value);
		}

		/**
     * Get zip filter
     * 
     * @return Zend_Filter
     */
		function getZipFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterZip' ) ) ) );
		}

		/**
     * Get filters
     * 
     * @return array
     */
		function getFilters() {
			return array( 'country_id' => $this->getCountryFilter(  ), 'region_id' => $this->getRegionFilter( 'country_id' ), 'zip' => $this->getZipFilter(  ) );
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			return array( 'country_id' => $this->getTextValidator( false, 0, 4 ), 'region_id' => $this->getIntegerValidator( false, 0 ), 'zip' => $this->getTextValidator( false, 0, 10 ) );
		}

		/**
     * Get title
     * 
     * @return string
     */
		function getTitle() {
			$addressHelper = $this->getAddressHelper(  );
			$country = null;
			$region = null;

			if ($this->getCountryId(  )) {
				$country = $addressHelper->getCountryById( $this->getCountryId(  ) );
			}


			if ($this->getRegionId(  )) {
				$region = $addressHelper->getRegionById( $this->getRegionId(  ) );
			}

			$zip = $this->getZip(  );
			$title = implode( ', ', array( ($region ? $region->getName(  ) : '*'), ($zip ? $zip : '*'), ($country ? $country->getName(  ) : '*') ) );
			return $title;
		}
	}

?>