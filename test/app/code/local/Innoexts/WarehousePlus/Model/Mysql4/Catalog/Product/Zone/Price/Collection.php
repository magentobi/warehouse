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

	class Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
		private $_countryTable = null;
		private $_regionTable = null;

		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'catalog/product_zone_price' );
			$this->_countryTable = $this->getTable( 'directory/country' );
			$this->_regionTable = $this->getTable( 'directory/country_region' );
		}

		/**
     * Initialize select, add country iso3 code and region name
     *
     * @return void
     */
		function _initSelect() {
			parent::_initSelect(  );
			$this->getSelect(  )->joinLeft( array( 'country_table' => $this->_countryTable ), 'country_table.country_id = main_table.country_id', array( 'country' => 'iso2_code' ) )->joinLeft( array( 'region_table' => $this->_regionTable ), 'region_table.region_id = main_table.region_id', array( 'region' => 'code' ) );
		}

		/**
     * Set product filter to collection
     *
     * @param int $productId
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price_Collection
     */
		function setProductFilter($productId) {
			return $this->addFieldToFilter( 'product_id', $productId );
		}

		/**
     * Set products filter to collection
     *
     * @param array $products
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price_Collection
     */
		function setProductsFilter($products) {
			$productIds = array(  );
			foreach ($products as $product) {

				if ($product instanceof Mage_Catalog_Model_Product) {
					$productIds[] = $product->getId(  );
					continue;
				}

				$productIds[] = $product;
			}


			if (empty( $$productIds )) {
				$productIds[] = false;
				$this->_setIsLoaded( true );
			}

			$this->addFieldToFilter( 'main_table.product_id', array( 'in' => $productIds ) );
			return $this;
		}

		/**
     * Set address filter to collection
     *
     * @param Varien_Object $address
     * 
     * @return Innoexts_WarehousePlus_Model_Mysql4_Catalog_Product_Zone_Price_Collection
     */
		function setAddressFilter($address) {
			$select = $this->getConnection(  )->select(  );
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
			$select->from( array( $table => $this->getMainTable(  ) ), 'zone_price_id' )->order( array( $countryIdOrder, $regionIdOrder, $zipOrder ) )->limit( 1 );
			$countryIdWhere = $countryId . ' = :country_id';
			$countryIdEmptyWhere = $countryId . ' = \'0\'';
			$regionIdWhere = $regionId . ' = :region_id';
			$regionIdEmptyWhere = $regionId . ' = \'0\'';
			$zipWhere = '(IF (' . $isZipRange . ' <> \'0\', (:postcode >= ' . $fromZip . ') AND (:postcode <= ' . $toZip . '), ' . $zip . ' = :postcode))';
			$zipEmptyWhere = '((' . $isZipRange . ' = \'0\') AND ((' . $zip . ' IS NULL) OR (' . $zip . ' = \'\')))';
			$where = '(' . implode( ') OR (', array( $countryIdWhere . ' AND ' . $regionIdWhere . ' AND ' . $zipWhere, $countryIdWhere . ' AND ' . $regionIdWhere . ' AND ' . $zipEmptyWhere, $countryIdWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipEmptyWhere, $countryIdWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipWhere, $countryIdEmptyWhere . ' AND ' . $regionIdEmptyWhere . ' AND ' . $zipEmptyWhere ) ) . ')';
			$select->where( ( '(' ) . $productId . ' = main_table.product_id) AND (' . $where . ')' );
			$this->getSelect(  )->where( 'main_table.zone_price_id = (' . $select->assemble(  ) . ')' );
			$this->addBindParam( ':country_id', $address->getCountryId(  ) );
			$this->addBindParam( ':region_id', $address->getRegionId(  ) );
			$this->addBindParam( ':postcode', $address->getPostcode(  ) );
			return $this;
		}
	}

?>