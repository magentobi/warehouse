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

	class Innoexts_Warehouse_Model_Shippingtablerate_Tablerate extends Innoexts_ShippingTablerate_Model_Tablerate {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Filter warehouse
     * 
     * @param mixed $value
     * 
     * @return string
     */
		function filterWarehouse($value) {
			$helper = $this->getWarehouseHelper(  );

			if (( $value && $value != '*' )) {
				$warehouses = $helper->getWarehousesHash(  );

				if (isset( $warehouses[$value] )) {
					$value = $helper;
				} 
else {
					if (in_array( $value, $warehouses )) {
						$value = array_search( $value, $warehouses );
					} 
else {
						$value = '0';
					}
				}
			} 
else {
				$value = '0';
			}

			return $value;
		}

		/**
     * Get warehouse filter
     * 
     * @return Zend_Filter
     */
		function getWarehouseFilter() {
			return ( new Zend_Filter_Callback( array( 'callback' => array( $this, 'filterWarehouse' ) ) ) );
		}

		/**
     * Get filters
     * 
     * @return array
     */
		function getFilters() {
			$filters = parent::getFilters(  );
			$filters['warehouse_id'] = $this->getWarehouseFilter(  );
			return $filters;
		}

		/**
     * Get validators
     * 
     * @return array
     */
		function getValidators() {
			$validators = parent::getValidators(  );
			$validators['warehouse_id'] = $this->getIntegerValidator( false, 0 );
			return $validators;
		}

		/**
     * Get title
     * 
     * @return string
     */
		function getTitle() {
			$helper = $this->getWarehouseHelper(  );
			$title = parent::getTitle(  );
			$warehouse = null;

			if ($this->getWarehouseId(  )) {
				$warehouse = $helper->getWarehouse( $this->getWarehouseId(  ) );
			}

			return implode( ', ', array( ($warehouse ? $warehouse->getTitle(  ) : '*'), $title ) );
		}
	}

?>