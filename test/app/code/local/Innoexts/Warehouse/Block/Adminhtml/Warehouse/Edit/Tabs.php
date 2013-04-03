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

	class Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tabs extends Innoexts_Core_Block_Adminhtml_Widget_Tabs {
		private $_modelName = 'warehouse';
		private $_childBlockTypePrefix = 'warehouse/adminhtml_warehouse_edit_tab_';

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
			$this->setId( 'warehouse_tabs' );
			$this->setDestElementId( 'edit_form' );
			$this->setTitle( $this->getWarehouseHelper(  )->__( 'Warehouse Information' ) );
		}

		/**
     * Prepare layout
     * 
     * @return Innoexts_Warehouse_Block_Adminhtml_Warehouse_Edit_Tabs
     */
		function _prepareLayout() {
			$helper = $this->getWarehouseHelper(  );
			$this->addTab( 'main', array( 'label' => $helper->__( 'General' ), 'content' => $this->getChildBlockContent( 'main' ) ) );
			$this->addTab( 'contact', array( 'label' => $helper->__( 'Contact' ), 'content' => $this->getChildBlockContent( 'contact' ) ) );
			$this->addTab( 'origin', array( 'label' => $helper->__( 'Origin' ), 'content' => $this->getChildBlockContent( 'origin' ) ) );

			if ($this->getModel(  )->getId(  )) {
				$config = $this->getWarehouseHelper(  )->getConfig(  );

				if (( !$config->isMultipleMode(  ) && $config->isAssignedAreaSingleAssignmentMethod(  ) )) {
					$this->addTab( 'area', array( 'label' => $helper->__( 'Areas' ), 'content' => $this->getChildBlockContent( 'area' ) ) );
				}


				if (( !$config->isMultipleMode(  ) && $config->isAssignedStoreSingleAssignmentMethod(  ) )) {
					$this->addTab( 'store', array( 'label' => $helper->__( 'Stores' ), 'content' => $this->getChildBlockContent( 'store' ) ) );
				}


				if (( !$config->isMultipleMode(  ) && $config->isAssignedCustomerGroupSingleAssignmentMethod(  ) )) {
					$this->addTab( 'customerGroups', array( 'label' => $helper->__( 'Customer Groups' ), 'content' => $this->getChildBlockContent( 'customer_group' ) ) );
				}


				if (( !$config->isMultipleMode(  ) && $config->isAssignedCurrencySingleAssignmentMethod(  ) )) {
					$this->addTab( 'currencies', array( 'label' => $helper->__( 'Currencies' ), 'content' => $this->getChildBlockContent( 'currency' ) ) );
				}


				if ($config->isShippingCarrierFilterEnabled(  )) {
					$this->addTab( 'shipping_carrier', array( 'label' => $helper->__( 'Shipping Carriers' ), 'content' => $this->getChildBlockContent( 'shipping_carrier' ) ) );
				}

				$this->addTab( 'products', array( 'label' => $helper->__( 'Products' ), 'url' => $this->getUrl( '*/*/productsGrid', array( '_current' => true ) ), 'class' => 'ajax' ) );
				$this->addTab( 'salesOrders', array( 'label' => $helper->__( 'Orders' ), 'url' => $this->getUrl( '*/*/salesOrdersGrid', array( '_current' => true ) ), 'class' => 'ajax' ) );
				$this->addTab( 'salesInvoices', array( 'label' => $helper->__( 'Invoices' ), 'url' => $this->getUrl( '*/*/salesInvoicesGrid', array( '_current' => true ) ), 'class' => 'ajax' ) );
				$this->addTab( 'salesShipments', array( 'label' => $helper->__( 'Shipments' ), 'url' => $this->getUrl( '*/*/salesShipmentsGrid', array( '_current' => true ) ), 'class' => 'ajax' ) );
				$this->addTab( 'salesCreditMemos', array( 'label' => $helper->__( 'Credit Memos' ), 'url' => $this->getUrl( '*/*/salesCreditmemosGrid', array( '_current' => true ) ), 'class' => 'ajax' ) );
			}

			parent::_prepareLayout(  );
			return $this;
		}
	}

?>