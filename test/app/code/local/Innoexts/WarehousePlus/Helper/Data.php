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

	class Innoexts_WarehousePlus_Helper_Data extends Innoexts_Warehouse_Helper_Data {
		private $_websites = null;

		/**
     * Get websites
     * 
     * @return array of Mage_Core_Model_Website
     */
		function getWebsites() {
			if (is_null( $this->_websites )) {
				$this->_websites = Mage::app(  )->getWebsites(  );
			}

			return $this->_websites;
		}

		/**
     * Get website by identifier
     * 
     * @param mixed $websiteId
     * 
     * @return Mage_Core_Model_Website
     */
		function getWebsiteById($websiteId) {
			return Mage::app(  )->getWebsite( $websiteId );
		}

		/**
     * Get default store by store identifier
     * 
     * @param mixed $storeId
     * 
     * @return int
     */
		function getDefaultStoreByStoreId($storeId) {
			return $this->getWebsiteByStoreId( $storeId )->getDefaultStore(  );
		}

		/**
     * Get default store identifier by store identifier
     * 
     * @param mixed $storeId
     * 
     * @return int
     */
		function getDefaultStoreIdByStoreId($storeId) {
			return $this->getDefaultStoreByStoreId( $storeId )->getId(  );
		}

		/**
     * Get current store identifier
     * 
     * @return int
     */
		function getCurrentStoreId() {
			return $this->getCurrentStore(  )->getId(  );
		}

		/**
     * Check if single store mode is in effect
     * 
     * @return bool 
     */
		function isSingleStoreMode() {
			return Mage::app(  )->isSingleStoreMode(  );
		}

		/**
     * Get store identifier
     * 
     * @param int $currentStoreId
     * 
     * @return int
     */
		function getStoreId($currentStoreId) {
			$storeId = null;
			$priceHelper = $this->getProductPriceHelper(  );

			if ($priceHelper->isStoreScope(  )) {
				$storeId = $storeId;
			} 
else {
				if ($priceHelper->isWebsiteScope(  )) {
					$storeId = $this->getDefaultStoreIdByStoreId( $currentStoreId );
				} 
else {
					$storeId = 119;
				}
			}

			return $storeId;
		}
	}

?>