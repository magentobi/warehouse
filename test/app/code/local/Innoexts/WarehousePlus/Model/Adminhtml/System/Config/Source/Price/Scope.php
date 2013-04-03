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

	class Innoexts_WarehousePlus_Model_Adminhtml_System_Config_Source_Price_Scope extends Mage_Adminhtml_Model_System_Config_Source_Price_Scope {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get options array
     * 
     * @return array
     */
		function toOptionArray() {
			return array( array( 'value' => '0', 'label' => Mage::helper( 'core' )->__( 'Global' ) ), array( 'value' => '1', 'label' => Mage::helper( 'core' )->__( 'Website' ) ), array( 'value' => '2', 'label' => $this->getWarehouseHelper(  )->__( 'Store View' ) ) );
		}
	}

?>