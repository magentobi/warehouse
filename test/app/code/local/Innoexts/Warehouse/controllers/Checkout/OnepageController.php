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

	require_once( 'Mage/Checkout/controllers/OnepageController.php' );
	class Innoexts_Warehouse_Checkout_OnepageController extends Mage_Checkout_OnepageController {
		private $_orders = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get version helper
     * 
     * @return Innoexts_Core_Helper_Version
     */
		function getVersionHelper() {
			return $this->getWarehouseHelper(  )->getVersionHelper(  );
		}

		/**
     * Get orders by quoteId
     *
     * @return array of Innoexts_Warehouse_Model_Sales_Order
     */
		function _getOrders() {
			if (is_null( $this->_orders )) {
				$quoteId = $this->getOnepage(  )->getQuote(  )->getId(  );
				$collection = Mage::getModel( 'sales/order' )->getCollection(  );
				$collection->getSelect(  )->where( 'quote_id = ?', $quoteId );
				$orders = array(  );
				foreach ($collection as $order) {
					$orders[$order->getId(  )] = $order;
				}


				if (!count( $orders )) {
					throw new Mage_Payment_Model_Info_Exception( 'core' )( 'Can not create invoice. Order was not found.' )(  );
				}

				$this->_orders = $orders;
			}

			return $this->_orders;
		}

		/**
     * Create invoice
     * 
     * @param Innoexts_Warehouse_Model_Sales_Order $order
     * 
     * @return Mage_Sales_Model_Order_Invoice
     */
		function __initInvoice($order) {
			$items = array(  );
			foreach ($order->getAllItems(  ) as $item) {
				$items[$item->getId(  )] = $item->getQtyOrdered(  );
			}

			$invoice = Mage::getModel( 'sales/service_order', $order )->prepareInvoice( $items );
			$invoice->setEmailSent( true )->register(  );
			return $invoice;
		}

		/**
     * Create order action
     */
		function saveOrderAction() {
			if ($this->_expireAjax(  )) {
				return null;
			}

			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$result = array(  );

			if ($requiredAgreements = Mage::helper( 'checkout' )->getRequiredAgreementIds(  )) {
				$postedAgreements = array_keys( $this->getRequest(  )->getPost( 'agreement', array(  ) ) );

				if ($diff = array_diff( $requiredAgreements, $postedAgreements )) {
					$result['success'] = false;
					$result['error'] = true;
					$result['error_messages'] = $this->__( 'Please agree to all the terms and conditions before placing the order.' );
					$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( $result ) );
					return null;
				}
			}


			if ($data = $this->getRequest(  )->getPost( 'payment', false )) {
				$this->getOnepage(  )->getQuote(  )->getPayment(  )->importData( $data );
			}


			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$this->getOnepage(  )->saveOrders(  );

				if (( $this->getVersionHelper(  )->isGe1510(  ) && !$this->getVersionHelper(  )->isGe1700(  ) )) {
					$storeId = Mage::app(  )->getStore(  )->getId(  );
					$paymentHelper = Mage::helper( 'payment' );
					$zeroSubTotalPaymentAction = $paymentHelper->getZeroSubTotalPaymentAutomaticInvoice( $storeId );
					foreach ($this->_getOrders(  ) as $orderId => $order) {

						if (( ( ( $paymentHelper->isZeroSubTotal( $storeId ) && $order->getGrandTotal(  ) == 0 ) && $zeroSubTotalPaymentAction == ACTION_AUTHORIZE_CAPTURE ) && $paymentHelper->getZeroSubTotalOrderStatus( $storeId ) == 'pending' )) {
							$invoice = $this->__initInvoice( $order );
							$invoice->getOrder(  )->setIsInProcess( true );

							if ($this->getVersionHelper(  )->isGe1610(  )) {
								$transactionSave = Mage::getModel( 'core/resource_transaction' )->addObject( $invoice )->addObject( $invoice->getOrder(  ) );
								$transactionSave->save(  );
								continue;
							}

							$invoice->save(  );
							continue;
						}
					}
				}
			} 
else {
				$this->getOnepage(  )->saveOrder(  );

				if (( $this->getVersionHelper(  )->isGe1510(  ) && !$this->getVersionHelper(  )->isGe1700(  ) )) {
					$storeId = Mage::app(  )->getStore(  )->getId(  );
					$paymentHelper = Mage::helper( 'payment' );
					$zeroSubTotalPaymentAction = $paymentHelper->getZeroSubTotalPaymentAutomaticInvoice( $storeId );

					if (( ( ( $paymentHelper->isZeroSubTotal( $storeId ) && $this->_getOrder(  )->getGrandTotal(  ) == 0 ) && $zeroSubTotalPaymentAction == ACTION_AUTHORIZE_CAPTURE ) && $paymentHelper->getZeroSubTotalOrderStatus( $storeId ) == 'pending' )) {
						$invoice = $this->_initInvoice(  );
						$invoice->getOrder(  )->setIsInProcess( true );

						if ($this->getVersionHelper(  )->isGe1610(  )) {
							$transactionSave = Mage::getModel( 'core/resource_transaction' )->addObject( $invoice )->addObject( $invoice->getOrder(  ) );
							$transactionSave->save(  );
						} 
else {
							$invoice->save(  );
						}
					}
				}
			}

			$redirectUrls = $this->getOnepage(  )->getCheckout(  )->getRedirectUrls(  );
			$redirectUrl = $this->getOnepage(  )->getCheckout(  )->getRedirectUrl(  );
			$result['success'] = true;
			$result['error'] = false;
			jmp;
			Mage_Payment_Model_Info_Exception {
				$message = $e->getMessage(  );

				if (!empty( $$message )) {
					$result['error_messages'] = $message;
				}

				$result['goto_section'] = 'payment';
				$result['update_section'] = array( 'name' => 'payment-method', 'html' => $this->_getPaymentMethodsHtml(  ) );
				jmp;
				Mage_Core_Exception {
					Mage::logException( $e );
					Mage::helper( 'checkout' )->sendPaymentFailedEmail( $this->getOnepage(  )->getQuote(  ), $e->getMessage(  ) );
					$result['success'] = false;
					$result['error'] = true;
					$result['error_messages'] = $e->getMessage(  );

					if ($gotoSection = $this->getOnepage(  )->getCheckout(  )->getGotoSection(  )) {
						$result['goto_section'] = $gotoSection;
						$this->getOnepage(  )->getCheckout(  )->setGotoSection( null );
					}


					if ($updateSection = $this->getOnepage(  )->getCheckout(  )->getUpdateSection(  )) {
						if (isset( $this->_sectionUpdateFunctions[$updateSection] )) {
							$updateSectionFunction = $this->_sectionUpdateFunctions[$updateSection];
							$result['update_section'] = array( 'name' => $updateSection, 'html' => $this->$updateSectionFunction(  ) );
						}

						$this->getOnepage(  )->getCheckout(  )->setUpdateSection( null );
					}

					$this->getOnepage(  )->getQuote(  )->save(  );

					if (( empty( $$redirectUrls ) && count( $redirectUrls ) == 1 )) {
						$result['redirect'] = current( $redirectUrls );
					}

					$this->getResponse(  )->setBody( Mage::helper( 'core' )->jsonEncode( $result ) );
					return null;
				}
			}
		}

		/**
     * Order success action
     */
		function successAction() {
			$helper = $this->getWarehouseHelper(  );

			if (( $helper->getConfig(  )->isMultipleMode(  ) && $helper->getConfig(  )->isSplitOrderEnabled(  ) )) {
				$session = $this->getOnepage(  )->getCheckout(  );

				if (!$session->getLastSuccessQuoteId(  )) {
					$this->_redirect( 'checkout/cart' );
					return null;
				}

				$session->getLastQuoteId(  );
				$session->getLastOrderId(  );
				$lastRecurringProfiles = $lastOrderId = $lastQuoteId = $session->getLastRecurringProfileIds(  );

				if (( !$lastQuoteId || ( !$lastOrderId && empty( $$lastRecurringProfiles ) ) )) {
					$this->_redirect( 'checkout/cart' );
					return null;
				}

				$orderIds = $session->getOrderIds(  );
				$session->clear(  );
				$this->loadLayout(  );
				$this->_initLayoutMessages( 'checkout/session' );
				Mage::dispatchEvent( 'checkout_onepage_controller_success_action', array( 'order_id' => current( $orderIds ), 'order_ids' => $orderIds ) );
				$this->renderLayout(  );
				return null;
			}

			parent::successAction(  );
		}
	}

?>