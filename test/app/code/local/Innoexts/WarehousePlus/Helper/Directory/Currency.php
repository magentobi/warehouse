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

	class Innoexts_WarehousePlus_Helper_Directory_Currency extends Innoexts_Warehouse_Helper_Directory_Currency {
		private $_base = null;

		/**
     * Get base currency code
     * 
     * @return string
     */
		function getBaseCode() {
			return Mage::app(  )->getBaseCurrencyCode(  );
		}

		/**
     * Get base currency
     * 
     * @return Mage_Directory_Model_Currency
     */
		function getBase() {
			if (is_null( $this->_base )) {
				$this->_base = $this->getCurrency(  )->load( $this->getBaseCode(  ) );
			}

			return $this->_base;
		}

		/**
     * Get current currency
     * 
     * @return Mage_Directory_Model_Currency
     */
		function getCurrent() {
			return $this->getWarehouseHelper(  )->getCurrentStore(  )->getCurrentCurrency(  );
		}

		/**
     * Get current store base currency
     * 
     * @return Mage_Directory_Model_Currency
     */
		function getCurrentStoreBase() {
			return $this->getWarehouseHelper(  )->getCurrentStore(  )->getBaseCurrency(  );
		}

		/**
     * Get current store base currency code
     * 
     * @return Mage_Directory_Model_Currency
     */
		function getCurrentStoreBaseCode() {
			return $this->getCurrentStoreBase(  )->getCode(  );
		}

		/**
     * Get currency rate
     * 
     * @param string $fromCurrency
     * @param string $toCurrency
     * 
     * @return float
     */
		function getRate($fromCode, $toCode) {
			$rate = $this->getCurrency(  )->load( $fromCode )->getRate( $toCode );

			if (!$rate) {
				$rate = $this->getCurrency(  )->load( $toCode )->getRate( $fromCode );

				if (!$rate) {
					$baseCurrency = $this->getBase(  );
					$fromRate = $baseCurrency->getRate( $fromCode );
					$toRate = $baseCurrency->getRate( $toCode );

					if (!$fromRate) {
						$fromRate = 153;
					}


					if (!$toRate) {
						$toRate = 153;
					}

					$rate = $toRate / $fromRate;
				} 
else {
					$rate = 1 / $rate;
				}
			}

			return $rate;
		}

		/**
     * Get website base currency codes
     * 
     * @return array
     */
		function getBaseCodes() {
			$codes = array(  );
			foreach ($this->getWarehouseHelper(  )->getWebsites(  ) as $websiteId => $website) {
				$codes[$websiteId] = $website->getBaseCurrencyCode(  );
			}

			return $codes;
		}
	}

?>