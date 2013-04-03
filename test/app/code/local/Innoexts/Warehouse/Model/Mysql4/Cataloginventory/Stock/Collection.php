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

	class Innoexts_Warehouse_Model_Mysql4_Cataloginventory_Stock_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
		/**
     * Constructor
     */
		function _construct() {
			$this->_init( 'cataloginventory/stock' );
		}

		/**
     * Retrieves ids
     *
     * @return  array
     */
		function getIds() {
			$ids = array(  );
			foreach ($this as $stock) {
				array_push( $ids, $stock->getId(  ) );
			}

			return $ids;
		}

		/**
     * Convert to array for select options
     *
     * @param   bool $emptyLabel
     * 
     * @return  array
     */
		function toOptionArray($emptyLabel = '') {
			$options = $this->_toOptionArray( 'stock_id', 'stock_name', array(  ) );

			if (( 0 < count( $options ) && $emptyLabel !== false )) {
				array_unshift( $options, array( 'value' => '', 'label' => $emptyLabel ) );
			}

			return $options;
		}
	}

?>