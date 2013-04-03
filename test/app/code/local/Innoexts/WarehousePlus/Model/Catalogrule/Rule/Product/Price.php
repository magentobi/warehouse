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

	class Innoexts_WarehousePlus_Model_Catalogrule_Rule_Product_Price extends Mage_CatalogRule_Model_Rule_Product_Price {
		/**
     * Apply price rule price to price index table
     * 
     * @param Varien_Db_Select $select
     * @param array|string $indexTable
     * @param string $entityId
     * @param string $customerGroupId
     * @param string $websiteId
     * @param array $updateFields       the array fields for compare with rule price and update
     * @param string $websiteDate
     * 
     * @return Mage_CatalogRule_Model_Rule_Product_Price
     */
		function applyPriceRuleToIndexTable($select, $indexTable, $entityId, $customerGroupId, $websiteId, $updateFields, $websiteDate) {
			$this->_getResource(  )->applyPriceRuleToIndexTable( clone $select, $indexTable, $entityId, $customerGroupId, $websiteId, $updateFields, $websiteDate );
			return $this;
		}
	}

?>