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

	class Innoexts_WarehousePlus_Model_Catalog_Product_Attribute_Backend_Price extends Mage_Catalog_Model_Product_Attribute_Backend_Price {
		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Set Attribute instance
     * Rewrite for redefine attribute scope
     * 
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * 
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Price
     */
		function setAttribute($attribute) {
			parent::setAttribute( $attribute );
			$this->setScope( $attribute );
			return $this;
		}

		/**
     * Redefine attribute scope
     *
     * @param Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * 
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Price
     */
		function setScope($attribute) {
			$priceHelper = $this->getWarehouseHelper(  )->getProductPriceHelper(  );
			$priceHelper->setAttributeScope( $attribute );
			return $this;
		}
	}

?>