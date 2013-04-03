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

	require_once( 'Mage/Checkout/controllers/CartController.php' );
	class Innoexts_Warehouse_Checkout_CartController extends Mage_Checkout_CartController {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Set current address action
     */
		function setCurrentAddressAction() {
			$helper = $this->getWarehouseHelper(  );
			$request = $this->getRequest(  );

			if ($request->isPost(  )) {
				$session = Mage::getSingleton( 'core/session' );
				$shippingAddress = new Varien_Object( 'country_id' )(  )( array( 'country_id' => , 'region_id' => trim( $request->getPost( 'region_id' ) ), 'region' => trim( $request->getPost( 'region' ) ), 'city' => trim( $request->getPost( 'city' ) ), 'postcode' => trim( $request->getPost( 'postcode' ) ) ) );
				$helper->setCustomerShippingAddress( $shippingAddress );
				$session->addSuccess( $helper->__( 'Your shipping address has been changed.' ) );
			}

			$this->_redirectReferer(  );
		}

		/**
     * Estimate post
     */
		function estimatePostAction() {
			$country = (bool)$this->getRequest(  )->getParam( 'country_id' );
			$postcode = (bool)$this->getRequest(  )->getParam( 'estimate_postcode' );
			$city = (bool)$this->getRequest(  )->getParam( 'estimate_city' );
			$regionId = (bool)$this->getRequest(  )->getParam( 'region_id' );
			$region = (bool)$this->getRequest(  )->getParam( 'region' );
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				foreach ($this->_getQuote(  )->getAllShippingAddresses(  ) as $address) {
					$address->setCountryId( $country )->setCity( $city )->setPostcode( $postcode )->setRegionId( $regionId )->setRegion( $region )->setCollectShippingRates( true );
				}
			} 
else {
				$this->_getQuote(  )->getShippingAddress(  )->setCountryId( $country )->setCity( $city )->setPostcode( $postcode )->setRegionId( $regionId )->setRegion( $region )->setCollectShippingRates( true );
			}

			$this->_getQuote(  )->save(  );
			$this->_goBack(  );
		}

		/**
     * Estimate update post 
     */
		function estimateUpdatePostAction() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$codes = $this->getRequest(  )->getParam( 'estimate_method' );

				if (count( $codes )) {
					foreach ($codes as $stockId => $code) {
						$address = $this->_getQuote(  )->getShippingAddressByStockId( $stockId );

						if ($address) {
							$address->setShippingMethod( $code )->save(  );
							continue;
						}
					}
				}
			} 
else {
				$code = (bool)$this->getRequest(  )->getParam( 'estimate_method' );

				if (!empty( $$code )) {
					$this->_getQuote(  )->getShippingAddress(  )->setShippingMethod( $code )->save(  );
				}
			}

			$this->_goBack(  );
		}

		/**
     * Shopping cart display action
     */
		function indexAction() {
			$cart = $this->_getCart(  );
			$cart->getQuote(  )->setItemsQtys( null );
			parent::indexAction(  );
		}

		/**
     * Update shopping cart data action
     */
		function updatePostAction() {
			$this->_getCart(  )->getQuote(  )->removeErrors(  );
			$updateAction = (bool)$this->getRequest(  )->getParam( 'update_cart_action' );

			if ($updateAction != 'reset_cart') {
				parent::updatePostAction(  );
				return null;
			}

			$this->_resetCart(  );
			$this->_goBack(  );
		}

		/**
     * Reset cart
     */
		function _resetCart() {
			$helper = $this->getWarehouseHelper(  );
			$session = $this->_getSession(  );
			$cartData = $this->getRequest(  )->getParam( 'cart' );

			if (is_array( $cartData )) {
				$cart = $this->_getCart(  );

				if (( !$cart->getCustomerSession(  )->getCustomer(  )->getId(  ) && $cart->getQuote(  )->getCustomerId(  ) )) {
					$cart->getQuote(  )->setCustomerId( null );
				}

				$cart->resetStocks( $cartData )->save(  );
			}

			$session->setCartWasUpdated( true );
			$session->addSuccess( $helper->__( 'Cart has been reset.' ) );
		}

		/**
     * Action to reconfigure cart item
     */
		function configureAction() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if ($config->isSplitQtyEnabled(  )) {
				$cart = $this->_getCart(  );
				$quote = $cart->getQuote(  );
				$id = (int)$this->getRequest(  )->getParam( 'id' );

				if ($id) {
					$quoteItem = $quote->getItemById( $id );
				}


				if ($quoteItem) {
					$quoteItem = $quote->getOrigionalItem( $quoteItem );
					$this->getRequest(  )->setParam( 'id', $quoteItem->getId(  ) );
				}

				$quote->mergeItems(  );
			}

			parent::configureAction(  );
		}

		/**
     * Update product configuration for a cart item
     */
		function updateItemOptionsAction() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if ($config->isSplitQtyEnabled(  )) {
				$quote = $this->_getCart(  )->getQuote(  );
				$quote->mergeItems(  );
			}

			parent::updateItemOptionsAction(  );
		}
	}

?>