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

	class Innoexts_WarehousePlus_Model_Catalog_Product_Zone_Price extends Innoexts_Core_Model_Area_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'catalog/product_zone_price' );
		}

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
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
			$filters = array( 'country_id' => $this->getCountryFilter(  ), 'region_id' => $this->getRegionFilter( 'country_id' ), 'is_zip_range' => $this->getTextFilter(  ), 'zip' => $this->getZipFilter(  ), 'from_zip' => $this->getTextFilter(  ), 'to_zip' => $this->getTextFilter(  ), 'price' => $this->getTextFilter(  ), 'price_type' => $this->getTextFilter(  ) );
			return $filters;
		}

		/**
     * Validate range
     * 
     * @param mixed $value
     * @param mixed $min
     * @param mixed $max
     * 
     * @return boolean
     */
		function validatePriceType($value) {
			if (in_array( $value, array( 'fixed', 'percent' ) )) {
				return true;
			}

			return false;
		}

		/**
     * Get price type validator
     * 
     * @return Zend_Validate
     */
		function getPriceTypeValidator() {
			$validator = $this->getTextValidator( true );
			( new Zend_Validate_NotEmpty( STRING ), true );
			( new Zend_Validate_Callback( array( 'callback' => array( $this, 'validatePriceType' ) ) ), true );
			return $validator;
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			$helper = $this->getWarehouseHelper(  );
			$validators = array( 'country_id' => $this->getTextValidator( false, 0, 4 ), 'region_id' => $this->getIntegerValidator( false, 0 ), 'price' => $this->getFloatValidator( true, 0 ), 'price_type' => $this->getPriceTypeValidator(  ), 'is_zip_range' => $this->getIntegerValidator( false, 0 ) );
			$isZipRange = $this->getIsZipRange(  );

			if ($isZipRange) {
				$maxZipValue = $helper->getConfig(  )->getMaxZipValue(  );
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
     * Validate product zone price
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
				$productZonePrice = Mage::getModel( 'catalog/product_zone_price' )->loadByRequest( $this );

				if ($productZonePrice->getId(  )) {
					array_push( $errorMessages, $helper->__( 'Duplicated price.' ) );
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
     * Load product zone price by product identifier and address
     * 
     * @param int $productId
     * @param Varien_Object $address
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Zone_Price
     */
		function loadByProductIdAndAddress($productId, $address) {
			$this->_getResource(  )->loadByProductIdAndAddress( $this, $productId, $address );
			$this->setOrigData(  );
			return $this;
		}

		/**
     * Load product zone price by request
     * 
     * @param Varien_Object $request
     * 
     * @return Innoexts_WarehousePlus_Model_Catalog_Product_Zone_Price
     */
		function loadByRequest($request) {
			$this->_getResource(  )->loadByRequest( $this, $request );
			$this->setOrigData(  );
			return $this;
		}

		/**
     * Check if fixed price type
     * 
     * @return bool
     */
		function isFixedPriceType() {
			return ($this->getPriceType(  ) == 'fixed' ? true : false);
		}

		/**
     * Get final price
     * 
     * @param float $price
     * 
     * @return float
     */
		function getFinalPrice($price) {
			if ($this->isFixedPriceType(  )) {
				return ($this->getPrice(  ) < $price ? round( $price - $this->getPrice(  ), 4 ) : $price);
			}

			return ($this->getPrice(  ) < 100 ? round( $price - $price * ( $this->getPrice(  ) / 100 ), 4 ) : $price);
		}
	}

?>