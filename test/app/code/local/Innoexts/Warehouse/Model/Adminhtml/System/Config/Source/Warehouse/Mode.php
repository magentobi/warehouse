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

	class Innoexts_Warehouse_Model_Adminhtml_System_Config_Source_Warehouse_Mode {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Options getter
     * 
     * @return array
     */
		function toOptionArray() {
			$helper = $this->getWarehouseHelper(  );
			return array( array( 'value' => 'single', 'label' => $helper->__( 'Single' ) ), array( 'value' => 'multiple', 'label' => $helper->__( 'Multiple' ) ) );
		}
	}

?>