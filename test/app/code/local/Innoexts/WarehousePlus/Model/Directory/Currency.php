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

	class Innoexts_WarehousePlus_Model_Directory_Currency extends Mage_Directory_Model_Currency {
		/**
     * Save currency rates
     *
     * @param array $rates
     * @return object
     */
		function saveRates($rates) {
			$eventPrefix = 'directory_currency_rates';
			Mage::dispatchEvent( $eventPrefix . '_save_before', array( 'rates' => $rates ) );
			$this->_getResource(  )->saveRates( $rates );
			Mage::dispatchEvent( $eventPrefix . '_save_after', array( 'rates' => $rates ) );
			return $this;
		}
	}

?>