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

	class Innoexts_WarehousePlus_Adminhtml_Catalog_Product_ZoneController extends Innoexts_Core_Controller_Adminhtml_Action {
		private $_modelNames = array( 'product' => 'catalog/product', 'product_zone_price' => 'catalog/product_zone_price' );

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_WarehousePlus_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Initialize product
     *
     * @return Mage_Catalog_Model_Product
     */
		function _initProduct() {
			$productId = (int)$this->getRequest(  )->getParam( 'id' );
			$product = Mage::getModel( 'catalog/product' )->setStoreId( $this->getRequest(  )->getParam( 'store', 0 ) );

			if (!$productId) {
				if ($setId = (int)$this->getRequest(  )->getParam( 'set' )) {
					$product->setAttributeSetId( $setId );
				}


				if ($typeId = $this->getRequest(  )->getParam( 'type' )) {
					$product->setTypeId( $typeId );
				}
			}

			$product->setData( '_edit_mode', true );

			if ($productId) {
				$product->load( $productId );
			}

			Mage::register( 'product', $product );
			Mage::register( 'current_product', $product );
			return $product;
		}

		/**
     * Price grid action
     * 
     * @return Innoexts_WarehousePlus_Adminhtml_Catalog_Product_ZoneController
     */
		function priceGridAction() {
			$this->_initProduct(  );
			$this->_gridAction( 'product_zone_price', true );
		}

		/**
     * Prepare save
     * 
     * @param string $type
     * @param Mage_Core_Model_Abstract $model
     * 
     * @return Innoexts_WarehousePlus_Adminhtml_Catalog_Product_ZoneController
     */
		function _prepareSave($type, $model) {
			if ($type == 'product_zone_price') {
				$productId = $this->getRequest(  )->getParam( 'id' );
				$model->setProductId( $productId );
			}

			return $this;
		}

		/**
     * Edit price action
     * 
     * @return Innoexts_WarehousePlus_Adminhtml_Catalog_Product_ZoneController
     */
		function editPriceAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_editAction( 'product_zone_price', true, null, 'zone_price_id', null, null, null, array(  ), $helper->__( 'The price was not found.' ) );
			return $this;
		}

		/**
     * Save price action
     * 
     * @return Innoexts_WarehousePlus_Adminhtml_Catalog_Product_ZoneController
     */
		function savePriceAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_saveAction( 'product_zone_price', true, 'zone_price_id', null, null, $helper->__( 'The price has been saved.' ), $helper->__( 'An error occurred while saving the price: %s.' ) );
			return $this;
		}

		/**
     * Delete price action
     * 
     * @return Innoexts_WarehousePlus_Adminhtml_Catalog_Product_ZoneController
     */
		function deletePriceAction() {
			$helper = $this->getWarehouseHelper(  );
			$this->_deleteAction( 'product_zone_price', true, 'zone_price_id', null, null, $helper->__( 'The price was not found.' ), $helper->__( 'The price has been deleted.' ) );
			return $this;
		}
	}

?>