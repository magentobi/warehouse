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

	class Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create extends Mage_Adminhtml_Model_Sales_Order_Create {
		/**
     * Get warehouse helper
     * 
     * @return  Innoexts_Warehouse_Helper_Data
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
     * Initialize creation data from existing order
     * 
     * @param Mage_Sales_Model_Order $order
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function initFromOrder($order) {
			$helper = $this->getWarehouseHelper(  );

			if (!$order->getReordered(  )) {
				$this->getSession(  )->setOrderId( $order->getId(  ) );
			} 
else {
				$this->getSession(  )->setReordered( $order->getId(  ) );
			}

			$this->getSession(  )->setCurrencyId( $order->getOrderCurrencyCode(  ) );

			if ($order->getCustomerId(  )) {
				$this->getSession(  )->setCustomerId( $order->getCustomerId(  ) );
			} 
else {
				$this->getSession(  )->setCustomerId( false );
			}

			$this->getSession(  )->setStoreId( $order->getStoreId(  ) );

			if (!$helper->getConfig(  )->isMultipleMode(  )) {
				$this->getSession(  )->setStockId( $order->getStockId(  ) );
			}

			$this->initRuleData(  );
			$availableProductTypes = Mage::getConfig(  )->getNode( 'adminhtml/sales/order/create/available_product_types' )->asArray(  );
			foreach ($order->getItemsCollection( array_keys( $availableProductTypes ), true ) as $orderItem) {

				if (!$orderItem->getParentItem(  )) {
					if ($order->getReordered(  )) {
						$qty = $orderItem->getQtyOrdered(  );
					} 
else {
						$qty = $orderItem->getQtyOrdered(  ) - $orderItem->getQtyShipped(  ) - $orderItem->getQtyInvoiced(  );
					}


					if (0 < $qty) {
						$item = $this->initFromOrderItem( $orderItem, $qty );

						if (is_string( $item )) {
							Mage::throwException( $item );
							continue;
						}

						continue;
					}

					continue;
				}
			}

			$this->_initBillingAddressFromOrder( $order );
			$this->_initShippingAddressFromOrder( $order );

			if (( !$this->getQuote(  )->isVirtual(  ) && $this->getShippingAddress(  )->getSameAsBilling(  ) )) {
				$this->setShippingAsBilling( 1 );
			}

			$this->setShippingMethod( $order->getShippingMethod(  ) );
			$this->getQuote(  )->getShippingAddress(  )->setShippingDescription( $order->getShippingDescription(  ) );
			$this->getQuote(  )->getPayment(  )->addData( $order->getPayment(  )->getData(  ) );
			$orderCouponCode = $order->getCouponCode(  );

			if ($orderCouponCode) {
				$this->getQuote(  )->setCouponCode( $orderCouponCode );
			}


			if ($this->getQuote(  )->getCouponCode(  )) {
				$this->getQuote(  )->collectTotals(  );
			}

			Mage::helper( 'core' )->copyFieldset( 'sales_copy_order', 'to_edit', $order, $this->getQuote(  ) );
			Mage::dispatchEvent( 'sales_convert_order_to_quote', array( 'order' => $order, 'quote' => $this->getQuote(  ) ) );

			if (!$order->getCustomerId(  )) {
				$this->getQuote(  )->setCustomerIsGuest( true );
			}

			$this->getQuote(  )->applyStocks(  );

			if ($this->getSession(  )->getUseOldShippingMethod( true )) {
				$this->collectShippingRates(  );
			} 
else {
				$this->collectRates(  );
			}

			$this->getQuote(  )->save(  );
			return $this;
		}

		/**
     * Set shipping method
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function setShippingMethod($method) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( !$config->isMultipleMode(  ) || !$config->isSplitOrderEnabled(  ) )) {
				parent::setShippingMethod( $method );
				return $this;
			}

			foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
				$stockId = $address->getStockId(  );
				$shippingMethod = (is_array( $method ) ? (isset( $method[$stockId] ) ? $method[$stockId] : null) : $method);

				if ($shippingMethod) {
					$address->setShippingMethod( $shippingMethod );
					continue;
				}
			}

			$this->setRecollect( true );
			return $this;
		}

		/**
     * Reset shipping method
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function resetShippingMethod() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( !$config->isMultipleMode(  ) || !$config->isSplitOrderEnabled(  ) )) {
				parent::resetShippingMethod(  );
				return $this;
			}

			foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {
				$address->setShippingMethod( false );
				$address->removeAllShippingRates(  );
			}

			return $this;
		}

		/**
     * Set shipping address
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function setShippingAddress($address) {
			parent::setShippingAddress( $address );
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( !$config->isMultipleMode(  ) || !$config->isSplitOrderEnabled(  ) )) {
				return $this;
			}

			$shippingAddress = $this->getQuote(  )->getShippingAddress(  );
			foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $_address) {
				$this->getQuote(  )->copyAddress( $shippingAddress, $_address );
			}

			return $this;
		}

		/**
     * Set shipping as billing
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function setShippingAsBilling($flag) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			$tmpAddress = clone $this->getBillingAddress(  );
			$tmpAddress->getData(  );
			$data = $tmpAddress->unsAddressId(  )->unsAddressType(  )->unsStockId(  );
			$data['save_in_address_book'] = 0;

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {

					if ($flag) {
						$address->addData( $data );
					}

					$address->setSameAsBilling( $flag );
				}
			} 
else {
				if ($flag) {
					$this->getShippingAddress(  )->addData( $data );
				}

				$this->getShippingAddress(  )->setSameAsBilling( $flag );
			}

			$this->setRecollect( true );
			return $this;
		}

		/**
     * Update quantity of order quote items
     *
     * @param   array $data
     * 
     * @return  Mage_Adminhtml_Model_Sales_Order_Create
     */
		function updateQuoteItems($data) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$productHelper = $helper->getProductHelper(  );

			if (is_array( $data )) {
				foreach ($data as $itemId => $info) {

					if (!empty( $info['configured'] )) {
						$item = ( $itemId, new Varien_Object( $info ) );
						$itemQty = (double)$item->getQty(  );
					} 
else {
						$item = $this->getQuote(  )->getItemById( $itemId );
						$itemQty = (double)$info['qty'];
					}


					if ($item) {
						if (isset( $info['stock_id'] )) {
							$product = $item->getProduct(  );
							$stockId = (int)$info['stock_id'];

							if ($helper->isStockIdExists( $stockId )) {
								$productHelper->setSessionStockId( $product, $stockId );
							}
						}

						$stockItem = $item->getStockItem(  );

						if ($stockItem) {
							if (!$stockItem->getIsQtyDecimal(  )) {
								$itemQty = (int)$itemQty;
							} 
else {
								$item->setIsQtyDecimal( 1 );
							}
						}

						$itemQty = (0 < $itemQty ? $itemQty : 1);

						if (isset( $info['custom_price'] )) {
							$itemPrice = $this->_parseCustomPrice( $info['custom_price'] );
						} 
else {
							$itemPrice = null;
						}

						$noDiscount = !isset( $info['use_discount'] );

						if (( empty( $info['action'] ) || !empty( $info['configured'] ) )) {
							$item->setQty( $itemQty );
							$item->setCustomPrice( $itemPrice );
							$item->setOriginalCustomPrice( $itemPrice );
							$item->setNoDiscount( $noDiscount );
							$item->getProduct(  )->setIsSuperMode( true );
							$item->getProduct(  )->unsSkipCheckRequiredOption(  );
							$item->checkData(  );
							continue;
						}

						$this->moveQuoteItem( $item->getId(  ), $info['action'], $itemQty );
						continue;
					}
				}

				jmp;
				Mage_Core_Exception {
					$this->recollectCart(  );
					throw $e;
					jmp;
					Exception {
						Mage::logException( $e );
						$this->recollectCart(  );
					}
				}
			}

			return $this;
		}

		/**
     * Reset quote items
     *
     * @return  Mage_Adminhtml_Model_Sales_Order_Create
     */
		function resetQuoteItems() {
			$helper = $this->getWarehouseHelper(  );
			$productHelper = $helper->getProductHelper(  );
			$quote = $this->getQuote(  );
			foreach ($quote->getAllVisibleItems(  ) as $item) {
				$product = $item->getProduct(  );

				if (!$product) {
					continue;
				}

				$productHelper->setSessionStockId( $product, null );
			}

			$quote->reapplyStocks(  );
			return $this;
		}

		/**
     * Validate quote data before order creation
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function _validate() {
			while (true) {
				$helper = $this->getWarehouseHelper(  );
				$config = $helper->getConfig(  );
				$customerId = $this->getSession(  )->getCustomerId(  );

				if (is_null( $customerId )) {
					Mage::throwException( Mage::helper( 'adminhtml' )->__( 'Please select a customer.' ) );
				}


				if (!$this->getSession(  )->getStore(  )->getId(  )) {
					Mage::throwException( Mage::helper( 'adminhtml' )->__( 'Please select a store.' ) );
				}

				$items = $this->getQuote(  )->getAllItems(  );

				if (count( $items ) == 0) {
					$this->_errors[] = Mage::helper( 'adminhtml' )->__( 'You need to specify order items.' );
				}

				foreach ($items as $item) {
					$messages = $item->getMessage( false );

					if (( ( $item->getHasError(  ) && is_array( $messages ) ) && !empty( $$messages ) )) {
						$this->_errors = array_merge( $this->_errors, $messages );
						continue;
					}
				}


				if (!$this->getQuote(  )->isVirtual(  )) {
					if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
						foreach ($this->getQuote(  )->getAllShippingAddresses(  ) as $address) {

							if (!$address->getShippingMethod(  )) {
								$this->_errors[] = $helper->__( 'Shipping method must be specified for %s warehouse.', $address->getWarehouseTitle(  ) );
								continue;
							}
						}
					} 
else {
						if (!$this->getQuote(  )->getShippingAddress(  )->getShippingMethod(  )) {
							$this->_errors[] = Mage::helper( 'adminhtml' )->__( 'Shipping method must be specified.' );
						}
					}
				}


				if (!$this->getQuote(  )->getPayment(  )->getMethod(  )) {
					$this->_errors[] = Mage::helper( 'adminhtml' )->__( 'Payment method must be specified.' );
					break;
				}

				$method = $this->getQuote(  )->getPayment(  )->getMethodInstance(  );

				if (!$method) {
					$this->_errors[] = Mage::helper( 'adminhtml' )->__( 'Payment method instance is not available.' );
					break;
				}


				if (!$method->isAvailable( $this->getQuote(  ) )) {
					$this->_errors[] = Mage::helper( 'adminhtml' )->__( 'Payment method is not available.' );
					break;
				}

				$method->validate(  );
				break;
			}


			if (!empty( $this->_errors )) {
				foreach ($this->_errors as $error) {
					$this->getSession(  )->addError( $error );
				}

				Mage::throwException( '' );
			}

			return $this;
		}

		/**
     * Create new order
     * 
     * @return array of Innoexts_Warehouse_Model_Sales_Order
     */
		function createOrder() {
			$this->_prepareCustomer(  );
			$this->_validate(  );
			$quote = $this->getQuote(  );
			$this->_prepareQuoteItems(  );
			$service = Mage::getModel( 'sales/service_quote', $quote );

			if ($this->getSession(  )->getOrder(  )->getId(  )) {
				$oldOrder = $this->getSession(  )->getOrder(  );
				$originalId = $oldOrder->getOriginalIncrementId(  );

				if (!$originalId) {
					$originalId = $oldOrder->getIncrementId(  );
				}

				$orderData = array( 'original_increment_id' => $originalId, 'relation_parent_id' => $oldOrder->getId(  ), 'relation_parent_real_id' => $oldOrder->getIncrementId(  ), 'edit_increment' => $oldOrder->getEditIncrement(  ) + 1, 'increment_id' => $originalId . '-' . ( $oldOrder->getEditIncrement(  ) + 1 ) );
				$quote->setReservedOrderId( $orderData['increment_id'] );
				$service->setOrderData( $orderData );
			}

			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$orders = $service->submitOrders(  );
			} 
else {
				$orders = array( 0 => $service->submit(  ) );
			}

			foreach ($orders as $_order) {
				$order = $orderData;
				break;
			}


			if (( ( !$quote->getCustomer(  )->getId(  ) || !$quote->getCustomer(  )->isInStore( $this->getSession(  )->getStore(  ) ) ) && !$quote->getCustomerIsGuest(  ) )) {
				$quote->getCustomer(  )->setCreatedAt( $order->getCreatedAt(  ) );
				$quote->getCustomer(  )->save(  )->sendNewAccountEmail( 'registered', '', $quote->getStoreId(  ) );
			}


			if ($this->getSession(  )->getOrder(  )->getId(  )) {
				$oldOrder = $this->getSession(  )->getOrder(  );
				$this->getSession(  )->getOrder(  )->setRelationChildId( $order->getId(  ) );
				$this->getSession(  )->getOrder(  )->setRelationChildRealId( $order->getIncrementId(  ) );
				$this->getSession(  )->getOrder(  )->cancel(  )->save(  );
				$order->save(  );
			}

			foreach ($orders as $_order) {

				if ($this->getSendConfirmation(  )) {
					$_order->sendNewOrderEmail(  );
				}

				Mage::dispatchEvent( 'checkout_submit_all_after', array( 'order' => $_order, 'quote' => $quote ) );
			}

			return $orders;
		}

		/**
     * Get stock identifier
     * 
     * @return int
     */
		function getStockId() {
			$this->getWarehouseHelper(  );
			$helper = $stockId = null;

			if (!$helper->getConfig(  )->isMultipleMode(  )) {
				if (intval( $this->getSession(  )->getStockId(  ) )) {
					$stockId = intval( $this->getSession(  )->getStockId(  ) );
				} 
else {
					$stockId = $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId( $this->getQuote(  ) );
				}
			}

			return $stockId;
		}

		/**
     * Quote saving
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Sales_Order_Create
     */
		function saveQuote() {
			if (!$this->getQuote(  )->getId(  )) {
				return $this;
			}


			if ($this->_needCollect) {
				$this->getQuote(  )->reapplyStocks(  );
				$this->getQuote(  )->save(  );
				$this->getQuote(  )->collectTotals(  );
			}

			$this->getQuote(  )->save(  );
			return $this;
		}

		/**
     * Add product to current order quote
     * $product can be either product id or product model
     * $config can be either buyRequest config, or just qty
     *
     * @param   int|Mage_Catalog_Model_Product $product
     * @param   float|array|Varien_Object $config
     * @return  Mage_Adminhtml_Model_Sales_Order_Create
     */
		function addProduct($product, $config = 1) {
			$helper = $this->getWarehouseHelper(  );

			if (( !is_array( $config ) && !( $config instanceof Varien_Object ) )) {
				$config = array( 'qty' => $config );
			}

			$config = new Varien_Object( $config );

			if (!( $product instanceof Mage_Catalog_Model_Product )) {
				$productId = $config;
				$product = Mage::getModel( 'catalog/product' )->setStore( $this->getSession(  )->getStore(  ) )->setStoreId( $this->getSession(  )->getStoreId(  ) )->load( $product );

				if (!$product->getId(  )) {
					Mage::throwException( Mage::helper( 'adminhtml' )->__( 'Failed to add a product to cart by id "%s".', $productId ) );
				}
			}

			$stockId = null;

			if ($helper->getConfig(  )->isMultipleMode(  )) {
				if (( isset( $config['stock_id'] ) && intval( $config['stock_id'] ) )) {
					$stockId = intval( $config['stock_id'] );
				}
			} 
else {
				$stockId = $this->getStockId(  );
			}


			if (!is_null( $stockId )) {
				$stockItem = $helper->getCatalogInventoryHelper(  )->getStockItemCached( $product->getId(  ), $stockId );
				$stockItem->assignProduct( $product );
			}

			$stockItem = $product->getStockItem(  );

			if (( $stockItem && $stockItem->getIsQtyDecimal(  ) )) {
				$product->setIsQtyDecimal( 1 );
			} 
else {
				if ($this->getVersionHelper(  )->isGe1700(  )) {
					$config->setQty( (int)$config->getQty(  ) );
				}
			}

			$product->setCartQty( $config->getQty(  ) );
			$item = $this->getQuote(  )->addProductAdvanced( $product, $config, PROCESS_MODE_FULL );

			if (is_string( $item )) {
				if ($product->getTypeId(  ) != TYPE_CODE) {
					$item = $this->getQuote(  )->addProductAdvanced( $product, $config, PROCESS_MODE_LITE );
				}


				if (is_string( $item )) {
					Mage::throwException( $item );
				}
			}

			$item->checkData(  );
			$this->setRecollect( true );
			return $this;
		}
	}

?>