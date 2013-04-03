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

	class Innoexts_Warehouse_Model_Rss_Observer {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Convert order item to quote item
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_Warehouse_Model_Rss_Observer
     */
		function prepareCatalogNotifyStockCollectionSelect($observer) {
			$event = $observer->getEvent(  );
			$collection = $event->getCollection(  );

			if (!$collection) {
				return $this;
			}

			$collection->getSelect(  )->distinct( true );
			return $this;
		}
	}

?>