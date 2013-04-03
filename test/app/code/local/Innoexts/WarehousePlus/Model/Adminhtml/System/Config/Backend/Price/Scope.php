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

	class Innoexts_WarehousePlus_Model_Adminhtml_System_Config_Backend_Price_Scope extends Mage_Adminhtml_Model_System_Config_Backend_Price_Scope {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Callback function which called after transaction commit in resource model
     * 
     * @return Innoexts_WarehousePlus_Model_Adminhtml_System_Config_Backend_Price_Scope
     */
		function afterCommitCallback() {
			parent::afterCommitCallback(  );

			if ($this->isValueChanged(  )) {
				$helper = $this->getWarehouseHelper(  );
				$processHelper = $helper->getProcessHelper(  );
				$helper->getProductPriceHelper(  )->changeScope( $this->getValue(  ) );
				$processHelper->reindexProductPrice(  );
				$processHelper->reindexProductFlat(  );
				$processHelper->reindexSearchFlat(  );
			}

			return $this;
		}
	}

?>