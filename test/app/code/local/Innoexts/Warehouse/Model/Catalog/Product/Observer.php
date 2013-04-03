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

	class Innoexts_Warehouse_Model_Catalog_Product_Observer {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get product helper
     * 
     * @return Innoexts_Warehouse_Helper_Catalog_Product
     */
		function getProductHelper() {
			return $this->getWarehouseHelper(  )->getProductHelper(  );
		}

		/**
     * Get request
     * 
     * @return Mage_Core_Controller_Request_Http
     */
		function getRequest() {
			return Mage::app(  )->getRequest(  );
		}

		/**
     * Add quote block
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function addQuoteBlock($observer) {
			$block = $observer->getEvent(  )->getBlock(  );

			if (( ( ( !$block || !( $block instanceof Mage_Catalog_Block_Product_View ) ) || $block->getType(  ) !== 'catalog/product_view' ) || $block->getNameInLayout(  ) !== 'product.info' )) {
				return $this;
			}

			$helper = $this->getWarehouseHelper(  );
			$layout = $block->getLayout(  );
			$product = $block->getProduct(  );
			$productHelper = $helper->getProductHelper(  );
			$config = $helper->getConfig(  );

			if (( ( ( !$layout || !$product ) || !$config->isCatalogInformationVisible(  ) ) || !$productHelper->isQuoteInStock( $product ) )) {
				return $this;
			}

			$containerBlock = $layout->createBlock( 'warehouse/catalog_product_view_quote_container' );
			$block->append( $containerBlock );
			$containerBlock->addToParentGroup( 'detailed_info' );
			return $this;
		}

		/**
     * Get edit tab html
     * 
     * @param Mage_Core_Model_Layout $layout
     * @param string $tabId
     * 
     * @return string
     */
		function getEditTabHtml($layout, $tabId) {
			return $layout->createBlock( 'warehouse/adminhtml_catalog_product_edit_tab_' . $tabId )->toHtml(  );
		}

		/**
     * Add tabs
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function addTabs($observer) {
			$block = $observer->getEvent(  )->getBlock(  );

			if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
				$request = $this->getRequest(  );

				if (( $request->getActionName(  ) == 'edit' || $request->getParam( 'type' ) )) {
					$helper = $this->getWarehouseHelper(  );
					$config = $helper->getConfig(  );
					$tabsIds = $block->getTabsIds(  );
					$layout = $block->getLayout(  );
					$after = (( array_search( 'inventory', $tabsIds ) !== false && 0 < array_search( 'inventory', $tabsIds ) ) ? $tabsIds[array_search( 'inventory', $tabsIds ) - 1] : 'categories');
					$block->removeTab( 'inventory' );
					$block->addTab( 'inventory', array( 'after' => $after, 'label' => $helper->__( 'Inventory' ), 'content' => $this->getEditTabHtml( $layout, 'inventory' ) ) );

					if ($config->isShelvesEnabled(  )) {
						$block->addTab( 'shelf', array( 'after' => $after, 'label' => $helper->__( 'Shelves' ), 'content' => $this->getEditTabHtml( $layout, 'shelf' ) ) );
					}


					if ($config->isPriorityEnabled(  )) {
						$block->addTab( 'priority', array( 'after' => $after, 'label' => $helper->__( 'Priority' ), 'content' => $this->getEditTabHtml( $layout, 'priority' ) ) );
					}


					if ($config->isShippingCarrierFilterEnabled(  )) {
						$block->addTab( 'shipping', array( 'after' => $after, 'label' => $helper->__( 'Shipping Carriers' ), 'content' => $this->getEditTabHtml( $layout, 'shipping' ) ) );
					}
				}
			}

			return $this;
		}

		/**
     * Add stock item
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function addStockItem($observer) {
			$product = $observer->getEvent(  )->getProduct(  );

			if (!( $product instanceof Mage_Catalog_Model_Product )) {
				return $this;
			}

			$helper = $this->getWarehouseHelper(  );
			$config = $helper->getConfig(  );

			if (!$config->isMultipleMode(  )) {
				return $this;
			}

			$stockId = $helper->getProductHelper(  )->getStockId( $product );

			if ($stockId) {
				$stockItem = $helper->getCatalogInventoryHelper(  )->getStockItemCached( intval( $product->getId(  ) ), $stockId );
				$stockItem->assignProduct( $product );
			}

			return $this;
		}

		/**
     * Save stock shelves
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function saveStockShelves($observer) {
			$this->getProductHelper(  )->saveStockShelves( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load stock shelves
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function loadStockShelves($observer) {
			$this->getProductHelper(  )->loadStockShelves( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Remove stock shelves
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function removeStockShelves($observer) {
			$this->getProductHelper(  )->removeStockShelves( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Save stock shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function saveStockShippingCarriers($observer) {
			$this->getProductHelper(  )->saveStockShippingCarriers( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load stock shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function loadStockShippingCarriers($observer) {
			$this->getProductHelper(  )->loadStockShippingCarriers( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load collection stock shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function loadCollectionStockShippingCarriers($observer) {
			$this->getProductHelper(  )->loadCollectionStockShippingCarriers( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove stock shipping carriers
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function removeStockShippingCarriers($observer) {
			$this->getProductHelper(  )->removeStockShippingCarriers( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Save stock priorities
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function saveStockPriorities($observer) {
			$this->getProductHelper(  )->saveStockPriorities( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load stock priorities
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function loadStockPriorities($observer) {
			$this->getProductHelper(  )->loadStockPriorities( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}

		/**
     * Load collection stock priorities
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function loadCollectionStockPriorities($observer) {
			$this->getProductHelper(  )->loadCollectionStockPriorities( $observer->getEvent(  )->getCollection(  ) );
			return $this;
		}

		/**
     * Remove stock priorities
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Catalog_Product_Observer
     */
		function removeStockPriorities($observer) {
			$this->getProductHelper(  )->removeStockPriorities( $observer->getEvent(  )->getProduct(  ) );
			return $this;
		}
	}

?>