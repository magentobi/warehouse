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

	class Innoexts_Warehouse_Model_Catalog_Product_Price_Observer {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Save batch prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function saveBatchPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->saveBatchPrices( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load batch prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function loadBatchPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->loadBatchPrices( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load collection batch prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function loadCollectionBatchPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->loadCollectionBatchPrices( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove batch prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function removeBatchPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->removeBatchPrices( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Save batch special prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function saveBatchSpecialPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->saveBatchSpecialPrices( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load batch special prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function loadBatchSpecialPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->loadBatchSpecialPrices( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load collection batch special prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function loadCollectionBatchSpecialPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->loadCollectionBatchSpecialPrices( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove batch special prices
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Price_Observer
     */
		function removeBatchSpecialPrices($observer) {
			$this->getWarehouseHelper(  )->getProductPriceHelper(  )->removeBatchSpecialPrices( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Before collection load
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function beforeCollectionLoad($observer) {
			$this->getWarehouseHelper(  )->getProductPriceIndexerHelper(  )->addPriceIndexFilter( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * After collection apply limitations
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function afterCollectionApplyLimitations($observer) {
			$this->getWarehouseHelper(  )->getProductPriceIndexerHelper(  )->addPriceIndexFilter( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}
	}

?>