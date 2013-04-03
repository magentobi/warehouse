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

	class Innoexts_Warehouse_Block_Checkout_Onepage_Shipping_Method_Available_Singlemode extends Mage_Checkout_Block_Onepage_Shipping_Method_Available {
		/**
     * Get shipping method
     * 
     * @return string
     */
		function getShippingMethod() {
			return $this->getAddressShippingMethod(  );
		}

		/**
     * Get shipping prices 
     */
		function getShippingPrices() {
			$shippingPrices = array(  );
			foreach ($this->getShippingRates(  ) as $carrierShippingRates) {
				foreach ($carrierShippingRates as $rate) {
					$shippingMethodCode = $rate->getCode(  );
					$price = (double)$rate->getPrice(  );
					$shippingPrices[$shippingMethodCode] = $price;
				}
			}

			return $shippingPrices;
		}

		/**
     * Get shipping prices JSON
     */
		function getShippingPricesJSON() {
			return Mage::helper( 'core' )->jsonEncode( $this->getShippingPrices(  ) );
		}

		/**
     * Get current shipping price
     * 
     * @return float
     */
		function getCurrentShippingPrice() {
			$price = null;
			$shippingMethod = $this->getShippingMethod(  );

			if ($shippingMethod) {
				foreach ($this->getShippingRates(  ) as $carrierShippingRates) {
					foreach ($carrierShippingRates as $rate) {
						$shippingMethodCode = $rate->getCode(  );

						if ($shippingMethodCode == $shippingMethod) {
							$price = (double)$rate->getPrice(  );
							break 2;
						}
					}
				}
			}

			return $price;
		}

		/**
     * Get current shipping price JS
     */
		function getCurrentShippingPriceJS() {
			return Mage::helper( 'core' )->jsonEncode( $this->getCurrentShippingPrice(  ) );
		}

		/**
     * Get customer address stock distance string
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getCustomerAddressStockDistanceString($stockId) {
			return $this->getWarehouseHelper(  )->getCustomerAddressStockDistanceString( $stockId );
		}
	}

?>