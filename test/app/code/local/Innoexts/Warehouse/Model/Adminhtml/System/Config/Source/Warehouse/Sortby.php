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

	class Innoexts_Warehouse_Model_Adminhtml_System_Config_Source_Warehouse_Sortby {
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
			return array( array( 'value' => 'id', 'label' => $helper->__( 'ID' ) ), array( 'value' => 'code', 'label' => $helper->__( 'Code' ) ), array( 'value' => 'title', 'label' => $helper->__( 'Title' ) ), array( 'value' => 'priority', 'label' => $helper->__( 'Priority' ) ), array( 'value' => 'origin', 'label' => $helper->__( 'Origin' ) ) );
		}
	}

?>