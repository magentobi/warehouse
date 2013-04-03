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

	class Innoexts_Warehouse_Block_Sales_Order_Pendingpayment extends Mage_Core_Block_Template {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Constructor
     */
		function __construct() {
			$helper = $this->getWarehouseHelper(  );
			$orderHelper = $helper->getOrderHelper(  );
			parent::__construct(  );
			$this->setTemplate( 'warehouse/sales/order/pendingpayment.phtml' );
			$customer = Mage::getSingleton( 'customer/session' )->getCustomer(  );
			$orders = Mage::getResourceModel( 'sales/order_collection' )->addFieldToSelect( '*' )->addFieldToFilter( 'customer_id', $customer->getId(  ) )->addFieldToFilter( 'state', $orderHelper->getPendingPaymentState(  ) )->setOrder( 'created_at', 'desc' )->setFlag( 'appendStockIds' );
			$this->setOrders( $orders );
			$layout = Mage::app(  )->getFrontController(  )->getAction(  )->getLayout(  );
			$rootBlock = $layout->getBlock( 'root' );

			if ($rootBlock) {
				$rootBlock->setHeaderTitle( $helper->__( 'My Pending Payments' ) );
			}

		}

		/**
     * Prepare layout
     * 
     * @return Innoexts_Warehouse_Block_Sales_Order_Pendingpayment
     */
		function _prepareLayout() {
			parent::_prepareLayout(  );
			$pager = $this->getLayout(  )->createBlock( 'page/html_pager', 'warehouse.sales.order.pendingpayment.pager' )->setCollection( $this->getOrders(  ) );
			$this->setChild( 'pager', $pager );
			$this->getOrders(  )->load(  );
			return $this;
		}

		/**
     * Get pager HTML
     * 
     * @return string
     */
		function getPagerHtml() {
			return $this->getChildHtml( 'pager' );
		}

		/**
     * Get make payment URL
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return string
     */
		function getMakePaymentUrl($order) {
			return $this->getWarehouseHelper(  )->getOrderHelper(  )->getMakePaymentUrl( $order );
		}

		/**
     * Get back URL
     * 
     * @return string
     */
		function getBackUrl() {
			return $this->getUrl( 'customer/account/' );
		}
	}

?>