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

	class Innoexts_Warehouse_Helper_Warehouse_Assignment_Method extends Mage_Core_Helper_Abstract {
		private $_singleMethods = null;
		private $_multipleMethods = null;

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get single methods
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Abstract
     */
		function getSingleMethods() {
			while (is_null( $this->_singleMethods )) {
				$helper = $this->getWarehouseHelper(  );
				Mage::getStoreConfig( 'single_assignment_methods' );
				$config = $methods = array(  );
				foreach ($config as $code => $methodConfig) {

					if (!isset( $methodConfig['model'] )) {
						Mage::throwException( $helper->__( 'Invalid model for single assignment method: %', $code ) );
					}

					$methodConfig['model'];
					$method = $modelName = Mage::getModel( $modelName, $methodConfig );
					jmp;
					Exception {
						Mage::logException( $e );
						return false;
						$method->setId( $code );
						$methods[$code] = $method;
						continue;
					}

					break;
				}

				$this->_singleMethods = $methods;
			}

			return $this->_singleMethods;
		}

		/**
     * Get single method
     * 
     * @param string $code
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Abstract
     */
		function getSingleMethod($code) {
			$methods = $this->getSingleMethods(  );

			if (isset( $methods[$code] )) {
				return $methods[$code];
			}

		}

		/**
     * Get current single method
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Abstract
     */
		function getCurrentSingleMethod() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			return $this->getSingleMethod( $config->getSingleAssignmentMethodCode(  ) );
		}

		/**
     * Get multiple methods
     * 
     * @return array of Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract
     */
		function getMultipleMethods() {
			while (is_null( $this->_multipleMethods )) {
				$helper = $this->getWarehouseHelper(  );
				$methods = array(  );
				$config = Mage::getStoreConfig( 'multiple_assignment_methods' );
				foreach ($config as $code => $methodConfig) {

					if (!isset( $methodConfig['model'] )) {
						Mage::throwException( $helper->__( 'Invalid model for multiple assignment method: %', $code ) );
					}

					$modelName = $methodConfig['model'];
					$method = Mage::getModel( $modelName, $methodConfig );
					jmp;
					Exception {
						Mage::logException( $e );
						return false;
						$method->setId( $code );
						$methods[$code] = $method;
						continue;
					}

					break;
				}

				$this->_multipleMethods = $methods;
			}

			return $this->_multipleMethods;
		}

		/**
     * Get multiple method
     * 
     * @param string $code
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract
     */
		function getMultipleMethod($code) {
			$methods = $this->getMultipleMethods(  );

			if (isset( $methods[$code] )) {
				return $methods[$code];
			}

		}

		/**
     * Get current multiple method
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Multiple_Abstract
     */
		function getCurrentMultipleMethod() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );
			return $this->getMultipleMethod( $config->getMultipleAssignmentMethodCode(  ) );
		}

		/**
     * Get current method
     * 
     * @return Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Abstract
     */
		function getCurrentMethod() {
			$config = $this->getWarehouseHelper(  )->getConfig(  );

			if ($config->isMultipleMode(  )) {
				return $this->getCurrentMultipleMethod(  );
			}

			return $this->getCurrentSingleMethod(  );
		}

		/**
     * Apply quote stock items
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return Innoexts_Warehouse_Helper_Warehouse_Assignment_Method
     */
		function applyQuoteStockItems($quote) {
			$method = $this->getCurrentMethod(  );

			if (!$method) {
				return $this;
			}

			$method->setQuote( $quote )->applyQuoteStockItems(  );
			return $this;
		}

		/**
     * Get quote stock identifier
     * 
     * @param Innoexts_Warehouse_Model_Sales_Quote $quote
     * 
     * @return int|null
     */
		function getQuoteStockId($quote = null) {
			$method = $this->getCurrentMethod(  );

			if (!$method) {
				return null;
			}

			return $method->setQuote( $quote )->getStockId(  );
		}

		/**
     * Get product stock identifier
     * 
     * @param Mage_Catalog_Model_Product $product
     * 
     * @return int
     */
		function getProductStockId($product) {
			$method = $this->getCurrentMethod(  );

			if (!$method) {
				return null;
			}

			return $method->getProductStockId( $product );
		}
	}

?>