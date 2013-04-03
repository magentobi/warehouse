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

	class Innoexts_Warehouse_Helper_Customer extends Mage_Core_Helper_Abstract {
		private $_groups = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get customer groups
     * 
     * @return array of Mage_Customer_Model_Group
     */
		function getGroups() {
			if (is_null( $this->_groups )) {
				$this->_groups = Mage::getModel( 'customer/group' )->getResourceCollection(  )->load(  );
			}

			return $this->_groups;
		}

		/**
     * Get session
     * 
     * @return Mage_Customer_Model_Session
     */
		function getSession() {
			return Mage::getSingleton( 'customer/session' );
		}

		/**
     * Get customer group id
     * 
     * @return int
     */
		function getCustomerGroupId() {
			return $this->getSession(  )->getCustomerGroupId(  );
		}
	}

?>