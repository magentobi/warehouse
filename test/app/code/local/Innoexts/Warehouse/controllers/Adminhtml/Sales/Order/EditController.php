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

	require_once( 'Mage/Adminhtml/controllers/Sales/Order/EditController.php' );
	class Innoexts_Warehouse_Adminhtml_Sales_Order_EditController extends Mage_Adminhtml_Sales_Order_EditController {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Initialize order creation session data
     * 
     * @return Mage_Adminhtml_Sales_Order_CreateController
     */
		function _initSession() {
			$helper = $this->getWarehouseHelper(  );
			parent::_initSession(  );

			if (!$helper->getConfig(  )->isMultipleMode(  )) {
				$stockId = (int)$this->getRequest(  )->getParam( 'stock_id' );

				if (( $stockId && $helper->isStockIdExists( $stockId ) )) {
					$helper->setSessionStockId( $stockId );
					$this->_getOrderCreateModel(  )->setRecollect( true );
				}
			}

			return $this;
		}

		/**
     * Saving quote and create order
     */
		function saveAction() {
			$helper = $this->getWarehouseHelper(  );

			if ($helper->getVersionHelper(  )->isGe1510(  )) {
				$this->_processActionData( 'save' );
			} 
else {
				$this->_processData( 'save' );
			}


			if ($paymentData = $this->getRequest(  )->getPost( 'payment' )) {
				$this->_getOrderCreateModel(  )->setPaymentData( $paymentData );
				$this->_getOrderCreateModel(  )->getQuote(  )->getPayment(  )->addData( $paymentData );
			}

			$orderCreate = $this->_getOrderCreateModel(  )->setIsValidate( true )->importPostData( $this->getRequest(  )->getPost( 'order' ) );
			$orderCreate->getQuote(  )->reapplyStocks(  );
			$orderCreate->getQuote(  )->collectTotals(  );
			$orders = $orderCreate->createOrder(  );
			$this->_getSession(  )->clear(  );

			if (1 < count( $orders )) {
				Mage::getSingleton( 'adminhtml/session' )->addSuccess( $helper->__( 'The orders has been created.' ) );
				$this->_redirect( '*/sales_order' );
				return null;
			}

			$order = array_shift( $orders );
			Mage::getSingleton( 'adminhtml/session' )->addSuccess( $this->__( 'The order has been created.' ) );
			$this->_redirect( '*/sales_order/view', array( 'order_id' => $order->getId(  ) ) );
		}
	}

?>