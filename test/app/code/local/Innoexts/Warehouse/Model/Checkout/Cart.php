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

	class Innoexts_Warehouse_Model_Checkout_Cart extends Mage_Checkout_Model_Cart {
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
     * Get catalog inventory helper
     * 
     * @return Innoexts_Warehouse_Helper_Cataloginventory
     */
		function getCatalogInventoryHelper() {
			return $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  );
		}

		/**
     * Returns suggested quantities for items.
     * Can be used to automatically fix user entered quantities before updating cart
     * so that cart contains valid qty values
     *
     * $data is an array of ($quoteItemId => (item info array with 'qty' key), ...)
     *
     * @param   array $data
     * 
     * @return  array
     */
		function suggestItemsQty($data) {
			foreach ($data as $itemId => $itemInfo) {

				if (!isset( $itemInfo['qty'] )) {
					continue;
				}

				$qty = (double)$itemInfo['qty'];

				if ($qty <= 0) {
					continue;
				}

				$quoteItem = ;

				if (!$quoteItem) {
					continue;
				}

				$stockItem = $this->getQuote(  )->getItemById( $itemId );

				if (!$stockItem) {
					continue;
				}

				$quoteItem->getProduct(  );
				$product = $quoteItem->getStockItem(  );

				if (!$product) {
					continue;
				}

				$data[$itemId]['before_suggest_qty'] = $qty;
				$data[$itemId]['qty'] = $stockItem->suggestQty( $qty );
			}

			return $data;
		}

		/**
     * Update item in shopping cart (quote)
     * $requestInfo - either qty (int) or buyRequest in form of array or Varien_Object
     * $updatingParams - information on how to perform update, passed to Quote->updateItem() method
     *
     * @param int $id
     * @param int|array|Varien_Object $requestInfo
     * @param null|array|Varien_Object $updatingParams
     * @return Mage_Sales_Model_Quote_Item|string
     *
     * @see Mage_Sales_Model_Quote::updateItem()
     */
		function updateItem($itemId, $requestInfo = null, $updatingParams = null) {
			$item = $this->getQuote(  )->getItemById( $itemId );

			if (!$item) {
				Mage::throwException( Mage::helper( 'checkout' )->__( 'Quote item does not exist.' ) );
			}

			$productId = $item->getProduct(  )->getId(  );
			$product = $this->_getProduct( $productId );
			$request = $this->_getProductRequest( $requestInfo );
			$stockItem = $item->getStockItem(  );

			if ($stockItem) {
				$minimumQty = $stockItem->getMinSaleQty(  );

				if (( ( ( $minimumQty && 0 < $minimumQty ) && $request->getQty(  ) < $minimumQty ) && !$this->getQuote(  )->hasProductId( $productId ) )) {
					$request->setQty( $minimumQty );
				}
			}

			$result = $this->getQuote(  )->updateItem( $itemId, $request, $updatingParams );
			jmp;
			Mage_Core_Exception {
				$this->getCheckoutSession(  )->setUseNotice( false );
				$result = $e->getMessage(  );

				if (is_string( $result )) {
					if ($this->getCheckoutSession(  )->getUseNotice(  ) === null) {
						$this->getCheckoutSession(  )->setUseNotice( true );
					}

					Mage::throwException( $result );
				}

				Mage::dispatchEvent( 'checkout_cart_product_update_after', array( 'quote_item' => $result, 'product' => $product ) );
				$this->getCheckoutSession(  )->setLastAddedProductId( $productId );
				return $result;
			}
		}

		/**
     * Update cart items information
     *
     * @param   array $data
     * 
     * @return  Innoexts_Warehouse_Model_Checkout_Cart
     */
		function updateItems($data) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$productHelper = $helper->getProductHelper(  );
			Mage::dispatchEvent( 'checkout_cart_update_items_before', array( 'cart' => $this, 'info' => $data ) );
			$messageFactory = Mage::getSingleton( 'core/message' );
			$session = $this->getCheckoutSession(  );
			$qtyRecalculatedFlag = false;
			foreach ($data as $itemId => $itemInfo) {
				$item = $this->getQuote(  )->getItemById( $itemId );

				if (!$item) {
					continue;
				}


				if (( !empty( $itemInfo['remove'] ) || ( isset( $itemInfo['qty'] ) && $itemInfo['qty'] == '0' ) )) {
					$this->removeItem( $itemId );
					continue;
				}

				$qty = (isset( $itemInfo['qty'] ) ? (double)$itemInfo['qty'] : false);

				if (0 < $qty) {
					if (( $config->isAllowAdjustment(  ) && isset( $itemInfo['stock_id'] ) )) {
						$product = $item->getProduct(  );
						$stockId = (int)$itemInfo['stock_id'];

						if ($helper->isStockIdExists( $stockId )) {
							$productHelper->setSessionStockId( $product, $stockId );
						}
					}

					$item->setQty( $qty );

					if ($this->getVersionHelper(  )->isGe1700(  )) {
						$itemInQuote = $this->getQuote(  )->getItemById( $item->getId(  ) );

						if (( !$itemInQuote && $item->getHasError(  ) )) {
							Mage::throwException( $item->getMessage(  ) );
						}
					} 
else {
						if ($item->getHasError(  )) {
							Mage::throwException( $item->getMessage(  ) );
						}
					}


					if (( isset( $itemInfo['before_suggest_qty'] ) && $itemInfo['before_suggest_qty'] != $qty )) {
						$qtyRecalculatedFlag = true;
						$message = $messageFactory->notice( Mage::helper( 'checkout' )->__( 'Quantity was recalculated from %d to %d', $itemInfo['before_suggest_qty'], $qty ) );
						$session->addQuoteItemMessage( $item->getId(  ), $message );
						continue;
					}

					continue;
				}
			}


			if ($qtyRecalculatedFlag) {
				$session->addNotice( Mage::helper( 'checkout' )->__( 'Some products quantities were recalculated because of quantity increment mismatch' ) );
			}

			Mage::dispatchEvent( 'checkout_cart_update_items_after', array( 'cart' => $this, 'info' => $data ) );
			return $this;
		}

		/**
     * Add product to shopping cart (quote)
     *
     * @param   int|Mage_Catalog_Model_Product $productInfo
     * @param   mixed $requestInfo
     * 
     * @return  Mage_Checkout_Model_Cart
     */
		function addProduct($productInfo, $requestInfo = null) {
			$product = $this->_getProduct( $productInfo );
			$request = $this->_getProductRequest( $requestInfo );
			$productId = $product->getId(  );

			if ($productId) {
				$result = $this->getQuote(  )->addProduct( $product, $request );
				jmp;
				Mage_Core_Exception {
					$this->getCheckoutSession(  )->setUseNotice( false );
					$result = $e->getMessage(  );

					if (is_string( $result )) {
						$redirectUrl = ($product->hasOptionsValidationFail(  ) ? $product->getUrlModel(  )->getUrl( $product, array( '_query' => array( 'startcustomization' => 1 ) ) ) : $product->getProductUrl(  ));
						$this->getCheckoutSession(  )->setRedirectUrl( $redirectUrl );

						if ($this->getCheckoutSession(  )->getUseNotice(  ) === null) {
							$this->getCheckoutSession(  )->setUseNotice( true );
						}

						Mage::throwException( $result );
					}
				}
			}

			Mage::throwException( Mage::helper( 'checkout' )->__( 'The product does not exist.' ) );
			Mage::dispatchEvent( 'checkout_cart_product_add_after', array( 'quote_item' => $result, 'product' => $product ) );
			$this->getCheckoutSession(  )->setLastAddedProductId( $productId );
			return $this;
		}

		/**
     * Reset stocks
     *
     * @param array $data
     * 
     * @return  Innoexts_Warehouse_Model_Checkout_Cart
     */
		function resetStocks($data) {
			$helper = $this->getWarehouseHelper(  );
			$productHelper = $helper->getProductHelper(  );
			$quote = $this->getQuote(  );
			foreach ($data as $itemId => $itemInfo) {
				$item = $quote->getItemById( $itemId );

				if (!$item) {
					continue;
				}

				$product = $item->getProduct(  );
				$productHelper->setSessionStockId( $product, null );
			}

			$quote->reapplyStocks(  );
			return $this;
		}
	}

?>