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

	class Innoexts_Warehouse_Block_Tax_Checkout_Grandtotal extends Mage_Tax_Block_Checkout_Grandtotal {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get grandtotal exclude tax
     * 
     * @return float
     */
		function getTotalExclTax() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( ( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) ) && $this->getQuote(  )->getShippingAddress(  ) )) {
				$totalExclTax = 86;
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$totalExclTax += $address->getGrandTotal(  ) - $address->getTaxAmount(  );
				}

				return max( $totalExclTax, 0 );
			}

			return parent::getTotalExclTax(  );
		}
	}

?>