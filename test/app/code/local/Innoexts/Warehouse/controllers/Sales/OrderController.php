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

	class Innoexts_Warehouse_Sales_OrderController extends Mage_Core_Controller_Front_Action {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Customer order pending payment
     */
		function pendingpaymentAction() {
			$customerSession = Mage::getSingleton( 'customer/session' );

			if (!$customerSession->isLoggedIn(  )) {
				$this->_forward( 'noRoute' );
				return false;
			}

			$helper = $this->getWarehouseHelper(  );
			$this->loadLayout(  );
			$this->_initLayoutMessages( 'catalog/session' );
			$this->getLayout(  )->getBlock( 'head' )->setTitle( $helper->__( 'My Pending Payments' ) );

			if ($block = $this->getLayout(  )->getBlock( 'customer.account.link.back' )) {
				$block->setRefererUrl( $this->_getRefererUrl(  ) );
			}

			$this->renderLayout(  );
		}

		/**
     * Make payment action
     */
		function makepaymentAction() {
			$helper = $this->getWarehouseHelper(  );
			$orderHelper = $helper->getOrderHelper(  );
			$orderId = (int)$this->getRequest(  )->getParam( 'order_id' );

			if (!$orderId) {
				$this->_forward( 'noRoute' );
				return false;
			}

			$order = Mage::getModel( 'sales/order' )->load( $orderId );

			if (!$order->getId(  )) {
				$this->_forward( 'noRoute' );
				return false;
			}

			$currentOrder = null;
			$customerSession = Mage::getSingleton( 'customer/session' );

			if ($customerSession->isLoggedIn(  )) {
				if ($order->getCustomerId(  ) == $customerSession->getCustomerId(  )) {
					$currentOrder = $customerSession;
				}
			} 
else {
				$orderIds = Mage::getSingleton( 'core/session' )->getOrderIds(  );

				if (( ( $orderIds && is_array( $orderIds ) ) && in_array( $orderId, $orderIds ) )) {
					$currentOrder = $customerSession;
				} 
else {
					Mage::helper( 'sales/guest' )->loadValidOrder(  );
					$currentOrder = Mage::registry( 'current_order' );
				}
			}


			if (( !$currentOrder || !$orderHelper->isPendingPayment( $currentOrder ) )) {
				$this->_forward( 'noRoute' );
				return false;
			}

			$paymentMethod = $currentOrder->getPayment(  )->getMethodInstance(  );

			if ($paymentMethod) {
				$redirectUrl = $paymentMethod->getOrderPlaceRedirectUrl(  );

				if ($redirectUrl) {
					$checkoutSession = Mage::getSingleton( 'checkout/session' );
					$checkoutSession->setLastOrderId( $order->getId(  ) )->setRedirectUrl( $redirectUrl )->setLastRealOrderId( $order->getIncrementId(  ) );
					$agreement = $order->getPayment(  )->getBillingAgreement(  );

					if ($agreement) {
						$checkoutSession->setLastBillingAgreementId( $agreement->getId(  ) );
					}

					$this->_redirectUrl( $redirectUrl );
					return null;
				}

				$this->_forward( 'noRoute' );
				return false;
			}

			$this->_forward( 'noRoute' );
			return false;
		}
	}

?>