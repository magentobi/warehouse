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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Area_Grid extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Editable_Area_Grid {
		private $_addButtonLabel = 'Add Area';
		private $_formJsObjectName = 'warehouseAreaTabFormJsObject';

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'warehouseAreaGrid' );
			$this->setDefaultSort( 'warehouse_area_id' );
			$this->setDefaultDir( 'ASC' );
			$this->setUseAjax( true );
		}

		/**
     * Get warehouse helper
     * 
     * @return Varien_Object
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Retrieve registered warehouse
     * 
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getWarehouse() {
			return Mage::registry( 'warehouse' );
		}

		/**
     * Prepare collection object
     *
     * @return Varien_Data_Collection
     */
		function __prepareCollection() {
			$warehouse = $this->getWarehouse(  );
			$collection = Mage::getModel( 'warehouse/warehouse_area' )->getCollection(  );
			$collection->setWarehouseFilter( $warehouse->getId(  ) );
			return $collection;
		}

		/**
     * Get country options
     * 
     * @return array
     */
		function getCountryOptions() {
			$options = array(  );
			$countries = Mage::getModel( 'adminhtml/system_config_source_country' )->toOptionArray( false );

			if (isset( $countries[0] )) {
				$countries[0] = array( 'value' => '0', 'label' => '*' );
			}

			foreach ($countries as $country) {
				$options[$country['value']] = $country['label'];
			}

			return $options;
		}

		/**
     * Add columns to grid
     *
     * @return Innoexts_Warehouse_Block_Admin_Warehouse_Edit_Tab_Area_Grid
     */
		function _prepareColumns() {
			parent::_prepareColumns(  );
			$helper = $this->getWarehouseHelper(  );
			$this->addColumn( 'action', array( 'header' => $helper->__( 'Action' ), 'width' => '50px', 'type' => 'action', 'getter' => 'getId', 'actions' => array( array( 'name' => 'edit', 'caption' => $helper->__( 'Edit' ), 'url' => array( 'base' => '*/*/editArea', 'params' => $this->getRowUrlParameters(  ) ), 'field' => 'warehouse_area_id' ), array( 'name' => 'delete', 'caption' => $helper->__( 'Delete' ), 'url' => array( 'base' => '*/*/deleteArea', 'params' => $this->getRowUrlParameters(  ) ), 'field' => 'warehouse_area_id', 'confirm' => $helper->__( 'Are you sure you want to delete area?' ) ) ), 'filter' => false, 'sortable' => false ) );
			parent::_prepareColumns(  );
			return $this;
		}

		/**
     * Retrieve grid URL
     * 
     * @return string
     */
		function getGridUrl() {
			return ($this->getData( 'grid_url' ) ? $this->getData( 'grid_url' ) : $this->getUrl( '*/*/areaGrid', array( '_current' => true ) ));
		}

		/**
     * Get row URL parameters
     * 
     * @param Varien_Object|null $row
     * 
     * @return array
     */
		function getRowUrlParameters($row = null) {
			$params = array( 'warehouse_id' => $this->getWarehouse(  )->getId(  ) );

			if ($row) {
				$params['warehouse_area_id'] = $row->getId(  );
			}

			return $params;
		}

		/**
     * Get row URL
     * 
     * @param Varien_Object $row
     * 
     * @return string
     */
		function getRowUrl($row) {
			return $this->getUrl( '*/*/editArea', $this->getRowUrlParameters( $row ) );
		}
	}

?>