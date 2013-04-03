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

	class Innoexts_ShippingTablerate_Model_Tablerate extends Innoexts_Core_Model_Area_Abstract {
		private $_eventPrefix = 'shippingtablerate_tablerate';
		private $_eventObject = 'tablerate';
		private $_cacheTag = 'shippingtablerate_tablerate';

		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'shippingtablerate/tablerate' );
		}

		/**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getTextHelper() {
			return Mage::helper( 'shippingtablerate' );
		}

		/**
     * Get shortened notes
     * 
     * @param int $maxLength
     * 
     * @return string
     */
		function getShortNote($maxLength = 50) {
			$string = Mage::helper( 'core/string' );
			$note = $this->getData( 'note' );
			return ($maxLength < $string->strlen( $note ) ? $string->substr( $note, 0, $maxLength ) . '...' : $note);
		}

		/**
     * Filter condition name
     * 
     * @param mixed $value
     * 
     * @return string
     */
		function filterConditionName($value) {
			$values = Mage::getSingleton( 'shipping/carrier_tablerate' )->getCode( 'condition_name' );
			return (isset( $values[$value] ) ? $value : null);
		}

		/**
     * Get condition name filter
     * 
     * @return Zend_Filter
     */
		function getConditionNameFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterConditionName' ) ) ) );
		}

		/**
     * Get condition value filter
     * 
     * @return Zend_Filter
     */
		function getConditionValueFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterFloat' ) ) ) );
		}

		/**
     * Get price filter
     * 
     * @return Zend_Filter
     */
		function getPriceFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterFloat' ) ) ) );
		}

		/**
     * Get cost filter
     * 
     * @return Zend_Filter
     */
		function getCostFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterFloat' ) ) ) );
		}

		/**
     * Get filters
     * 
     * @return array
     */
		function getFilters() {
			return array( 'dest_country_id' => $this->getCountryFilter(  ), 'dest_region_id' => $this->getRegionFilter( 'dest_country_id' ), 'dest_zip' => $this->getZipFilter(  ), 'condition_name' => $this->getConditionNameFilter(  ), 'condition_value' => $this->getConditionValueFilter(  ), 'price' => $this->getPriceFilter(  ), 'cost' => $this->getCostFilter(  ), 'note' => $this->getTextFilter(  ) );
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			return array( 'dest_country_id' => $this->getTextValidator( false, 0, 4 ), 'dest_region_id' => $this->getIntegerValidator( false, 0 ), 'dest_zip' => $this->getTextValidator( false, 0, 10 ), 'condition_name' => $this->getTextValidator( true, 0, 20 ), 'condition_value' => $this->getFloatValidator( false, 0 ), 'price' => $this->getFloatValidator( false, 0 ), 'cost' => $this->getFloatValidator( false, 0 ), 'note' => $this->getTextValidator( false, 0, 512 ) );
		}

		/**
     * Validate catalog inventory stock
     * 
     * @throws Mage_Core_Exception
     * 
     * @return bool
     */
		function validate() {
			$helper = $this->getTextHelper(  );
			parent::validate(  );
			$errorMessages = array(  );
			$tablerate = Mage::getModel( 'shippingtablerate/tablerate' )->loadByRequest( $this );

			if ($tablerate->getId(  )) {
				array_push( $errorMessages, $helper->__( 'Duplicate rate.' ) );
			}


			if (count( $errorMessages )) {
				Mage::throwException( join( '
', $errorMessages ) );
			}

			return true;
		}

		/**
     * Get title
     * 
     * @return string
     */
		function getTitle() {
			$conditionNames = $title = parent::getTitle(  );
			$conditionName = $this->getConditionName(  );
			$conditionName = (isset( $conditionNames[$conditionName] ) ? $conditionNames[$conditionName] : '');
			$conditionValue = $this->getConditionValue(  );
			implode( ', ', array( $title, ($conditionName ? $conditionName : ''), ($conditionValue ? floatval( $conditionValue ) : '0') ) );
			$title = Mage::getSingleton( 'shipping/carrier_tablerate' )->getCode( 'condition_name' );
			return $title;
		}

		/**
     * Load table rate by request
     * 
     * @param Varien_Object $request
     * 
     * @return Innoexts_ShippingTablerate_Model_Tablerate
     */
		function loadByRequest($request) {
			$this->_getResource(  )->loadByRequest( $this, $request );
			$this->setOrigData(  );
			return $this;
		}
	}

?>