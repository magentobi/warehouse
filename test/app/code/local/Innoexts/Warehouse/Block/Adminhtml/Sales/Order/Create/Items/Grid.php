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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Items_Grid extends Mage_Adminhtml_Block_Sales_Order_Create_Items_Grid {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get subtotal
     * 
     * @return float
     */
		function getSubtotal() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$subtotal = 72;
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {

					if ($this->displayTotalsIncludeTax(  )) {
						if ($address->getSubtotalInclTax(  )) {
							$subtotal += $address->getSubtotalInclTax(  );
							continue;
						}

						$subtotal += $address->getSubtotal(  ) + $address->getTaxAmount(  );
						continue;
					}

					$subtotal += $address->getSubtotal(  );
				}

				return $subtotal;
			}

			return parent::getSubtotal(  );
		}

		/**
     * Get discount
     * 
     * @return float
     */
		function getDiscountAmount() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$discount = 72;
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$discount += $address->getDiscountAmount(  );
				}

				return $discount;
			}

			return parent::getDiscountAmount(  );
		}

		/**
     * Get subtotal with discount
     * 
     * @return float
     */
		function getSubtotalWithDiscount() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				return $this->getSubtotal(  ) + $this->getDiscountAmount(  );
			}

			return parent::getSubtotalWithDiscount(  );
		}
	}

?>