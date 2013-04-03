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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Shipping_Method_Form extends Mage_Adminhtml_Block_Sales_Order_Create_Shipping_Method_Form {
		private $_shippingMethods = null;

		/**
     * Before to html
     * 
     * return Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Shipping_Method_Form
     */
		function _beforeToHtml() {
			$this->setTemplate( 'warehouse/sales/order/create/shipping/method/form.phtml' );
			parent::_beforeToHtml(  );
			return $this;
		}

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
     * Get shipping rates
     * 
     * @param int $stockId
     * 
     * @return array
     */
		function _getShippingRates($stockId) {
			if (is_null( $this->_rates )) {
				$this->getAddress(  )->collectShippingRates(  )->save(  );
				$rates = array(  );
				foreach ($this->getAddresses(  ) as $address) {

					if ($address->getStockId(  )) {
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
     * Get shipping method
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function _getShippingMethod($stockId) {
			if (is_null( $this->_shippingMethods )) {
				$shippingMethods = array(  );
				foreach ($this->getAddresses(  ) as $address) {

					if ($address->getStockId(  )) {
						$shippingMethods[$address->getStockId(  )] = $address->getShippingMethod(  );
						continue;
					}
				}

				$this->_shippingMethods = $shippingMethods;
			}


			if (isset( $this->_shippingMethods[$stockId] )) {
				return $this->_shippingMethods[$stockId];
			}

		}

		/**
     * Retrieve rate of active shipping method
     *
     * @return Mage_Sales_Model_Quote_Address_Rate || false
     */
		function _getActiveMethodRate($stockId) {
			$rates = $this->_getShippingRates( $stockId );

			if (is_array( $rates )) {
				foreach ($rates as $group) {
					foreach ($group as $code => $rate) {

						if ($rate->getCode(  ) == $this->_getShippingMethod( $stockId )) {
							return $rate;
						}
					}
				}
			}

			return false;
		}

		/**
     * Get shipping price
     * 
     * @param float $price
     * @param bool $flag
     * 
     * @return float
     */
		function _getShippingPrice($stockId, $price, $flag) {
			$this->getQuote(  );
			$address = $quote = $quote->getShippingAddressByStockId( $stockId );

			if ($address) {
				$store = $address->getQuote(  )->getStore(  );
				return $quote->getStore(  )->convertPrice( Mage::helper( 'tax' )->getShippingPrice( $price, $flag, $address, null, $store ), true );
			}

		}
	}

?>