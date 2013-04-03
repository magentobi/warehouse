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

	class Innoexts_Warehouse_Model_Warehouse extends Innoexts_Core_Model_Abstract {
		private $_eventPrefix = 'warehouse';
		private $_eventObject = 'warehouse';
		private $_cacheTag = 'warehouse';
		private $_warehouses = null;

		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'warehouse/warehouse' );
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
     * Get address helper
     * 
     * @return Innoexts_Core_Helper_Address
     */
		function getAddressHelper() {
			return $this->getCoreHelper(  )->getAddressHelper(  );
		}

		/**
     * Filter country
     * 
     * @param mixed $value
     * @return string
     */
		function filterCountry($country) {
			if ($country) {
				$country = $this->getAddressHelper(  )->castCountryId( $country );

				if ($country) {
					return $country;
				}
			}

			return '0';
		}

		/**
     * Get country filter
     * 
     * @return Zend_Filter
     */
		function getCountryFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterCountry' ) ) ) );
		}

		/**
     * Get filters
     * 
     * @return array
     */
		function getFilters() {
			return array( 'code' => $this->getTextFilter(  ), 'title' => $this->getTextFilter(  ), 'description' => $this->getTextFilter(  ), 'priority' => $this->getIntegerFilter(  ), 'notify' => $this->getIntegerFilter(  ), 'contact_name' => $this->getTextFilter(  ), 'contact_email' => $this->getTextFilter(  ), 'origin_country_id' => $this->getCountryFilter(  ), 'origin_region_id' => $this->getTextFilter(  ), 'origin_postcode' => $this->getTextFilter(  ), 'origin_city' => $this->getTextFilter(  ) );
		}

		/**
     * Get code validator
     * 
     * @return Zend_Validate
     */
		function getCodeValidator() {
			$helper = $this->getWarehouseHelper(  );
			$validator = new Zend_Validate_Regex( array( 'pattern' => '/^[a-z]+[a-z0-9_]*$/' ) );
			$validator->setMessage( $helper->__( 'Warehouse code may only contain letters (a-z), numbers (0-9) or underscore(_), the first character must be a letter' ), NOT_MATCH );
			return $this->getTextValidator( true, 0, 32 )->addValidator( $validator );
		}

		/**
     * Get contact email validator
     * 
     * @return Zend_Validate
     */
		function getContactEmailValidator() {
			$validator = $this->getTextValidator( false, 0, 64 );

			if ($this->getContactEmail(  )) {
				( new Zend_Validate_EmailAddress(  ) );
			}

			return $validator;
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			return array( 'code' => $this->getCodeValidator(  ), 'title' => $this->getTextValidator( true, 0, 128 ), 'description' => $this->getTextValidator( false, 0, 512 ), 'priority' => $this->getIntegerValidator( false, 0 ), 'notify' => $this->getIntegerValidator( false, 0 ), 'contact_name' => $this->getTextValidator( false, 0, 64 ), 'contact_email' => $this->getContactEmailValidator(  ), 'origin_country_id' => $this->getTextValidator( true, 0, 4 ), 'origin_region_id' => $this->getTextValidator( true, 0, 100 ), 'origin_postcode' => $this->getTextValidator( true, 0, 50 ), 'origin_city' => $this->getTextValidator( true, 0, 100 ) );
		}

		/**
     * Validate catalog inventory stock
     *
     * @throws Mage_Core_Exception
     * 
     * @return bool
     */
		function validate() {
			$helper = $this->getWarehouseHelper(  );
			parent::validate(  );
			$errorMessages = array(  );
			$warehouse = Mage::getModel( 'warehouse/warehouse' )->loadByCode( $this->getCode(  ), $this->getId(  ) );

			if ($warehouse->getId(  )) {
				array_push( $errorMessages, $helper->__( 'Warehouse with the same code already exists.' ) );
			}

			$warehouse = Mage::getModel( 'warehouse/warehouse' )->loadByTitle( $this->getTitle(  ), $this->getId(  ) );

			if ($warehouse->getId(  )) {
				array_push( $errorMessages, $helper->__( 'Warehouse with the same title already exists.' ) );
			}


			if (count( $errorMessages )) {
				Mage::throwException( join( '
', $errorMessages ) );
			}

			return true;
		}

		/**
     * Preset stock id
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function presetStockId() {
			$stock = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStock(  );
			$stockId = $this->getStockId(  );

			if ($stockId) {
				$stock->load( $stockId );
			}

			$stock->setStockName( $this->getTitle(  ) );
			$stock->save(  );
			$this->setStockId( $stock->getId(  ) );
		}

		/**
     * Delete stock
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function deleteStock() {
			$stockId = $this->getStockId(  );

			if ($stockId) {
				$stock = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStock(  );
				$stock->load( $stockId );
				$stock->delete(  );
			}

			return $this;
		}

		/**
     * Preset origin region
     *
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function presetOriginRegion() {
			$regionId = $this->getOriginRegionId(  );

			if ($regionId) {
				if (is_numeric( $regionId )) {
					$region = $this->getAddressHelper(  )->getRegionById( $regionId );
					$this->setOriginRegionId( $region->getId(  ) );
					$this->setOriginRegion( $region->getName(  ) );
				} 
else {
					$this->setOriginRegionId( null );
					$this->setOriginRegion( $regionId );
				}
			} 
else {
				$this->setOriginRegionId( null );
			}

			return $this;
		}

		/**
     * Processing object before save data
     *
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function _beforeSave() {
			$regionId = ($this->getOriginRegionId(  ) ? $this->getOriginRegionId(  ) : $this->getOriginRegion(  ));
			$this->setOriginRegionId( $regionId );
			$this->filter(  );
			$this->validate(  );
			parent::_beforeSave(  );
			$this->presetStockId(  );
			$this->presetOriginRegion(  );
			return $this;
		}

		/**
     * Processing object after save data
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function _afterSave() {
			parent::_afterSave(  );
			return $this;
		}

		/**
     * Processing object before delete data
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function _beforeDelete() {
			if ($this->getWarehouseHelper(  )->getDefaultStockId(  ) == $this->getStockId(  )) {
				$helper = $this->getWarehouseHelper(  );
				Mage::throwException( $helper->__( 'The default warehouse can\'t be deleted.' ) );
			}

			return parent::_beforeDelete(  );
		}

		/**
     * Processing object after delete data
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function _afterDelete() {
			$this->deleteStock(  );
			return parent::_afterDelete(  );
		}

		/**
     * Get origin
     * 
     * @return Varien_Object
     */
		function getOrigin() {
			$this->hasData(  );
			$origin = new Varien_Object(  );

			if ($this->hasOriginCountryId(  )) {
				$origin->setCountryId( $this->getOriginCountryId(  ) );
			}


			if ($this->hasOriginRegionId(  )) {
				$origin->setRegionId( $this->getOriginRegionId(  ) );
			}


			if ($this->hasOriginRegion(  )) {
				$origin->setRegion( $this->getOriginRegion(  ) );
			}


			if ($this->hasOriginPostcode(  )) {
				$origin->setPostcode( $this->getOriginPostcode(  ) );
			}


			if ($this->hasOriginCity(  )) {
				$origin->setCity( $this->getOriginCity(  ) );
			}

			return $origin;
		}

		/**
     * Get origin string
     * 
     * @return string
     */
		function getOriginString() {
			if (!$this->hasData( 'origin_string' )) {
				$this->setData( 'origin_string', $this->getWarehouseHelper(  )->getAddressHelper(  )->format( $this->getOrigin(  ) ) );
			}

			return $this->getData( 'origin_string' );
		}

		/**
     * Check if notification enabled
     * 
     * @return bool
     */
		function isNotify() {
			return ($this->getNotify(  ) ? true : false);
		}

		/**
     * Check if contact information is set
     * 
     * @return bool
     */
		function isContactSet() {
			return (( $this->getContactName(  ) && $this->getContactEmail(  ) ) ? true : false);
		}

		/**
     * Load warehouse by code
     * 
     * @param string $code
     * @param int $exclude
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function loadByCode($code, $exclude = null) {
			$this->_getResource(  )->loadByCode( $this, $code, $exclude );
			$this->setOrigData(  );
			return $this;
		}

		/**
     * Load warehouse by title
     * 
     * @param string $title
     * @param int $exclude
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function loadByTitle($title, $exclude = null) {
			$this->_getResource(  )->loadByTitle( $this, $title, $exclude );
			$this->setOrigData(  );
			return $this;
		}

		/**
     * Get stores identifiers
     * 
     * @return array
     */
		function getStores() {
			if (is_null( $this->getData( 'stores' ) )) {
				$this->setData( 'stores', $this->_getResource(  )->getStores( $this ) );
			}

			return $this->getData( 'stores' );
		}

		/**
     * Get shipping carriers
     * 
     * @return array
     */
		function getShippingCarriers() {
			if (is_null( $this->getData( 'shipping_carriers' ) )) {
				$this->setData( 'shipping_carriers', $this->_getResource(  )->getShippingCarriers( $this ) );
			}

			return $this->getData( 'shipping_carriers' );
		}
	}

?>