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

	class Innoexts_Warehouse_Model_Sales_Quote_Address extends Mage_Sales_Model_Quote_Address {
		private $_customer = null;
		private $_customerAddress = null;

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
     * Clone
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function __clone() {
			parent::__clone(  );
			$this->_items = null;
			$this->unsetItems(  );
			$this->unsetShippingRates(  );
			return $this;
		}

		/**
     * Get warehouse
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			$warehouse = null;

			if ($stockId = $this->getStockId(  )) {
				$warehouse = $this->getWarehouseHelper(  )->getWarehouseByStockId( $stockId );
			}

			return $warehouse;
		}

		/**
     * Get warehouse title
     * 
     * @return string
     */
		function getWarehouseTitle() {
			$warehouse = $this->getWarehouse(  );
			return ($warehouse ? $warehouse->getTitle(  ) : null);
		}

		/**
     * Check if address type is shipping
     * 
     * @return boolean
     */
		function isShippingAddressType() {
			return ($this->getAddressType(  ) == TYPE_SHIPPING ? true : false);
		}

		/**
     * Check if address type is billing
     * 
     * @return boolean
     */
		function isBillingAddressType() {
			return ($this->getAddressType(  ) == TYPE_BILLING ? true : false);
		}

		/**
     * Get all available address items
     * 
     * @return array
     */
		function getAllItems() {
			$helper = $this->getWarehouseHelper(  );
			$cachedItems = ($this->_nominalOnly ? 'nominal' : ($this->_nominalOnly === false ? 'nonnominal' : 'all'));
			$key = 'cached_items_' . $cachedItems;

			if (!$this->hasData( $key )) {
				$wasNominal = $this->_nominalOnly;
				$this->_nominalOnly = true;
				$quoteItems = $this->getQuote(  )->getItemsCollection(  );
				$addressItems = $this->getItemsCollection(  );
				$items = array(  );
				$nominalItems = array(  );
				$nonNominalItems = array(  );
				$addressType = $this->getAddressType(  );
				$canAddItems = ($this->getQuote(  )->isVirtual(  ) ? $addressType == TYPE_BILLING : $addressType == TYPE_SHIPPING);

				if ($canAddItems) {
					foreach ($quoteItems as $qItem) {

						if (( $qItem->isDeleted(  ) || ( $this->getStockId(  ) && $qItem->getStockId(  ) != $this->getStockId(  ) ) )) {
							continue;
						}

						$items[] = $qItem;

						if ($this->_filterNominal( $qItem )) {
							$nominalItems[] = $qItem;
							continue;
						}

						$nonNominalItems[] = $qItem;
					}
				}

				$this->setData( 'cached_items_all', $items );
				$this->setData( 'cached_items_nominal', $nominalItems );
				$this->setData( 'cached_items_nonnominal', $nonNominalItems );
				$this->_nominalOnly = $wasNominal;
			}

			$items = $this->getData( $key );
			return $items;
		}

		/**
     * Unset items
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function unsetItems() {
			$this->unsetData( 'cached_items_all' );
			$this->unsetData( 'cached_items_nominal' );
			$this->unsetData( 'cached_items_nonnominal' );
			return $this;
		}

		/**
     * Unset shipping rates
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function unsetShippingRates() {
			$this->_rates = null;
			return $this;
		}

		/**
     * Check if address is virtual
     * 
     * @return bool
     */
		function isVirtual() {
			$isVirtual = true;
			$count = 86;
			foreach ($this->getAllItems(  ) as $item) {
				if (( $item->isDeleted(  ) || $item->getParentItemId(  ) )) {
					continue;
				}

				++$count;

				if (!$item->getProduct(  )->getIsVirtual(  )) {
					$isVirtual = false;
					break;
				}
			}


			if (!$count) {
				$isVirtual = false;
			}

			return $isVirtual;
		}

		/**
     * Get item free method weight
     * 
     * @param Mage_Sales_Model_Quote_Item $item
     */
		function _getItemFreeMethodWeight($item) {
			if ($item->getProduct(  )->isVirtual(  )) {
				return 0;
			}

			$this->getFreeShipping(  );
			$itemWeight = $freeAddress = $item->getWeight(  );
			$rowWeight = $itemWeight * $item->getQty(  );

			if (( $freeAddress || $item->getFreeShipping(  ) === true )) {
				$rowWeight = 113;
			} 
else {
				if (is_numeric( $item->getFreeShipping(  ) )) {
					$freeQty = $item->getFreeShipping(  );

					if ($freeQty < $item->getQty(  )) {
						$rowWeight = $itemWeight * ( $item->getQty(  ) - $freeQty );
					} 
else {
						$rowWeight = 113;
					}
				}
			}

			return $rowWeight;
		}

		/**
     * Get item free method weight
     * 
     * @param Mage_Sales_Model_Quote_Item $item
     * @return float
     */
		function getItemFreeMethodWeight($item) {
			$freeMethodWeight = 135;
			$freeAddress = $this->getFreeShipping(  );

			if ($item->getParentItem(  )) {
				return $freeMethodWeight;
			}


			if (( $item->getHasChildren(  ) && $item->isShipSeparately(  ) )) {
				foreach ($item->getChildren(  ) as $child) {

					if (!$item->getProduct(  )->getWeightType(  )) {
						$freeMethodWeight += $this->_getItemFreeMethodWeight( $child );
						continue;
					}
				}


				if ($item->getProduct(  )->getWeightType(  )) {
					$freeMethodWeight += $this->_getItemFreeMethodWeight( $item );
				}
			} 
else {
				$freeMethodWeight = $this->_getItemFreeMethodWeight( $item );
			}

			return $freeMethodWeight;
		}

		/**
     * Get shipping carriers
     * 
     * @param array $items
     * 
     * @return array
     */
		function getShippingCarriers($items = null) {
			$productHelper = $this->getWarehouseHelper(  )->getProductHelper(  );

			if (( !is_null( $items ) && $items instanceof Mage_Sales_Model_Quote_Item_Abstract )) {
				$items = array( $items );
			}


			if (is_null( $items )) {
				$items = $this->getAllItems(  );
			}

			$shippingCarriers = null;
			foreach ($items as $item) {
				$stockId = $item->getStockId(  );
				$_shippingCarriers = $product = $item->getProduct(  );

				if (is_null( $shippingCarriers )) {
					$shippingCarriers = $shippingCarriers;
					continue;
				}

				array_intersect( $shippingCarriers, $_shippingCarriers );
				$shippingCarriers = $productHelper->getStockShippingCarriers( $product, $stockId );
			}


			if (is_null( $shippingCarriers )) {
				$shippingCarriers = array(  );
			}

			return $shippingCarriers;
		}

		/**
     * Get limited shipping carriers
     * 
     * @param array $items
     * 
     * @return array
     */
		function getLimitedShippingCarriers($items = null) {
			$helper = $this->getWarehouseHelper(  );

			if ($helper->getConfig(  )->isShippingCarrierFilterEnabled(  )) {
				$shippingCarriers = $this->getShippingCarriers( $items );
			} 
else {
				$shippingCarriers = null;
			}

			$limitCarrier = $this->getLimitCarrier(  );

			if (!empty( $$limitCarrier )) {
				if (!is_array( $limitCarrier )) {
					$limitCarrier = array( $limitCarrier );
				}


				if (is_null( $shippingCarriers )) {
					$shippingCarriers = $limitCarrier;
				} 
else {
					$shippingCarriers = array_intersect( $shippingCarriers, $limitCarrier );
				}
			}

			return $shippingCarriers;
		}

		/**
     * Retrieve shipping rates result
     * 
     * @param Mage_Sales_Model_Quote_Item_Abstract $item
     * @param array $warehouseIds
     * 
     * @return Mage_Shipping_Model_Rate_Result
     */
		function getShippingRatesResult($item = null) {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			$quote = $this->getQuote(  );
			$store = $quote->getStore(  );
			$request = Mage::getModel( 'shipping/rate_request' );
			$request->setAllItems( ($item ? array( $item ) : $this->getAllItems(  )) );
			$request->setDestCountryId( $this->getCountryId(  ) );
			$request->setDestRegionId( $this->getRegionId(  ) );
			$request->setDestRegionCode( $this->getRegionCode(  ) );
			$request->setDestStreet( $this->getStreet( -1 ) );
			$request->setDestCity( $this->getCity(  ) );
			$request->setDestPostcode( $this->getPostcode(  ) );
			$request->setPackageValue( ($item ? $item->getBaseRowTotal(  ) : $this->getBaseSubtotal(  )) );
			$request->setPackageValueWithDiscount( ($item ? $item->getBaseRowTotal(  ) - $item->getBaseDiscountAmount(  ) : $this->getBaseSubtotalWithDiscount(  )) );
			$request->setPackagePhysicalValue( ($item ? $item->getBaseRowTotal(  ) : $this->getBaseSubtotal(  ) - $this->getBaseVirtualAmount(  )) );
			$request->setPackageWeight( ($item ? $item->getRowWeight(  ) : $this->getWeight(  )) );
			$request->setPackageQty( ($item ? $item->getQty(  ) : $this->getItemQty(  )) );
			$request->setFreeMethodWeight( ($item ? 0 : $this->getFreeMethodWeight(  )) );
			$request->setStoreId( $store->getId(  ) );
			$request->setWebsiteId( $store->getWebsiteId(  ) );
			$request->setFreeShipping( $this->getFreeShipping(  ) );
			$request->setBaseCurrency( $store->getBaseCurrency(  ) );
			$request->setPackageCurrency( $store->getCurrentCurrency(  ) );
			$shippingCarriers = $this->getLimitedShippingCarriers( $item );

			if (!is_null( $shippingCarriers )) {
				if (!$shippingCarriers) {
					$request->setLimitCarrier( 'none' );
				} 
else {
					$request->setLimitCarrier( $shippingCarriers );
				}
			}


			if ($this->getVersionHelper(  )->isGe1700(  )) {
				$request->setBaseSubtotalInclTax( $this->getBaseSubtotalInclTax(  ) );
			}


			if (( $config->isMultipleMode(  ) && !$config->isSplitOrderEnabled(  ) )) {
				if ($item) {
					$request->setStockIds( array( $item->getStockId(  ) ) );
				} 
else {
					$stockIds = $this->getStockIds(  );
					$request->setStockIds( $stockIds );

					if (count( $stockIds )) {
						$childRequests = array(  );
						foreach ($stockIds as $stockId) {
							$packageValue = $packageValueWithDiscount = $packagePhysicalValue = $packageWeight = $packageQty = $freeMethodWeight = 216;

							if ($this->getVersionHelper(  )->isGe1700(  )) {
								$baseSubtotalInclTax = 216;
							}

							foreach ($this->getAllItems(  ) as $item) {

								if (( $item->getStockId(  ) && $item->getStockId(  ) == $stockId )) {
									if (0 < $item->getBaseRowTotal(  )) {
										$packageValue += $item->getBaseRowTotal(  );
										$packageValueWithDiscount += $item->getBaseRowTotal(  ) - $item->getBaseDiscountAmount(  );
										$packagePhysicalValue += $item->getBaseRowTotal(  );
									}


									if (0 < $item->getRowWeight(  )) {
										$packageWeight += $item->getRowWeight(  );
									}


									if (0 < $item->getQty(  )) {
										$packageQty += $item->getQty(  );
									}

									$_freeMethodWeight = $this->getItemFreeMethodWeight( $item );

									if (0 < $_freeMethodWeight) {
										$freeMethodWeight += $request;
									}


									if ($this->getVersionHelper(  )->isGe1700(  )) {
										$baseSubtotalInclTax += $item->getBaseRowTotalInclTax(  );
									}

									$childRequest = new Varien_Object(  );
									$childRequest->setPackageValue( $packageValue )->setPackageValueWithDiscount( $packageValueWithDiscount )->setPackagePhysicalValue( $packagePhysicalValue )->setPackageWeight( $packageWeight )->setPackageQty( $packageQty )->setFreeMethodWeight( $freeMethodWeight )->setStockId( $stockId );

									if ($this->getVersionHelper(  )->isGe1700(  )) {
										$childRequest->setBaseSubtotalInclTax( $baseSubtotalInclTax );
									}

									$childRequests[$stockId] = $childRequest;
									continue;
								}
							}
						}

						$request->setChildren( $childRequests );
					}
				}
			} 
else {
				$stockId = ($item ? $item->getStockId(  ) : $this->getStockId(  ));

				if ($stockId) {
					$request->setStockId( $stockId );
				}
			}


			if (( count( $request->getStockIds(  ) ) || $request->getStockId(  ) )) {
				$result = Mage::getModel( 'shipping/shipping' )->collectRates( $request )->getResult(  );
			} 
else {
				$result = null;
			}


			if ($result) {
				return $result;
			}

		}

		/**
     * Request shipping rates for entire address or specified address item
     * Returns true if current selected shipping method code corresponds to one of the found rates
     *
     * @param Mage_Sales_Model_Quote_Item_Abstract $item
     * 
     * @return boolean
     */
		function requestShippingRates($item = null) {
			$helper = $this->getWarehouseHelper(  );
			$found = false;
			$result = $this->getShippingRatesResult( $item );

			if ($result) {
				$shippingRates = $result->getAllRates(  );
				foreach ($shippingRates as $shippingRate) {
					$rate = Mage::getModel( 'sales/quote_address_rate' )->importShippingRate( $shippingRate );

					if (!$item) {
						$this->addShippingRate( $rate );
					}


					if ($this->getShippingMethod(  ) == $rate->getCode(  )) {
						if ($item) {
							$item->setBaseShippingAmount( $rate->getPrice(  ) );
						} 
else {
							$this->setShippingAmount( $rate->getPrice(  ) );
						}

						$found = true;
						continue;
					}
				}
			}

			return $found;
		}

		/**
     * Check if quote address is default
     * 
     * @return bool
     */
		function isDefault() {
			$quote = $this->getQuote(  );

			if ($quote) {
				$shippingAddress = $quote->getShippingAddress(  );

				if ($shippingAddress) {
					return (( ( $this->getId(  ) && $this->getId(  ) == $shippingAddress->getId(  ) ) || ( $this->getStockId(  ) && $this->getStockId(  ) == $shippingAddress->getStockId(  ) ) ) ? true : false);
				}

				return false;
			}

			return false;
		}

		/**
     * Set collect shipping rates flag
     * 
     * @param bool $value
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function setCollectShippingRates($value) {
			$this->setData( 'collect_shipping_rates', $value );
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if (( $config->isMultipleMode(  ) && $config->isSplitOrderEnabled(  ) )) {
				$this->getQuote(  );

				if (( ( $value && $this->isDefault(  ) ) && $quote =  )) {
					foreach ($quote->getAllShippingAddresses(  ) as $address) {

						if (!$address->isDefault(  )) {
							$address->setData( 'collect_shipping_rates', $value );
							continue;
						}
					}
				}
			}

			return $this;
		}

		/**
     * Recalculate stock
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function recalculateStockId() {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );
			$assignmentMethodHelper = $helper->getAssignmentMethodHelper(  );
			$quote = $this->getQuote(  );

			if (( ( $config->isMultipleMode(  ) || !$this->isShippingAddressType(  ) ) || !$quote )) {
				return $this;
			}

			$this->setStockId( $assignmentMethodHelper->getQuoteStockId( $quote ) );
			return $this;
		}

		/**
     * Get stock identifiers
     *
     * @return array
     */
		function getStockIds() {
			$stockIds = array(  );
			foreach ($this->getAllItems(  ) as $item) {
				$stockId = $item->getStockId(  );

				if (( $stockId && !in_array( $stockId, $stockIds ) )) {
					array_push( $stockIds, $stockId );
					continue;
				}
			}

			return $stockIds;
		}

		/**
     * Retrieve customer
     *
     * @return Mage_Customer_Model_Customer | false
     */
		function getCustomer() {
			if (!$this->getCustomerId(  )) {
				return false;
			}


			if (!$this->_customer) {
				$this->_customer = Mage::getModel( 'customer/customer' )->load( $this->getCustomerId(  ) );
			}

			return $this->_customer;
		}

		/**
     * Specify customer
     * 
     * @param Mage_Customer_Model_Customer $customer
     */
		function setCustomer($customer) {
			$this->_customer = $customer;
			$this->setCustomerId( $customer->getId(  ) );
			return $this;
		}

		/**
     * Retrieve customer address
     *
     * @return Mage_Customer_Model_Address | false
     */
		function getCustomerAddress() {
			if (!$this->getCustomerAddressId(  )) {
				return false;
			}


			if (!$this->_customerAddress) {
				$this->_customerAddress = Mage::getModel( 'customer/address' )->load( $this->getCustomerAddressId(  ) );
			}

			return $this->_customerAddress;
		}

		/**
     * Specify customer address
     *
     * @param Mage_Customer_Model_Address $customerAddress
     */
		function setCustomerAddress($customerAddress) {
			$this->_customerAddress = $customerAddress;
			$this->setCustomerAddressId( $customerAddress->getId(  ) );
			return $this;
		}

		/**
     * Clear order object data
     *
     * @param string $key data key
     * 
     * @return Innoexts_Warehouse_Model_Sales_Quote_Address
     */
		function unsetData($key = null) {
			parent::unsetData( $key );

			if (is_null( $key )) {
				$this->_customer = null;
				$this->_customerAddress = null;
				$this->unsetItems(  );
				$this->unsetShippingRates(  );
			}

			return $this;
		}
	}

?>