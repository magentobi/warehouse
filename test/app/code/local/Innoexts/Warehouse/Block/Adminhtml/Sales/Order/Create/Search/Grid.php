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

	class Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Search_Grid extends Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Prepare page
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Sales_Order_Create_Search_Grid
     */
		function _preparePage() {
			parent::_preparePage(  );
			$stockId = $this->getWarehouseHelper(  )->getAssignmentMethodHelper(  )->getQuoteStockId( $this->getQuote(  ) );

			if ($stockId) {
				$this->getCollection(  )->setFlag( 'stock_id', $stockId );
			}

			return $this;
		}
	}

?>