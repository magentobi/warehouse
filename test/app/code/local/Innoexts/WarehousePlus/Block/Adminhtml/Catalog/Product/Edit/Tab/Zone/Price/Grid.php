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

	class Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Zone_Price_Grid extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Grid {
		private $_addButtonLabel = 'Add Discount';
		private $_formJsObjectName = 'productZonePriceTabFormJsObject';

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'productZonePriceGrid' );
			$this->setDefaultSort( 'zone_price_id' );
			$this->setDefaultDir( 'ASC' );
			$this->setUseAjax( true );
		}

		/**
     * Get text helper
     * 
     * @return Varien_Object
     */
		function getTextHelper() {
			return Mage::helper( 'warehouseplus' );
		}

		/**
     * Retrieve registered product model
     *
     * @return Mage_Catalog_Model_Product
     */
		function getProduct() {
			return Mage::registry( 'product' );
		}

		/**
     * Prepare collection
     * 
     * @return Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Zone_Price_Grid
     */
		function __prepareCollection() {
			$product = $this->getProduct(  );
			$collection = Mage::getModel( 'catalog/product_zone_price' )->getCollection(  );
			$collection->setProductFilter( $product->getId(  ) );
			return $collection;
		}

		/**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
		function _getStore() {
			$storeId = (int)$this->getRequest(  )->getParam( 'store', 0 );
			return Mage::app(  )->getStore( $storeId );
		}

		/**
     * Retrieve price type options
     * 
     * @return array
     */
		function getPriceTypeOptions() {
			$helper = $this->getTextHelper(  );
			return array( 'fixed' => $helper->__( 'Fixed' ), 'percent' => $helper->__( 'Percent' ) );
		}

		/**
     * Add columns to grid
     *
     * @return Innoexts_WarehousePlus_Block_Adminhtml_Catalog_Product_Edit_Tab_Zone_Price_Grid
     */
		function _prepareColumns() {
			parent::_prepareColumns(  );
			$helper = $this->getTextHelper(  );
			$this->addColumn( 'price', array( 'header' => $helper->__( 'Amount' ), 'align' => 'left', 'index' => 'price', 'type' => 'number' ) );
			$this->addColumn( 'price_type', array( 'header' => $helper->__( 'Apply' ), 'align' => 'left', 'index' => 'price_type', 'filter_index' => 'main_table.price_type', 'type' => 'options', 'options' => $this->getPriceTypeOptions(  ) ) );
			$this->addColumn( 'action', array( 'header' => $helper->__( 'Action' ), 'width' => '90px', 'type' => 'action', 'getter' => 'getId', 'actions' => array( array( 'name' => 'edit', 'caption' => $helper->__( 'Edit' ), 'url' => array( 'base' => '*/catalog_product_zone/editPrice', 'params' => $this->getRowUrlParameters(  ) ), 'field' => 'zone_price_id' ), array( 'name' => 'delete', 'caption' => $helper->__( 'Delete' ), 'url' => array( 'base' => '*/catalog_product_zone/deletePrice', 'params' => $this->getRowUrlParameters(  ) ), 'field' => 'zone_price_id', 'confirm' => $helper->__( 'Are you sure you want to delete discount?' ) ) ), 'filter' => false, 'sortable' => false ) );
			$this->sortColumnsByOrder(  );
			return $this;
		}

		/**
     * Retrieve grid URL
     * 
     * @return string
     */
		function getGridUrl() {
			return ($this->getData( 'grid_url' ) ? $this->getData( 'grid_url' ) : $this->getUrl( '*/catalog_product_zone/priceGrid', array( '_current' => true ) ));
		}

		/**
     * Get row URL parameters
     * 
     * @param Varien_Object|null $row
     * @return array
     */
		function getRowUrlParameters($row = null) {
			$params = array( 'id' => $this->getProduct(  )->getId(  ) );

			if ($row) {
				$params['zone_price_id'] = $row->getId(  );
			}

			return $params;
		}

		/**
     * Get row URL
     * 
     * @param Varien_Object $row
     * @return string
     */
		function getRowUrl($row) {
			return $this->getUrl( '*/catalog_product_zone/editPrice', $this->getRowUrlParameters( $row ) );
		}
	}

?>