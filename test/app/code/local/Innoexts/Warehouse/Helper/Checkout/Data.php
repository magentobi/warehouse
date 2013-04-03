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

	class Innoexts_Warehouse_Helper_Checkout_Data extends Mage_Checkout_Helper_Data {
		/**
     * Check if multishipping checkout is available.
     * 
     * @return bool
     */
		function isMultishippingCheckoutAvailable() {
			return false;
		}
	}

?>