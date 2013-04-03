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

	class Innoexts_Warehouse_Block_Checkout_Onepage_Shipping_Method_Available_Multiplemode extends Mage_Checkout_Block_Onepage_Abstract {
		private $_rates = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get warehouses
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouses() {
			return $this->getQuote(  )->getWarehouses(  );
		}

		/**
     * Get addresses
     * 
     * @return array of Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function getAddresses() {
			return $this->getQuote(  )->getAllShippingAddresses(  );
		}

		/**
     * Get shipping method
     * 
     * @param int $stockId
     * @return string
     */
		function getShippingMethod($stockId) {
			$shippingAddress = $this->getQuote(  )->getShippingAddress2( $stockId );

			if ($shippingAddress) {
				return $shippingAddress->getShippingMethod(  );
			}

		}

		/**
     * Get shipping rates
     * 
     * @param int $stockId
     * @return array
     */
		function getShippingRates($stockId) {
			if (is_null( $this->_rates )) {
				$this->getQuote(  )->getShippingAddress(  )->collectShippingRates(  )->save(  );
				$rates = array(  );
				foreach ($this->getAddresses(  ) as $address) {

					if (( $address->getStockId(  ) && !$address->isVirtual(  ) )) {
						$rates[$address->getStockId(  )] = $address->getGroupedAllShippingRates(  );
						continue;
					}
				}

				$this->_rates = $rates;
			}


			if (isset( $this->_rates[$stockId] )) {
				return $this->_rates[$stockId];
			}

			return array(  );
		}

		/**
     * Get shipping price
     * 
     * @param float $price
     * @param bool $flag
     * @return float
     */
		function getShippingPrice($stockId, $price, $flag) {
			$shippingAddress = $this->getQuote(  )->getShippingAddress2( $stockId );

			if ($shippingAddress) {
				$taxHelper = Mage::helper( 'tax' );
				$store = $this->getQuote(  )->getStore(  );
				return $store->convertPrice( $taxHelper->getShippingPrice( $price, $flag, $shippingAddress ), true );
			}

		}

		/**
     * Get carrier name
     * 
     * @param string $carrierCode
     * 
     * @return string
     */
		function getCarrierName($carrierCode) {
			return $this->getWarehouseHelper(  )->getShippingHelper(  )->getCarrierName( $carrierCode );
		}

		/**
     * Get shipping prices
     * 
     * @return array
     */
		function getShippingPrices() {
			$shippingPrices = array(  );
			foreach ($this->getAddresses(  ) as $shippingAddress) {

				if ($shippingAddress->isVirtual(  )) {
					continue;
				}

				$stockId = (int)$shippingAddress->getStockId(  );

				if (!isset( $shippingPrices[$stockId] )) {
					$shippingRates = $this->getShippingRates( $stockId );
					foreach ($shippingRates as $carrierShippingRates) {
						foreach ($carrierShippingRates as $rate) {
							$shippingMethodCode = $rate->getCode(  );
							$price = (double)$rate->getPrice(  );
							$shippingPrices[$stockId][$shippingMethodCode] = $price;
						}
					}

					continue;
				}
			}

			return $shippingPrices;
		}

		/**
     * Get shipping prices JSON
     * 
     * @return string
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
			$price = array(  );
			foreach ($this->getAddresses(  ) as $shippingAddress) {

				if ($shippingAddress->isVirtual(  )) {
					continue;
				}

				$stockId = (int)$shippingAddress->getStockId(  );

				if (!isset( $price[$stockId] )) {
					$shippingMethod = $this->getShippingMethod( $stockId );
					$shippingRates = $this->getShippingRates( $stockId );
					foreach ($shippingRates as $carrierShippingRates) {
						foreach ($carrierShippingRates as $rate) {
							$shippingMethodCode = $rate->getCode(  );

							if ($shippingMethodCode == $shippingMethod) {
								$price[$stockId] = (double)$rate->getPrice(  );
								break 2;
							}
						}
					}

					continue;
				}
			}

			return $price;
		}

		/**
     * Get current shipping price JS
     * 
     * @return string
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