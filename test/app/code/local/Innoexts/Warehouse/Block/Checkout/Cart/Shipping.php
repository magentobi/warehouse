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

	class Innoexts_Warehouse_Block_Checkout_Cart_Shipping extends Mage_Checkout_Block_Cart_Shipping {
		private $_rates2 = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
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
     * Get shipping rates
     * 
     * @param int $stockId
     * 
     * @return array
     */
		function getShippingRates2($stockId) {
			if (is_null( $this->_rates2 )) {
				$this->getAddress(  )->collectShippingRates(  )->save(  );
				$rates = array(  );
				foreach ($this->getAddresses(  ) as $address) {
					$stockId = (int)$address->getStockId(  );

					if ($stockId) {
						$rates[$stockId] = $address->getGroupedAllShippingRates(  );
						continue;
					}
				}

				$this->_rates2 = $rates;
			}


			if (isset( $this->_rates2[$stockId] )) {
				return $this->_rates2[$stockId];
			}

			return array(  );
		}

		/**
     * Check if no shipping rates
     * 
     * @return bool
     */
		function isShippingRatesEmpty() {
			$isEmpty = true;
			$addresses = $this->getAddresses(  );

			if (count( $addresses )) {
				$isEmpty = false;
				foreach ($addresses as $address) {
					$stockId = (int)$address->getStockId(  );
					$rates = ($stockId ? $this->getShippingRates2( $stockId ) : array(  ));

					if (!count( $rates )) {
						$isEmpty = true;
						break;
					}
				}
			}

			return $isEmpty;
		}

		/**
     * Get address shipping method
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getAddressShippingMethod2($stockId) {
			$shippingAddress = $this->getQuote(  )->getShippingAddress2( $stockId );

			if ($shippingAddress) {
				return $shippingAddress->getShippingMethod(  );
			}

		}

		/**
     * Get shipping price
     * 
     * @param float $price
     * @param bool $flag
     * 
     * @return float
     */
		function getShippingPrice2($stockId, $price, $flag) {
			$quote = $this->getQuote(  );
			$shippingAddress = $quote->getShippingAddress2( $stockId );

			if ($shippingAddress) {
				$taxHelper = $this->getWarehouseHelper(  )->getTaxHelper(  );
				return $quote->getStore(  )->convertPrice( $taxHelper->getShippingPrice( $price, $flag, $shippingAddress ), true );
			}

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