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

	class Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price extends Mage_Core_Model_Mysql4_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'catalog/product_zone_price', 'zone_price_id' );
		}

		/**
     * Load product zone price by request
     * 
     * @param Innoexts_WarehousePlus_Model_Catalog_Product_Zone_Price $productZonePrice
     * @param Varien_Object $request
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price
     */
		function loadByRequest($productZonePrice, $request) {
			$adapter = $this->_getReadAdapter(  );
			$select = $adapter->select(  )->from( $this->getMainTable(  ) );
			$conditions = array(  );

			if ($request->getId(  )) {
				array_push( $conditions, '(zone_price_id != ' . $adapter->quote( $request->getId(  ) ) . ')' );
			}

			$countryId = ($request->getCountryId(  ) ? $request->getCountryId(  ) : '0');
			$regionId = ($request->getRegionId(  ) ? $request->getRegionId(  ) : '0');
			$zip = ($request->getZip(  ) ? $request->getZip(  ) : '');
			array_push( $conditions, '(product_id = ' . $adapter->quote( $request->getProductId(  ) ) . ')' );
			array_push( $conditions, '(country_id = ' . $adapter->quote( $countryId ) . ')' );
			array_push( $conditions, '(region_id = ' . $adapter->quote( $regionId ) . ')' );
			array_push( $conditions, '(zip = ' . $adapter->quote( $zip ) . ')' );
			$select->where( implode( ' AND ', $conditions ) );
			$select->limit( 1 );
			$row = $adapter->fetchRow( $select );

			if (( $row && !empty( $$row ) )) {
				$productZonePrice->setData( $row );
			}

			$this->_afterLoad( $productZonePrice );
			return $this;
		}

		/**
     * Load product zone price by product identifier and address
     * 
     * @param Innoexts_WarehousePlus_Model_Catalog_Product_Zone_Price $productZonePrice
     * @param int $productId
     * @param Varien_Object $address
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price
     */
		function loadByProductIdAndAddress($productZonePrice, $productId, $address) {
			$adapter = $this->_getReadAdapter(  );
			$bind = array( ':product_id' => $productId, ':country_id' => $address->getCountryId(  ), ':region_id' => $address->getRegionId(  ), ':postcode' => $address->getPostcode(  ) );
			$table = 'pzp';
			$productId = $table . '.product_id';
			$countryId = $table . '.country_id';
			$regionId = $table . '.region_id';
			$isZipRange = $table . '.is_zip_range';
			$zip = $table . '.zip';
			$fromZip = $table . '.from_zip';
			$toZip = $table . '.to_zip';
			$countryIdOrder = $countryId . ' DESC';
			$regionIdOrder = $regionId . ' DESC';
			$zipOrder = '(IF (' . $isZipRange . ' = \'0\', IF ((' . $zip . ' IS NULL) OR (' . $zip . ' = \'\'), 3, 1), 2)) ASC';
			$select = $adapter->select(  )->from( array( $table => $this->getMainTable(  ) ) )->order( array( $countryIdOrder, $regionIdOrder, $zipOrder ) )->limit( 1 );
			$productIdWhere = $productId . ' = :product_id';
			$countryIdWhere = $countryId . ' = :country_id';
			$countryIdEmptyWhere = $countryId . ' = \'0\'';
			$regionIdWhere = $regionId . ' = :region_id';
			$regionIdEmptyWhere = $regionId . ' = \'0\'';
			$zipWhere = '(IF (' . $isZipRange . ' <> \'0\', (:postcode >= ' . $fromZip . ') AND (:postcode <= ' . $toZip . '), ' . $zip . ' = :postcode))';
			$zipEmptyWhere = '((' . $isZipRange . ' = \'0\') AND ((' . $zip . ' IS NULL) OR (' . $zip . ' = \'\')))';
			$where = '(' . implode( ') OR (', array( $countryIdWhere . ' AND ' . $regionIdWhere . ' AND ' . $zipWhere, $countryIdWhere . ' AND ' . $regionIdWhere . ' AND ' . $zipEmptyWhere, $countryIdWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipEmptyWhere, $countryIdWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipWhere, $countryIdEmptyWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipEmptyWhere ) ) . ')';
			$select->where( $productIdWhere );
			$select->where( $where );
			$data = $adapter->fetchRow( $select, $bind );
			$productZonePrice->setData( $data );
			$this->_afterLoad( $productZonePrice );
			return $this;
		}
	}

?>