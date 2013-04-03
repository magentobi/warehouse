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

	class Innoexts_Warehouse_Block_Checkout_Onepage_Shipping extends Mage_Checkout_Block_Onepage_Shipping {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Return Sales Quote Address model
     * 
     * @return Mage_Sales_Model_Quote_Address
     */
		function getAddress() {
			if (is_null( $this->_address )) {
				$address = null;

				if ($this->isCustomerLoggedIn(  )) {
					$address = $this->getQuote(  )->getShippingAddress(  );
				} 
else {
					$address = Mage::getModel( 'sales/quote_address' );
				}

				$this->getWarehouseHelper(  )->copyCustomerAddressIfEmpty( $address );
				$this->_address = $address;
			}

			return $this->_address;
		}
	}

?>