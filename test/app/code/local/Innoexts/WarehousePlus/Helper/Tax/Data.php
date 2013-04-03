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

	class Innoexts_WarehousePlus_Helper_Tax_Data extends Mage_Tax_Helper_Data {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Round price
     * 
     * @param mixed $price
     * @return float
     */
		function roundPrice($price) {
			return round( $price, 4 );
		}

		/**
     * Get product price with all tax settings processing
     *
     * @param   Mage_Catalog_Model_Product $product
     * @param   float $price inputed product price
     * @param   bool $includingTax return price include tax flag
     * @param   null|Mage_Customer_Model_Address $shippingAddress
     * @param   null|Mage_Customer_Model_Address $billingAddress
     * @param   null|int $ctc customer tax class
     * @param   mixed $store
     * @param   bool $priceIncludesTax flag what price parameter contain tax
     * 
     * @return  float
     */
		function getPrice($product, $price, $includingTax = null, $shippingAddress = null, $billingAddress = null, $ctc = null, $store = null, $priceIncludesTax = null) {
			if (!$price) {
				return $price;
			}

			$store = Mage::app(  )->getStore( $store );

			if (!$this->needPriceConversion( $store )) {
				return $this->roundPrice( $price );
			}


			if (is_null( $priceIncludesTax )) {
				$priceIncludesTax = $this->priceIncludesTax( $store );
			}

			$percent = $product->getTaxPercent(  );
			$includingPercent = null;
			$taxClassId = $product->getTaxClassId(  );

			if (is_null( $percent )) {
				if ($taxClassId) {
					$request = Mage::getSingleton( 'tax/calculation' )->getRateRequest( $shippingAddress, $billingAddress, $ctc, $store );
					$percent = Mage::getSingleton( 'tax/calculation' )->getRate( $request->setProductClassId( $taxClassId ) );
				}
			}


			if (( $taxClassId && $priceIncludesTax )) {
				$request = Mage::getSingleton( 'tax/calculation' )->getRateRequest( false, false, false, $store );
				$includingPercent = Mage::getSingleton( 'tax/calculation' )->getRate( $request->setProductClassId( $taxClassId ) );
			}


			if (( $percent === false || is_null( $percent ) )) {
				if (( $priceIncludesTax && !$includingPercent )) {
					return $price;
				}
			}

			$product->setTaxPercent( $percent );

			if (!is_null( $includingTax )) {
				if ($priceIncludesTax) {
					if ($includingTax) {
						if ($includingPercent != $percent) {
							$price = $this->_calculatePrice( $price, $includingPercent, false );

							if ($percent != 0) {
								$price = $this->getCalculator(  )->round( $price );
								$price = $this->_calculatePrice( $price, $percent, true );
							}
						}
					} 
else {
						$price = $this->_calculatePrice( $price, $includingPercent, false );
					}
				} 
else {
					if ($includingTax) {
						$price = $this->_calculatePrice( $price, $percent, true );
					}
				}
			} 
else {
				if ($priceIncludesTax) {
					switch ($this->getPriceDisplayType( $store )) {
						case DISPLAY_TYPE_EXCLUDING_TAX: {
						}

						case DISPLAY_TYPE_BOTH: {
							$price = $this->_calculatePrice( $price, $includingPercent, false );
							break;
						}

						case DISPLAY_TYPE_INCLUDING_TAX: {
							$price = $this->_calculatePrice( $price, $includingPercent, false );
							$price = $this->_calculatePrice( $price, $percent, true );
						}
					}
				} 
else {
					switch ($this->getPriceDisplayType( $store )) {
						case DISPLAY_TYPE_INCLUDING_TAX: {
							$price = $this->_calculatePrice( $price, $percent, true );
							break;
						}

						case DISPLAY_TYPE_BOTH: {
						}

						case DISPLAY_TYPE_EXCLUDING_TAX: {
						}
					}
				}
			}

			return $this->roundPrice( $price );
		}
	}

?>