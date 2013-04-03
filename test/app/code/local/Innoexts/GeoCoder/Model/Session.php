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

	class Innoexts_GeoCoder_Model_Session extends Mage_Core_Model_Session_Abstract {
		/**
     * Constructor
     */
		function __construct() {
			$namespace = 'innoexts_geocoder';

			if ($this->getCustomerConfigShare(  )->isWebsiteScope(  )) {
				$namespace .= '_' . Mage::app(  )->getStore(  )->getWebsite(  )->getCode(  );
			}

			$this->init( $namespace );
			Mage::dispatchEvent( 'innoexts_geocoder_session_init', array( 'geocoder_session' => $this ) );
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
     * Get key by address
     * 
     * @param string $address
     * 
     * @return string
     */
		function getKeyByAddress($address) {
			return 'hash' . md5( $address );
		}

		/**
     * Set coordinates for address
     * 
     * @param string $address
     * @param Varien_Object $coordinates
     * 
     * @return Innoexts_GeoCoder_Model_Session
     */
		function setCoordinates($address, $coordinates) {
			$value = implode( ':', array( $coordinates->getLatitude(  ), $coordinates->getLongitude(  ) ) );
			$this->setDataUsingMethod( $this->getKeyByAddress( $address ), $value );
			return $this;
		}

		/**
     * Get coordinates by address
     * 
     * @param string $address
     * 
     * @return Varien_Object
     */
		function getCoordinates($address) {
			$coordinates = null;
			$value = $this->getDataUsingMethod( $this->getKeyByAddress( $address ) );

			if ($value) {
				$pieces = explode( ':', $value );

				if (( ( ( ( count( $pieces ) && isset( $pieces[0] ) ) && isset( $pieces[1] ) ) && $pieces[0] ) && $pieces[1] )) {
					$coordinates = new Varien_Object(  );
					$coordinates->setLatitude( floatval( $pieces[0] ) );
					$coordinates->setLongitude( floatval( $pieces[1] ) );
				}
			}

			return $coordinates;
		}
	}

?>