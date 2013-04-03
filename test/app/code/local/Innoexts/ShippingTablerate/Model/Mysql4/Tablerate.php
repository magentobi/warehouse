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

	class Innoexts_ShippingTablerate_Model_Mysql4_Tablerate extends Mage_Core_Model_Mysql4_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'shippingtablerate/tablerate', 'pk' );
		}

		/**
     * Perform actions before object save
     *
     * @param Mage_Core_Model_Abstract $object
     */
		function _beforeSave($object) {
			parent::_beforeSave( $object );
			return $this;
		}

		/**
     * Load table rate by request
     * 
     * @param Innoexts_ShippingTablerate_Model_Tablerate $tablerate
     * @param Varien_Object $request
     * 
     * @return Innoexts_ShippingTablerate_Model_Mysql4_Tablerate
     */
		function loadByRequest($tablerate, $request) {
			$adapter = $this->_getReadAdapter(  );
			$select = $adapter->select(  )->from( $this->getMainTable(  ) );
			$conditions = array(  );

			if ($request->getId(  )) {
				array_push( $conditions, '(pk != ' . $adapter->quote( $request->getId(  ) ) . ')' );
			}

			$websiteId = ($request->getWebsiteId(  ) ? $request->getWebsiteId(  ) : '0');
			$destCountryId = ($request->getDestCountryId(  ) ? $request->getDestCountryId(  ) : '0');
			$destRegionId = ($request->getDestRegionId(  ) ? $request->getDestRegionId(  ) : '0');
			$destZip = ($request->getDestZip(  ) ? $request->getDestZip(  ) : '');
			$conditionName = ($request->getConditionName(  ) ? $request->getConditionName(  ) : '');
			$conditionValue = ($request->getConditionValue(  ) ? $request->getConditionValue(  ) : '');
			array_push( $conditions, '(website_id = ' . $adapter->quote( $websiteId ) . ')' );
			array_push( $conditions, '(dest_country_id = ' . $adapter->quote( $destCountryId ) . ')' );
			array_push( $conditions, '(dest_region_id = ' . $adapter->quote( $destRegionId ) . ')' );
			array_push( $conditions, '(dest_zip = ' . $adapter->quote( $destZip ) . ')' );
			array_push( $conditions, '(condition_name = ' . $adapter->quote( $conditionName ) . ')' );
			array_push( $conditions, '(condition_value = ' . $adapter->quote( $conditionValue ) . ')' );
			$select->where( implode( ' AND ', $conditions ) );
			$select->limit( 1 );
			$row = $adapter->fetchRow( $select );

			if (( $row && !empty( $$row ) )) {
				$tablerate->setData( $row );
			}

			$this->_afterLoad( $tablerate );
			return $this;
		}
	}

?>