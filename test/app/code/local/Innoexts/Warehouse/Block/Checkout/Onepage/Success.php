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

	class Innoexts_Warehouse_Block_Checkout_Onepage_Success extends Mage_Checkout_Block_Onepage_Success {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get visible states
     * 
     * @return array
     */
		function getVisibleStates() {
			$orderConfig = Mage::getSingleton( 'sales/order_config' );
			return array_merge( $orderConfig->getVisibleOnFrontStates(  ), array( $this->getWarehouseHelper(  )->getOrderHelper(  )->getPendingPaymentState(  ) ) );
		}

		/**
     * Get order print url
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return string
     */
		function getOrderPrintUrl($order) {
			return $this->getUrl( 'sales/order/print', array( 'order_id' => $order->getId(  ) ) );
		}

		/**
     * Get order view url
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return string
     */
		function getOrderViewUrl($order) {
			return $this->getUrl( 'sales/order/view', array( 'order_id' => $order->getId(  ) ) );
		}

		/**
     * Get order make payment URL
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return string
     */
		function getOrderMakePaymentUrl($order) {
			return $this->getWarehouseHelper(  )->getOrderHelper(  )->getMakePaymentUrl( $order );
		}

		/**
     * Check if order is visible
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return bool
     */
		function _isOrderVisible($order) {
			return !in_array( $order->getState(  ), Mage::getSingleton( 'sales/order_config' )->getInvisibleOnFrontStates(  ) );
		}

		/**
     * Check if order print is visible
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return bool
     */
		function isOrderPrintVisible($order) {
			return $this->_isOrderVisible( $order );
		}

		/**
     * Check if order view is visible
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return bool
     */
		function isOrderViewVisible($order) {
			return ( $this->_isOrderVisible( $order ) && Mage::getSingleton( 'customer/session' )->isLoggedIn(  ) );
		}

		/**
     * Check if order make payment is visible
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return boolean
     */
		function isOrderMakePaymentVisible($order) {
			return $this->getWarehouseHelper(  )->getOrderHelper(  )->isPendingPayment( $order );
		}

		/**
     * Before to html
     * 
     * @return Innoexts_Warehouse_Block_Checkout_Onepage_Success
     */
		function _beforeToHtml() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			parent::_beforeToHtml(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$checkoutSession = Mage::getSingleton( 'checkout/session' );
				$orderIds = $checkoutSession->getOrderIds(  );

				if (!$orderIds) {
					$orderIds = array(  );
				}


				if (count( $orderIds ) < 2) {
					return $this;
				}

				$collection = Mage::getResourceModel( 'sales/order_collection' );
				$adapter = $collection->getConnection(  );
				$collection->addAttributeToSelect( '*' )->addAttributeToFilter( 'state', array( 'in' => $this->getVisibleStates(  ) ) )->addAttributeToSort( 'created_at', 'asc' );
				$collection->getSelect(  )->where( $adapter->quoteInto( 'entity_id IN (?)', $orderIds ) );
				$collection->load(  );
				$this->setOrders( $collection );
			}

			return $this;
		}
	}

?>