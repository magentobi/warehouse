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

	class Innoexts_Core_Model_Abstract extends Mage_Core_Model_Abstract {
		/**
     * Get core helper
     * 
     * @return Innoexts_Core_Helper_Data
     */
		function getCoreHelper() {
			return Mage::helper( 'innoexts_core' );
		}

		/**
     * Create filter chain
     * 
     * @return Zend_Filter
     */
		function createFilterChain() {
			return new Zend_Filter(  );
		}

		/**
     * Create validator chain
     * 
     * @return Zend_Validate
     */
		function createValidatorChain() {
			return new Zend_Validate(  );
		}

		/**
     * Get text filter
     * 
     * @return Zend_Filter
     */
		function getTextFilter() {
			return ( new Zend_Filter_StripTags(  ) );
		}

		/**
     * Filter float
     * 
     * @param mixed $value
     * 
     * @return float
     */
		function filterFloat($value) {
			return (double)(bool)$value;
		}

		/**
     * Filter integer
     * 
     * @param mixed $value
     * 
     * @return integer
     */
		function filterInteger($value) {
			return (int)(bool)$value;
		}

		/**
     * Get float filter
     * 
     * @return Zend_Filter
     */
		function getFloatFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterFloat' ) ) ) );
		}

		/**
     * Get integer filter
     * 
     * @return Zend_Filter
     */
		function getIntegerFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterInteger' ) ) ) );
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
		function validateRange($value, $min = null, $max = null) {
			if (strval( $value ) !== '') {
				if (!is_null( $min )) {
					if ($value < $min) {
						return false;
					}
				}


				if (!is_null( $max )) {
					if ($max < $value) {
						return false;
					}
				}
			}

			return true;
		}

		/**
     * Get text validator
     * 
     * @param boolean $isRequired
     * @param int $minLength
     * @param int $maxLength
     * 
     * @return Zend_Validate
     */
		function getTextValidator($isRequired = false, $minLength = null, $maxLength = null) {
			$validator = $this->createValidatorChain(  );

			if ($isRequired) {
				( new Zend_Validate_NotEmpty( STRING ), true );
			}


			if (( !is_null( $minLength ) || !is_null( $maxLength ) )) {
				$options = array(  );

				if (!is_null( $minLength )) {
					$options['min'] = $minLength;
				}


				if (!is_null( $maxLength )) {
					$options['max'] = $maxLength;
				}

				( new Zend_Validate_StringLength( $options ), true );
			}

			return $validator;
		}

		/**
     * Get integer validator
     * 
     * @param boolean $isRequired
     * @param int $min
     * @param int $max
     * 
     * @return Zend_Validate
     */
		function getIntegerValidator($isRequired = false, $min = null, $max = null) {
			$validator = $this->createValidatorChain(  );

			if ($isRequired) {
				( new Zend_Validate_NotEmpty( INTEGER ), true );
			}

			( new Zend_Validate_Int(  ), true );

			if (( !is_null( $min ) || !is_null( $max ) )) {
				( new Zend_Validate_Callback( array( 'callback' => array( $this, 'validateRange' ), 'options' => array( $min, $max ) ) ), true );
			}

			return $validator;
		}

		/**
     * Get float validator
     * 
     * @param boolean $isRequired
     * @param int $min
     * @param int $max
     * 
     * @return Zend_Validate
     */
		function getFloatValidator($isRequired = false, $min = null, $max = null) {
			$validator = $this->createValidatorChain(  );

			if ($isRequired) {
				( new Zend_Validate_NotEmpty( FLOAT ), true );
			}

			( new Zend_Validate_Float(  ), true );

			if (( !is_null( $min ) || !is_null( $max ) )) {
				( new Zend_Validate_Callback( array( 'callback' => array( $this, 'validateRange' ), 'options' => array( $min, $max ) ) ), true );
			}

			return $validator;
		}

		/**
     * Get filters
     * 
     * @return array
     */
		function getFilters() {
			return array(  );
		}

		/**
     * Filter model
     *
     * @throws Mage_Core_Exception
     * 
     * @return Innoexts_Core_Model_Abstract
     */
		function filter() {
			$filters = $this->getFilters(  );
			foreach ($filters as $field => $filter) {
				$this->setData( $field, $filter->filter( $this->getData( $field ) ) );
			}

			return $this;
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			return array(  );
		}

		/**
     * Validate model
     * 
     * @throws Mage_Core_Exception
     * 
     * @return bool
     */
		function validate() {
			$validators = $this->getValidators(  );
			$errorMessages = array(  );
			foreach ($validators as $field => $validator) {

				if (!$validator->isValid( $this->getData( $field ) )) {
					$errorMessages = array_merge( $errorMessages, $validator->getMessages(  ) );
					continue;
				}
			}


			if (count( $errorMessages )) {
				Mage::throwException( join( '
', $errorMessages ) );
			}

			return true;
		}

		/**
     * Processing object before save data
     *
     * @return Innoexts_Core_Model_Abstract
     */
		function _beforeSave() {
			$this->filter(  );
			$this->validate(  );
			parent::_beforeSave(  );
			return $this;
		}

		/**
     * Add data
     * 
     * @param array $arr
     * 
     * @return Innoexts_Core_Model_Abstract
     */
		function addData($arr) {
			Mage::dispatchEvent( $this->_eventPrefix . '_add_data_before', array_merge( $this->_getEventData(  ), array( 'array' => $arr ) ) );
			parent::addData( $arr );
			Mage::dispatchEvent( $this->_eventPrefix . '_add_data_after', array_merge( $this->_getEventData(  ), array( 'array' => $arr ) ) );
			return $this;
		}
	}

?>