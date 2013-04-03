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

	class Innoexts_Warehouse_Model_Mysql4_Sales_Quote_Address extends Mage_Sales_Model_Mysql4_Quote_Address {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Perform actions before object save
     *
     * @param Mage_Core_Model_Abstract $object
     */
		function _beforeSave($object) {
			parent::_beforeSave( $object );

			if (( !$this->getWarehouseHelper(  )->getConfig(  )->isMultipleMode(  ) && $object->isShippingAddressType(  ) )) {
				$object->recalculateStockId(  );
			}

		}
	}

?>