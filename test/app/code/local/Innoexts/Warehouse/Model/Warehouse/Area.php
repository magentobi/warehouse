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

	class Innoexts_Warehouse_Model_Warehouse_Area extends Innoexts_Core_Model_Area_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'warehouse/warehouse_area' );
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
     * Get filters
     * 
     * @return array
     */
		function getFilters() {
			$filters = array( 'country_id' => $this->getCountryFilter(  ), 'region_id' => $this->getRegionFilter( 'country_id' ), 'is_zip_range' => $this->getTextFilter(  ), 'zip' => $this->getZipFilter(  ), 'from_zip' => $this->getTextFilter(  ), 'to_zip' => $this->getTextFilter(  ) );
			return $filters;
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			$helper = $this->getWarehouseHelper(  );
			$validators = array( 'country_id' => $this->getTextValidator( false, 0, 4 ), 'region_id' => $this->getIntegerValidator( false, 0 ), 'is_zip_range' => $this->getIntegerValidator( false, 0 ) );
			$isZipRange = $this->getIsZipRange(  );

			if ($isZipRange) {
				$maxZipValue = 9999999999;
				$fromZip = (int)$this->getFromZip(  );
				$validators['from_zip'] = $this->getIntegerValidator( true, 1, $maxZipValue );
				$validators['to_zip'] = $this->getIntegerValidator( true, $fromZip, $maxZipValue );
			} 
else {
				$validators['zip'] = $this->getTextValidator( false, 0, 10 );
			}

			return $validators;
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

			if (parent::validate(  )) {
				$isZipRange = $this->getIsZipRange(  );

				if ($isZipRange) {
					$this->setZip( $this->getFromZip(  ) . '-' . $this->getToZip(  ) );
				} 
else {
					$this->setFromZip( null );
					$this->setToZip( null );
				}

				$errorMessages = array(  );
				$warehouseArea = Mage::getModel( 'warehouse/warehouse_area' )->loadByRequest( $this );

				if ($warehouseArea->getId(  )) {
					array_push( $errorMessages, $helper->__( 'Duplicate area.' ) );
				}


				if (count( $errorMessages )) {
					Mage::throwException( join( '
', $errorMessages ) );
				}

				return true;
			}

			return false;
		}

		/**
     * Processing object before save data
     *
     * @return Innoexts_Warehouse_Model_Warehouse_Area
     */
		function _beforeSave() {
			$this->filter(  );
			$this->validate(  );
			return parent::_beforeSave(  );
		}

		/**
     * Load warehouse area by request
     * 
     * @param Varien_Object $request
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Area
     */
		function loadByRequest($request) {
			$this->_getResource(  )->loadByRequest( $this, $request );
			$this->setOrigData(  );
			return $this;
		}
	}

?>