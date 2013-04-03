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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Grid extends Innoexts_Core_Block_Adminhtml_Widget_Grid {
		private $_objectId = 'warehouse_id';

		/**
     * Retrieve warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'warehouseGrid' );
			$this->setDefaultSort( 'priority' );
			$this->setDefaultDir( 'ASC' );
			$this->setSaveParametersInSession( true );
			$this->setUseAjax( true );
			$this->setEmptyText( $this->getWarehouseHelper(  )->__( 'No warehouses found' ) );
		}

		/**
     * Prepare collection object
     *
     * @return Varien_Data_Collection
     */
		function __prepareCollection() {
			return Mage::getModel( 'warehouse/warehouse' )->getCollection(  );
		}

		/**
     * Prepare columns
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Grid
     */
		function _prepareColumns() {
			parent::_prepareColumns(  );
			$this->getWarehouseHelper(  );
			$this->addColumn( 'warehouse_id', array( 'header' => $helper->__( 'ID' ), 'width' => '80', 'align' => 'left', 'index' => 'warehouse_id' ) );
			$config = $helper = $helper->getConfig(  );

			if ($config->isPriorityEnabled(  )) {
				$this->addColumn( 'priority', array( 'header' => $helper->__( 'Priority' ), 'width' => '80', 'align' => 'left', 'index' => 'priority' ) );
			}

			$this->addColumn( 'code', array( 'header' => $helper->__( 'Code' ), 'align' => 'left', 'index' => 'code' ) );
			$this->addColumn( 'title', array( 'header' => $helper->__( 'Title' ), 'align' => 'left', 'index' => 'title' ) );
			$this->addColumn( 'origin_country_id', array( 'header' => $helper->__( 'Origin Country' ), 'width' => '100', 'type' => 'country', 'index' => 'origin_country_id' ) );
			$this->addColumn( 'origin_region', array( 'header' => $helper->__( 'Origin Region/State' ), 'width' => '100', 'index' => 'origin_region' ) );
			$this->addColumn( 'origin_postcode', array( 'header' => $helper->__( 'Origin Postal Code' ), 'width' => '100', 'index' => 'origin_postcode' ) );
			$this->addColumn( 'origin_city', array( 'header' => $helper->__( 'Origin City' ), 'width' => '100', 'index' => 'origin_city' ) );
			$this->addColumn( 'action', array( 'header' => $helper->__( 'Action' ), 'width' => '100', 'type' => 'action', 'getter' => 'getId', 'actions' => array( array( 'caption' => $helper->__( 'Edit' ), 'url' => array( 'base' => '*/*/edit' ), 'field' => 'warehouse_id' ) ), 'filter' => false, 'sortable' => false, 'is_system' => true ) );
			return $this;
		}
	}

?>