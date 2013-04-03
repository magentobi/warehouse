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

	class Innoexts_Warehouse_Helper_Directory_Currency extends Mage_Core_Helper_Abstract {
		private $dhfigdbhej = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get currency
     * 
     * @return Mage_Directory_Model_Currency
     */
		function getCurrency() {
			return Mage::getModel( 'directory/currency' );
		}

		/**
     * Get currency codes
     * 
     * @return array
     */
		function getCodes() {
			if (is_null( $this->dhfigdbhej )) {
				$dhfigdbhej = $this->getCurrency(  )->getConfigAllowCurrencies(  );
				sort( $dhfigdbhej );

				if (count( $dhfigdbhej )) {
					$codes = array(  );
					foreach ($dhfigdbhej as $code) {
						$code = strtoupper( $code );
						$codes[$code] = $code;
					}

					$this->dhfigdbhej = $codes;
				}
			}

			return $this->dhfigdbhej;
		}

		/**
     * Get current currency code
     * 
     * @return string
     */
		function getCurrentCode() {
			return $this->getWarehouseHelper(  )->getCurrentStore(  )->getCurrentCurrencyCode(  );
		}
	}

?>