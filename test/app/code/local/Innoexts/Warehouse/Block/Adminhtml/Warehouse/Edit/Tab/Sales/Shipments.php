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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Sales_Shipments extends Innoexts_Core_Block_Adminhtml_Widget_Grid {
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
			$this->setId( 'salesShipmentsGrid' );
			$this->setDefaultSort( 'created_at' );
			$this->setDefaultDir( 'DESC' );
			$this->setUseAjax( true );
		}

		/**
     * Retrieve warehouse model
     *
     * @return Innoexts_Warehouse_Model_Warehouse
     */
		function getModel() {
			return Mage::registry( 'warehouse' );
		}

		/**
     * Check wether action is allowed or not
     * 
     * @param string $action
     * @return boolean
     */
		function isActionAllowed($action) {
			return $this->getAdminSession(  )->isAllowed( 'sales/order/shipment' );
		}

		/**
     * Retrieve collection class
     * 
     * @return string
     */
		function _getCollectionClass() {
			return 'sales/order_shipment_grid_collection';
		}

		/**
     * Prepare collection object
     *
     * @return Varien_Data_Collection
     */
		function __prepareCollection() {
			$model = $this->getModel(  );
			$collection = Mage::getResourceModel( $this->_getCollectionClass(  ) );
			$select = $collection->getSelect(  );
			$select->joinLeft( array( 'shipment_stock' => $collection->getTable( 'warehouse/shipment_grid_warehouse' ) ), '(main_table.entity_id = shipment_stock.entity_id)', array( 'stock_id' => 'shipment_stock.stock_id' ) );

			if ($model->getId(  )) {
				$select->where( 'shipment_stock.stock_id = ?', $model->getStockId(  ) );
			} 
else {
				$select->where( 'shipment_stock.stock_id = -1' );
			}

			return $collection;
		}

		/**
     * Add columns to grid
     *
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tab_Sales_Shipments
     */
		function _prepareColumns() {
			$helper = $this->getWarehouseHelper(  );
			$this->addColumn( 'increment_id', array( 'header' => $helper->__( 'Shipment #' ), 'index' => 'increment_id', 'type' => 'text' ) );
			$this->addColumn( 'created_at', array( 'header' => $helper->__( 'Date Shipped' ), 'index' => 'created_at', 'type' => 'datetime' ) );
			$this->addColumn( 'order_increment_id', array( 'header' => $helper->__( 'Order #' ), 'index' => 'order_increment_id', 'type' => 'text' ) );
			$this->addColumn( 'order_created_at', array( 'header' => $helper->__( 'Order Date' ), 'index' => 'order_created_at', 'type' => 'datetime' ) );
			$this->addColumn( 'shipping_name', array( 'header' => $helper->__( 'Ship to Name' ), 'index' => 'shipping_name' ) );
			$this->addColumn( 'total_qty', array( 'header' => $helper->__( 'Total Qty' ), 'index' => 'total_qty', 'type' => 'number' ) );
			$this->addColumn( 'action', array( 'header' => $helper->__( 'Action' ), 'width' => '50px', 'type' => 'action', 'getter' => 'getId', 'actions' => array( array( 'caption' => $helper->__( 'View' ), 'url' => array( 'base' => 'adminhtml/sales_shipment/view' ), 'field' => 'shipment_id' ) ), 'filter' => false, 'sortable' => false, 'is_system' => true ) );
			return parent::_prepareColumns(  );
		}

		/**
     * Retrieve grid URL
     *
     * @return string
     */
		function getGridUrl() {
			return ($this->getData( 'grid_url' ) ? $this->getData( 'grid_url' ) : $this->getUrl( '*/*/salesShipmentsGrid', array( '_current' => true ) ));
		}

		/**
     * Get row URL
     * 
     * @return string
     */
		function getRowUrl($row) {
			if (!$this->isViewAllowed(  )) {
				return false;
			}

			return $this->getUrl( 'adminhtml/sales_shipment/view', array( 'shipment_id' => $row->getId(  ) ) );
		}
	}

?>