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

	class Innoexts_Warehouse_Model_Mysql4_Warehouse extends Mage_Core_Model_Mysql4_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'warehouse/warehouse', 'warehouse_id' );
		}

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

			if (( !$object->getId(  ) || !$object->getCreatedAt(  ) )) {
				$object->setCreatedAt( Mage::getSingleton( 'core/date' )->gmtDate(  ) );
			}

			$object->setUpdatedAt( Mage::getSingleton( 'core/date' )->gmtDate(  ) );
			return $this;
		}

		/**
     * Perform actions after object save
     *
     * @param Mage_Core_Model_Abstract $object
     */
		function _afterSave($object) {
			parent::_afterSave( $object );
			return $this;
		}

		/**
     * Load warehouse by code
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param string $code
     * @param int $exclude
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Warehouse
     */
		function loadByCode($warehouse, $code, $exclude = null) {
			$adapter = $this->_getReadAdapter(  );
			$select = $adapter->select(  )->from( $this->getMainTable(  ) );
			$select->where( 'code = ?', $code );

			if ($exclude) {
				$select->where( 'warehouse_id <> ?', $exclude );
			}

			$row = $adapter->fetchRow( $select );

			if (( $row && !empty( $$row ) )) {
				$warehouse->setData( $row );
			}

			$this->_afterLoad( $warehouse );
			return $this;
		}

		/**
     * Load warehouse by title
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * @param string $title
     * @param int $exclude
     * 
     * @return Innoexts_Warehouse_Model_Mysql4_Warehouse
     */
		function loadByTitle($warehouse, $title, $exclude = null) {
			$adapter = $this->_getReadAdapter(  );
			$select = $adapter->select(  )->from( $this->getMainTable(  ) );
			$select->where( 'title = ?', $title );

			if ($exclude) {
				$select->where( 'warehouse_id <> ?', $exclude );
			}

			$row = $adapter->fetchRow( $select );

			if (( $row && !empty( $$row ) )) {
				$warehouse->setData( $row );
			}

			$this->_afterLoad( $warehouse );
			return $this;
		}

		/**
     * Get write connection
     * 
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
		function getWriteConnection() {
			return $this->_getWriteAdapter(  );
		}

		/**
     * Get stores
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return array
     */
		function getStores($warehouse) {
			$stores = array(  );
			$select = $adapter = $this->_getReadAdapter(  );
			$query = $adapter->select(  )->from( $this->getTable( 'warehouse/warehouse_store' ) )->where( 'warehouse_id = ?', $warehouse->getId(  ) );
			$query->fetch(  );

			if ($row = $adapter->query( $select )) {
				array_push( $stores, $row['store_id'] );
			}

			return $stores;
		}

		/**
     * Get shipping carriers
     * 
     * @param Innoexts_Warehouse_Model_Warehouse $warehouse
     * 
     * @return array
     */
		function getShippingCarriers($warehouse) {
			$this->_getReadAdapter(  );
			$select = $adapter->select(  )->from( $this->getTable( 'warehouse/warehouse_shipping_carrier' ) )->where( 'warehouse_id = ?', $warehouse->getId(  ) );
			$adapter->query( $select );
			$query = $shippingCarriers = array(  );

			if ($row = $adapter = $query->fetch(  )) {
				array_push( $shippingCarriers, $row['shipping_carrier'] );
			}

			return $shippingCarriers;
		}
	}

?>