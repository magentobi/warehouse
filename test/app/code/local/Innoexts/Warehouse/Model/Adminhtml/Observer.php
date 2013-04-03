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

	class Innoexts_Warehouse_Model_Adminhtml_Observer {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Add grid column
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Observer
     */
		function addGridColumn($observer) {
			$block = $observer->getEvent(  )->getBlock(  );

			if (!$block) {
				return $this;
			}

			$adminhtmlHelper = $this->getWarehouseHelper(  )->getAdminhtmlHelper(  );

			if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Grid) {
				$adminhtmlHelper->addQtyProductGridColumn( $block );
				$adminhtmlHelper->addBatchPriceProductGridColumn( $block );
			} 
else {
				if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
					$adminhtmlHelper->addStockOrderGridColumn( $block );
				} 
else {
					if ($block instanceof Mage_Adminhtml_Block_Sales_Invoice_Grid) {
						$adminhtmlHelper->addStockInvoiceGridColumn( $block );
					} 
else {
						if ($block instanceof Mage_Adminhtml_Block_Sales_Shipment_Grid) {
							$adminhtmlHelper->addStockShipmentGridColumn( $block );
						} 
else {
							if ($block instanceof Mage_Adminhtml_Block_Sales_Creditmemo_Grid) {
								$adminhtmlHelper->addStockCreditmemoGridColumn( $block );
							} 
else {
								if ($block instanceof Mage_Adminhtml_Block_Report_Product_Lowstock_Grid) {
									$adminhtmlHelper->addQtyProductLowstockGridColumns( $block );
								}
							}
						}
					}
				}
			}

			return $this;
		}

		/**
     * Prepare grid
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Observer
     */
		function prepareGrid($observer) {
			$block = $observer->getEvent(  )->getBlock(  );

			if (!$block) {
				return $this;
			}

			$adminhtmlHelper = $this->getWarehouseHelper(  )->getAdminhtmlHelper(  );

			if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Grid) {
				$adminhtmlHelper->prepareProductGrid( $block );
			} 
else {
				if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
					$adminhtmlHelper->prepareOrderGrid( $block );
				} 
else {
					if ($block instanceof Mage_Adminhtml_Block_Sales_Invoice_Grid) {
						$adminhtmlHelper->prepareInvoiceGrid( $block );
					} 
else {
						if ($block instanceof Mage_Adminhtml_Block_Sales_Shipment_Grid) {
							$adminhtmlHelper->prepareShipmentGrid( $block );
						} 
else {
							if ($block instanceof Mage_Adminhtml_Block_Sales_Creditmemo_Grid) {
								$adminhtmlHelper->prepareCreditmemoGrid( $block );
							} 
else {
								if ($block instanceof Mage_Adminhtml_Block_Report_Product_Lowstock_Grid) {
									$adminhtmlHelper->prepareProductLowstockGrid( $block );
								}
							}
						}
					}
				}
			}

			return $this;
		}

		/**
     * Before load product collection
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Observer
     */
		function beforeLoadProductCollection($observer) {
			$observer->getEvent(  )->getCollection(  );

			if (!$collection) {
				return $this;
			}

			$adminhtmlHelper = $collection = $this->getWarehouseHelper(  )->getAdminhtmlHelper(  );

			if ($collection instanceof Mage_Reports_Model_Resource_Product_Lowstock_Collection) {
				$adminhtmlHelper->beforeLoadProductLowstockCollection( $collection );
			}

			return $this;
		}

		/**
     * Process order create data
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_Observer
     */
		function processOrderCreateData($observer) {
			$event = $observer->getEvent(  );
			$orderCreateModel = $event->getOrderCreateModel(  );
			$request = $event->getRequest(  );

			if (( !$orderCreateModel || !$request )) {
				return $this;
			}


			if (( isset( $request['reset_items'] ) && $request['reset_items'] )) {
				$orderCreateModel->resetQuoteItems(  );
			}

			return $this;
		}
	}

?>