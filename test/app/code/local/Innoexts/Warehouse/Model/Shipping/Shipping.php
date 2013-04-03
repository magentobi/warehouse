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

	class Innoexts_Warehouse_Model_Shipping_Shipping extends Mage_Shipping_Model_Shipping {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/** 
     * Apply request origin
     * 
     * @param Mage_Shipping_Model_Shipping_Method_Request $request
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Shipping_Shipping
     */
		function applyRequestOrigin($request, $stockId = null) {
			$helper = $this->getWarehouseHelper(  );

			if (!$stockId) {
				$stockId = $request->getStockId(  );
			}


			if (!$stockId) {
				return $this;
			}

			$warehouse = $helper->getWarehouseByStockId( $stockId );

			if (!$warehouse) {
				return $this;
			}

			$origin = $warehouse->getOrigin(  );
			$origRegionId = ($origin->getRegionId(  ) ? $origin->getRegionId(  ) : $origin->getRegion(  ));
			$origRegionCode = $stockId;

			if (is_numeric( $origRegionId )) {
				$origRegionCode = Mage::getModel( 'directory/region' )->load( $origRegionCode )->getCode(  );
			} 
else {
				$origRegionCode = $stockId;
			}

			$request->setCountryId( $origin->getCountryId(  ) );
			$request->setRegionId( $origRegionId );
			$request->setCity( $origin->getCity(  ) );
			$request->setPostcode( $origin->getPostcode(  ) );
			$request->setOrigCountryId( $origin->getCountryId(  ) );
			$request->setOrigRegionId( $origRegionId );
			$request->setOrigCity( $origin->getCity(  ) );
			$request->setOrigPostcode( $origin->getPostcode(  ) );
			$request->setOrigCountry( $origin->getCountryId(  ) );
			$request->setOrigRegionCode( $origRegionCode );
			$request->setOrig( $origin );
			$request->setWarehouseId( $warehouse->getId(  ) );
			return $this;
		}

		/**
     * Get result rate by carrier and method
     * 
     * @param Mage_Shipping_Model_Rate_Result $result
     * @param string $carrier
     * @param string $method
     * 
     * @return Mage_Shipping_Model_Rate_Result_Method | null
     */
		function getResultRate($result, $carrier, $method) {
			$rate = null;

			if (( $result && $result instanceof Mage_Shipping_Model_Rate_Result )) {
				foreach ($result->getAllRates(  ) as $_rate) {

					if (( $_rate->getCarrier(  ) == $carrier && $_rate->getMethod(  ) == $method )) {
						$rate = $method;
						break;
						continue;
					}
				}
			}

			return $rate;
		}

		/**
     * Collect carrier rates
     * 
     * @param string $carrierCode
     * @param Mage_Shipping_Model_Shipping_Method_Request $request
     * 
     * @return Innoexts_Warehouse_Model_Shipping_Shipping
     */
		function collectCarrierRates($carrierCode, $request) {
			$helper = $this->getWarehouseHelper(  );
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			$carrier = $this->getCarrierByCode( $carrierCode, $request->getStoreId(  ) );

			if ($carrier) {
				if ($helper->getVersionHelper(  )->isGe1600(  )) {
					$carrier->setActiveFlag( $this->_availabilityConfigField );
				}

				$result = $carrier->checkAvailableShipCountries( $request );

				if (( false !== $result && !( $result instanceof Mage_Shipping_Model_Rate_Result_Error ) )) {
					$result = $carrier->proccessAdditionalValidation( $request );
				}


				if (false !== $result) {
					if (!( $result instanceof Mage_Shipping_Model_Rate_Result_Error )) {
						if (( $config->isMultipleMode(  ) && !$config->isSplitOrderEnabled(  ) )) {
							$result = null;
							$childRequests = $request->getChildren(  );

							if (count( $childRequests )) {
								foreach ($childRequests as $stockId => $_childRequest) {
									clone $request;
									$childRequest = ;
									$childRequest->setPackageValue( $_childRequest->getPackageValue(  ) )->setPackageValueWithDiscount( $_childRequest->getPackageValueWithDiscount(  ) )->setPackagePhysicalValue( $_childRequest->getPackagePhysicalValue(  ) )->setPackageWeight( $_childRequest->getPackageWeight(  ) )->setPackageQty( $_childRequest->getPackageQty(  ) )->setFreeMethodWeight( $_childRequest->getFreeMethodWeight(  ) )->setStockId( $_childRequest->getStockId(  ) );
									$this->applyRequestOrigin( $childRequest, $stockId );
									$childResult = $carrier->collectRates( $childRequest );

									if (!$childResult) {
										return $this;
									}


									if (( $result && $result instanceof Mage_Shipping_Model_Rate_Result )) {
										foreach ($childResult->getAllRates(  ) as $childRate) {
											$rate = $this->getResultRate( $result, $childRate->getCarrier(  ), $childRate->getMethod(  ) );

											if ($rate) {
												$rate->setPrice( floatval( $rate->getPrice(  ) ) + floatval( $childRate->getPrice(  ) ) );
												continue;
											}

											$result->append( $childRate );
										}

										continue;
									}

									$result = $childRate;
								}
							}
						} 
else {
							$this->applyRequestOrigin( $request );
							$result = $carrier->collectRates( $request );

							if (!$result) {
								return $this;
							}
						}
					}


					if (( $carrier->getConfigData( 'showmethod' ) == 0 && $result->getError(  ) )) {
						return $this;
					}


					if (method_exists( $result, 'sortRatesByPrice' )) {
						$result->sortRatesByPrice(  );
					}

					$this->getResult(  )->append( $result );
				}
			}

			return $this;
		}
	}

?>