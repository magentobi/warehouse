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

	class Innoexts_Warehouse_Model_Mysql4_Warehouse_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'warehouse/warehouse' );
		}

		/**
     * Retrieve options array
     * 
     * @param string $valueField
     * 
     * @return array
     */
		function toOptionArray($valueField = 'warehouse_id') {
			return $this->_toOptionArray( $valueField, 'title' );
		}

		/**
     * Retrieve options hash array
     * 
     * @param string $valueField
     * 
     * @return array
     */
		function toOptionHash($valueField = 'warehouse_id') {
			return $this->_toOptionHash( $valueField, 'title' );
		}

		/**
     * Before load
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
		function _beforeLoad() {
			Mage::dispatchEvent( 'warehouse_collection_load_before', array( 'collection' => $this ) );
			return parent::_beforeLoad(  );
		}

		/**
     * After load
     *
     * @return Mage_Core_Model_Resource_Db_Collection_Abstract
     */
		function _afterLoad() {
			if (0 < count( $this )) {
				Mage::dispatchEvent( 'warehouse_collection_load_after', array( 'collection' => $this ) );
			}

			return $this;
		}
	}

?>