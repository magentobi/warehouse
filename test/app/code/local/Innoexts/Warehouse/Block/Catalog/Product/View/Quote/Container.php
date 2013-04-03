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

	class Innoexts_Warehouse_Block_Catalog_Product_View_Quote_Container extends Mage_Core_Block_Template {
		/**
     * Get warehouse helper
     *
     * @return  Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Construct
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'warehouse/catalog/product/view/quote/container.phtml' );
		}

		/**
     * Prepare layout
     * 
     * @return Innoexts_Warehouse_Block_Catalog_Product_View_Quote_Container
     */
		function _prepareLayout() {
			$this->setChild( 'quote', $this->getLayout(  )->createBlock( 'warehouse/catalog_product_view_quote' ) );
			parent::_prepareLayout(  );
			return $this;
		}
	}

?>