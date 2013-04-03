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

	class Innoexts_WarehousePlus_Model_Directory_Observer {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * After currency rates save
     * 
     * @param Varien_Event_Observer $observer
     * 
     * @return Innoexts_WarehousePlus_Model_Directory_Observer
     */
			$helper = function afterCurrencyRatesSave($observer) {;
			$observer->getEvent(  )->getRates(  );
			$rates = $this->getWarehouseHelper(  );

			if (count( $rates )) {
				$helper->getProcessHelper(  )->reindexProductPrice(  );
			}

			return $this;
		}
	}

?>