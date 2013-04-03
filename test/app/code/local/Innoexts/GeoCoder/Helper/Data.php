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

	class Innoexts_GeoCoder_Helper_Data extends Mage_Core_Helper_Abstract {
		private $_httpClient = null;
		private $_uri = 'http://maps.googleapis.com/maps/api/geocode/json';

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
     * Get HTTP client
     * 
     * @return Zend_Http_Client
     */
		function getHttpClient() {
			if (is_null( $this->_httpClient )) {
				$this->_httpClient = new Zend_Http_Client(  );
			}

			return $this->_httpClient;
		}

		/**
     * Get URI
     *
     * @return string
     */
		function getUri() {
			return $this->_uri;
		}

		/**
     * Get session
     * 
     * @return Innoexts_GeoCoder_Model_Session
     */
		function getSession() {
			return Mage::getSingleton( 'innoexts_geocoder/session' );
		}

		/**
     * Get coordinates object
     * 
     * @param Varien_Object $address
     * 
     * @return Varien_Object
     */
		function getCoordinates($address) {
			$coordinates = new Varien_Object(  );
			$addressHelper = $this->getAddressHelper(  );

			if (!$addressHelper->isEmpty( $address )) {
				$address = $addressHelper->cast( $address );
				$addressString = $addressHelper->format( $address );

				if ($addressString) {
					$_coordinates = $this->getSession(  )->getCoordinates( $addressString );

					if (!$_coordinates) {
						$httpClient = $this->getHttpClient(  );
						$httpClient->setUri( $this->getUri(  ) );
						$httpClient->setParameterGet( 'address', $addressString );
						$httpClient->setParameterGet( 'sensor', 'false' );
						$responce = $httpClient->request( 'GET' );

						if ($responce) {
							$data = Zend_Json_Decoder::decode( $responce->getBody(  ), TYPE_OBJECT );
						}


						if ($data) {
							if (( isset( $data->status ) && strtoupper( trim( $data->status ) ) == 'OK' )) {
								if (( ( isset( $data->results ) && isset( $data->results[0] ) ) && isset( $data->results[0]->geometry ) )) {
									$geometry = $data->results[0]->geometry;

									if (( ( isset( $geometry->location ) && isset( $geometry->location->lat ) ) && isset( $geometry->location->lng ) )) {
										$location = $geometry->location;
										$coordinates->setLatitude( $location->lat );
										$coordinates->setLongitude( $location->lng );
										$this->getSession(  )->setCoordinates( $addressString, $coordinates );
									}
								}
							}
						}
					} 
else {
						$coordinates = $data;
					}
				}
			}

			return $coordinates;
		}
	}

?>