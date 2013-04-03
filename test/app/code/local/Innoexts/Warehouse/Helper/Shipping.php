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

	class Innoexts_Warehouse_Helper_Shipping extends Mage_Core_Helper_Abstract {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get carrier name
     * 
     * @param string $carrierCode
     * 
     * @return string
     */
		function getCarrierName($carrierCode) {
			$name = Mage::getStoreConfig( 'carriers/' . $carrierCode . '/title' );

			if ($name) {
				return $name;
			}

			return $carrierCode;
		}
	}

?>