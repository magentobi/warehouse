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

	class Innoexts_Warehouse_Helper_Sales_Order extends Mage_Core_Helper_Abstract {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get pending payment state
     * 
     * @return string
     */
		function getPendingPaymentState() {
			return 'pending_payment';
		}

		/**
     * Check if order has pending payment state
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return boolean
     */
		function isPendingPayment($order) {
			return ($order->getState(  ) == $this->getPendingPaymentState(  ) ? true : false);
		}

		/**
     * Get order make payment URL
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return string
     */
		function getMakePaymentUrl($order) {
			return Mage::getModel( 'core/url' )->getUrl( 'warehouse/sales_order/makepayment', array( 'order_id' => $order->getId(  ) ) );
		}
	}

?>