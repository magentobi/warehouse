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

	class Innoexts_Warehouse_Model_Adminhtml_System_Config_Backend_Warehouse_Displaydistance extends Mage_Core_Model_Config_Data {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * After commit callback
     * 
     * @return Innoexts_Warehouse_Model_Adminhtml_System_Config_Backend_Warehouse_Displaydistance
     */
		function afterCommitCallback() {
			parent::afterCommitCallback(  );

			if (( $this->isValueChanged(  ) && $this->getValue(  ) )) {
				$this->getWarehouseHelper(  )->updateWarehousesCoordinates(  );
			}

			return $this;
		}
	}

?>