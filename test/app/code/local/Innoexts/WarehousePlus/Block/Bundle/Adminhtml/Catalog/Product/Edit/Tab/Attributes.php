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

	class Innoexts_WarehousePlus_Block_Bundle_Adminhtml_Catalog_Product_Edit_Tab_Attributes extends Innoexts_Warehouse_Block_Bundle_Adminhtml_Catalog_Product_Edit_Tab_Attributes {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}
	}

?>