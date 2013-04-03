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

	class Innoexts_Warehouse_Model_Sales_Service_Quote extends Mage_Sales_Model_Service_Quote {
		private $_orders = array(  );

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get convertor
     * 
     * @return Mage_Sales_Model_Convert_Quote
     */
		function getConvertor() {
			return $this->_convertor;
		}

		/**
     * Set order
     * 
     * @param int $stockId
     * @param Innoexts_Warehouse_Model_Sales_Order $order
     * 
     * @return Innoexts_Warehouse_Model_Sales_Service_Quote
     */
		function setOrder($stockId, $order) {
			$this->_orders[$stockId] = $order;
			return $this;
		}

		/**
     * Clear orders
     * 
     * @return Innoexts_Warehouse_Model_Sales_Service_Quote
     */
		function clearOrders() {
			$this->_orders = array(  );
			return $this;
		}

		/**
     * Get orders
     * 
     * @return array of Innoexts_Warehouse_Model_Sales_Order
     */
		function getOrders() {
			return $this->_orders;
		}

		/**
     * Validate quote data before converting to order
     *
     * @return Innoexts_Warehouse_Model_Sales_Service_Quote
     */
		function _validateMultiple() {
			$helper = Mage::helper( 'sales' );
			$quote = $this->getQuote(  );

			if (!$quote->isVirtual(  )) {
				$address = $quote->getShippingAddress(  );
				$addressValidation = $address->validate(  );

				if ($addressValidation !== true) {
					Mage::throwException( $helper->__( 'Please check shipping address information. %s', implode( ' ', $addressValidation ) ) );
				}

				foreach ($quote->getAllShippingAddresses(  ) as $address) {

					if ($address->isVirtual(  )) {
						continue;
					}

					$method = $address->getShippingMethod(  );
					$rate = $address->getShippingRateByCode( $method );

					if (( !$quote->isVirtual(  ) && ( !$method || !$rate ) )) {
						Mage::throwException( $helper->__( 'Please specify a shipping method for %s warehouse.', $address->getWarehouseTitle(  ) ) );
						continue;
					}
				}
			}

			$addressValidation = $quote->getBillingAddress(  )->validate(  );

			if ($addressValidation !== true) {
				Mage::throwException( $helper->__( 'Please check billing address information. %s', implode( ' ', $addressValidation ) ) );
			}


			if (!$quote->getPayment(  )->getMethod(  )) {
				Mage::throwException( $helper->__( 'Please select a valid payment method.' ) );
			}

			return $this;
		}

		/**
     * Submit nominal items
     *
     * @return Innoexts_Warehouse_Model_Sales_Service_Quote
     */
		function submitNominalItemsMultiple() {
			$this->_validateMultiple(  );
			$this->_submitRecurringPaymentProfiles(  );
			$this->_inactivateQuote(  );
			$this->_deleteNominalItems(  );
			return $this;
		}

		/**
     * Submit all available items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Service_Quote
     */
		function submitAllMultiple() {
			$shouldInactivateQuoteOld = $this->_shouldInactivateQuote;
			$this->_shouldInactivateQuote = false;
			$this->submitNominalItemsMultiple(  );
			$this->_shouldInactivateQuote = $shouldInactivateQuoteOld;
			jmp;
			Exception {
				$this->_shouldInactivateQuote = $shouldInactivateQuoteOld;
				throw $e;

				if (!$this->getQuote(  )->getAllVisibleItems(  )) {
					$this->_inactivateQuote(  );
					return null;
				}

				$this->submitOrders(  );
				return $this;
			}
		}

		/**
     * Submit the quote. Quote submit process will create the orders based on quote data
     * 
     * @return array of Innoexts_Warehouse_Model_Sales_Order
     */
		function submitOrders() {
			$this->_deleteNominalItems(  );
			$this->_validateMultiple(  );
			$this->clearOrders(  );
			$quote = $this->getQuote(  );
			$billingAddress = $quote->getBillingAddress(  );
			$convertor = $this->getConvertor(  );
			$isVirtual = $quote->isVirtual(  );
			$transaction = Mage::getModel( 'core/resource_transaction' );

			if ($quote->getCustomerId(  )) {
				$transaction->addObject( $quote->getCustomer(  ) );
			}

			$transaction->addObject( $quote );
			$addresses = array(  );

			if (!$isVirtual) {
				foreach ($quote->getAllShippingAddresses(  ) as $address) {
					array_push( $addresses, $address );
				}
			} 
else {
				array_push( $addresses, $quote->getBillingAddress(  ) );
			}

			$addresses = array_reverse( $addresses );
			foreach ($addresses as $address) {
				$stockId = intval( $address->getStockId(  ) );
				$quote->unsReservedOrderId(  );
				$quote->reserveOrderId(  );
				$quote->collectTotals(  );
				$order = $convertor->addressToOrder( $address );
				$orderBillingAddress = $convertor->addressToOrderAddress( $quote->getBillingAddress(  ) );

				if ($billingAddress->getCustomerAddress(  )) {
					$orderBillingAddress->setCustomerAddress( $billingAddress->getCustomerAddress(  ) );
				}

				$order->setBillingAddress( $orderBillingAddress );

				if (!$isVirtual) {
					if (!$address->isVirtual(  )) {
						$orderShippingAddress = $convertor->addressToOrderAddress( $address );

						if ($address->getCustomerAddress(  )) {
							$orderShippingAddress->setCustomerAddress( $address->getCustomerAddress(  ) );
						}

						$order->setShippingAddress( $orderShippingAddress );
					} 
else {
						$order->setIsVirtual( 1 );
					}
				} 
else {
					$order->setIsVirtual( 1 );
				}

				$order->setPayment( $convertor->paymentToOrderPayment( $quote->getPayment(  ) ) );

				if (Mage::app(  )->getStore(  )->roundPrice( $address->getGrandTotal(  ) ) == 0) {
					$order->getPayment(  )->setMethod( 'free' );
				}

				foreach ($this->_orderData as $key => $value) {
					$order->setData( $key, $value );
				}

				foreach ($quote->getAllItems(  ) as $item) {

					if (( $isVirtual || $item->getStockId(  ) == $stockId )) {
						$orderItem = $convertor->itemToOrderItem( $item );

						if ($item->getParentItem(  )) {
							$orderItem->setParentItem( $order->getItemByQuoteItemId( $item->getParentItem(  )->getId(  ) ) );
						}

						$order->addItem( $orderItem );
						continue;
					}
				}

				$order->setQuote( $quote );
				$this->setOrder( $stockId, $order );
				$transaction->addObject( $order );
				$transaction->addCommitCallback( array( $order, 'place' ) );
				$transaction->addCommitCallback( array( $order, 'save' ) );
				Mage::dispatchEvent( 'checkout_type_onepage_save_order', array( 'order' => $order, 'quote' => $quote ) );
				Mage::dispatchEvent( 'sales_model_service_quote_submit_before', array( 'order' => $order, 'quote' => $quote ) );
			}

			$transaction->save(  );
			$this->_inactivateQuote(  );
			foreach ($this->getOrders(  ) as $order) {
				Mage::dispatchEvent( 'sales_model_service_quote_submit_success', array( 'order' => $order, 'quote' => $quote ) );
			}

			jmp;
			Exception {
				if (!Mage::getSingleton( 'customer/session' )->isLoggedIn(  )) {
					$quote->getCustomer(  )->setId( null );
				}

				foreach ($this->getOrders(  ) as $order) {
					$order->setId( null );
					foreach ($order->getItemsCollection(  ) as $item) {
						$item->setOrderId( null );
						$item->setItemId( null );
					}

					Mage::dispatchEvent( 'sales_model_service_quote_submit_failure', array( 'order' => $order, 'quote' => $quote ) );
				}

				throw $e;
				foreach ($this->getOrders(  ) as $order) {
					Mage::dispatchEvent( 'sales_model_service_quote_submit_after', array( 'order' => $order, 'quote' => $quote ) );
				}

				return $this->getOrders(  );
			}
		}
	}

?>