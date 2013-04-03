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

	class Innoexts_Warehouse_Block_Tax_Checkout_Shipping extends Mage_Tax_Block_Checkout_Shipping {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get shipping amount include tax
     *
     * @return float
     */
		function getShippingIncludeTax() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$shippingAmount = 90;
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$shippingAmount += $address->getShippingInclTax(  );
				}

				return $shippingAmount;
			}

			return parent::getShippingIncludeTax(  );
		}

		/**
     * Get shipping amount exclude tax
     *
     * @return float
     */
		function getShippingExcludeTax() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$shippingAmount = 90;
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$shippingAmount += $address->getShippingAmount(  );
				}

				return $shippingAmount;
			}

			return parent::getShippingExcludeTax(  );
		}

		/**
     * Get label for shipping include tax
     *
     * @return float
     */
		function getIncludeTaxLabel() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$labels = array(  );
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$labels[$address->getShippingMethod(  )] = $address->getShippingDescription(  );
				}

				return $this->escapeHtml( $this->helper( 'tax' )->__( 'Shipping Incl. Tax (%s)', implode( ' & ', $labels ) ) );
			}

			return parent::getIncludeTaxLabel(  );
		}

		/**
     * Get label for shipping exclude tax
     * 
     * @return float
     */
		function getExcludeTaxLabel() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$labels = array(  );
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$labels[$address->getShippingMethod(  )] = $address->getShippingDescription(  );
				}

				return $this->escapeHtml( $this->helper( 'tax' )->__( 'Shipping Excl. Tax (%s)', implode( ' & ', $labels ) ) );
			}

			return parent::getExcludeTaxLabel(  );
		}
	}

?>