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

	class Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Manual extends Innoexts_Warehouse_Model_Warehouse_Assignment_Method_Single_Abstract {
		/**
     * Get stock identifier
     * 
     * @return int
     */
		function _getStockId() {
			$helper = $this->getWarehouseHelper(  );
			$stockId = $helper->getSessionStockId(  );

			if ($stockId) {
				return $stockId;
			}

			return parent::_getStockId(  );
		}
	}

?>