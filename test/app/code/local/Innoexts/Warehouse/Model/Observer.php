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

	class Innoexts_Warehouse_Model_Observer {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Add system config js
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function addSystemConfigJs($observer) {
			$block = $observer->getEvent(  )->getBlock(  );

			if (( !$block || !( $block instanceof Mage_Adminhtml_Block_System_Config_Edit ) )) {
				return $this;
			}

			$layout = $block->getLayout(  );

			if (!$layout) {
				return $this;
			}

			$layout->getBlock( 'js' )->append( $layout->createBlock( 'adminhtml/template' )->setTemplate( 'warehouse/system/config/js.phtml' ) );
			return $this;
		}

		/**
     * Set coordinates
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function setCoordinates($observer) {
			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );

			if (( !$config->isNearestSingleAssignmentMethod(  ) && !$config->isNearestMultipleAssignmentMethod(  ) )) {
				return $this;
			}

			$warehouse = $observer->getEvent(  )->getWarehouse(  );

			if (( !$warehouse || !( $warehouse instanceof Innoexts_Warehouse_Model_Warehouse ) )) {
				return $this;
			}

			$helper->setWarehouseCoordinates( $warehouse );
			return $this;
		}

		/**
     * Save stores
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function saveStores($observer) {
			$this->getWarehouseHelper(  )->saveStores( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Add data stores
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function addDataStores($observer) {
			$this->getWarehouseHelper(  )->addDataStores( $observer->getEvent(  )->getWarehouse(  ), $observer->getEvent(  )->getArray(  ) );
			return $this;
		}

		/**
     * Load stores
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadStores($observer) {
			$this->getWarehouseHelper(  )->loadStores( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Load collection stores
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadCollectionStores($observer) {
			$this->getWarehouseHelper(  )->loadCollectionStores( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove stores
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function removeStores($observer) {
			$this->getWarehouseHelper(  )->removeStores( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Save customer groups
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function saveCustomerGroups($observer) {
			$this->getWarehouseHelper(  )->saveCustomerGroups( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Add data customer groups
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function addDataCustomerGroups($observer) {
			$this->getWarehouseHelper(  )->addDataCustomerGroups( $observer->getEvent(  )->getWarehouse(  ), $observer->getEvent(  )->getArray(  ) );
			return $this;
		}

		/**
     * Load customer groups
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadCustomerGroups($observer) {
			$this->getWarehouseHelper(  )->loadCustomerGroups( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Load collection customer groups
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadCollectionCustomerGroups($observer) {
			$this->getWarehouseHelper(  )->loadCollectionCustomerGroups( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove customer groups
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function removeCustomerGroups($observer) {
			$this->getWarehouseHelper(  )->removeCustomerGroups( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Save currencies
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function saveCurrencies($observer) {
			$this->getWarehouseHelper(  )->saveCurrencies( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Add data currencies
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function addDataCurrencies($observer) {
			$this->getWarehouseHelper(  )->addDataCurrencies( $observer->getEvent(  )->getWarehouse(  ), $observer->getEvent(  )->getArray(  ) );
			return $this;
		}

		/**
     * Load currencies
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadCurrencies($observer) {
			$this->getWarehouseHelper(  )->loadCurrencies( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Load collection currencies
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadCollectionCurrencies($observer) {
			$this->getWarehouseHelper(  )->loadCollectionCurrencies( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove currencies
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function removeCurrencies($observer) {
			$this->getWarehouseHelper(  )->removeCurrencies( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Save shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function saveShippingCarriers($observer) {
			$this->getWarehouseHelper(  )->saveShippingCarriers( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Add data shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function addDataShippingCarriers($observer) {
			$this->getWarehouseHelper(  )->addDataShippingCarriers( $observer->getEvent(  )->getWarehouse(  ), $observer->getEvent(  )->getArray(  ) );
			return $this;
		}

		/**
     * Load shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadShippingCarriers($observer) {
			$this->getWarehouseHelper(  )->loadShippingCarriers( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}

		/**
     * Load collection shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function loadCollectionShippingCarriers($observer) {
			$this->getWarehouseHelper(  )->loadCollectionShippingCarriers( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Observer
     */
		function removeShippingCarriers($observer) {
			$this->getWarehouseHelper(  )->removeShippingCarriers( $observer->getEvent(  )->getWarehouse(  ) );
			return $this;
		}
	}

?>