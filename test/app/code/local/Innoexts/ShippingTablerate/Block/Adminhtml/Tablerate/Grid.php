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

	class Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid extends Innoexts_Core_Block_Adminhtml_Widget_Grid_Area_Grid {
		private $_objectId = 'tablerate_id';
		private $_website = null;

		/**
     * Retrieve shipping table rate helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getShippingTablerateHelper() {
			return Mage::helper( 'shippingtablerate' );
		}

		/**
     * Get text helper
     *
     * @return Innoexts_ShippingTablerate_Helper_Data
     */
		function getTextHelper() {
			return $this->getShippingTablerateHelper(  );
		}

		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setId( 'tablerateGrid' );
			$this->setDefaultSort( 'pk' );
			$this->setDefaultDir( 'ASC' );
			$this->setSaveParametersInSession( true );
			$this->setUseAjax( true );
			$this->_exportPageSize = 10000;
			$this->setEmptyText( $this->getTextHelper(  )->__( 'No table rates found.' ) );
		}

		/**
     * Get website
     * 
     * @return Mage_Core_Model_Website
     */
		function getWebsite() {
			if (is_null( $this->_website )) {
				$this->_website = $this->getShippingTablerateHelper(  )->getWebsite(  );
			}

			return $this->_website;
		}

		/**
     * Get website identifier
     * 
     * @return mixed
     */
		function getWebsiteId() {
			return $this->getShippingTablerateHelper(  )->getWebsiteId( $this->getWebsite(  ) );
		}

		/**
     * Prepare collection object
     *
     * @return Varien_Data_Collection
     */
			$websiteId = function __prepareCollection() {;
			$collection = Mage::getModel( 'shippingtablerate/tablerate' )->getCollection(  );
			$collection->getSelect(  );
			$select = $this->getWebsiteId(  );

			if ($websiteId) {
				$select->where( 'website_id = ?', $websiteId );
			} 
else {
				$select->where( 'website_id = -1' );
			}

			return $collection;
		}

		/**
     * Get condition name options
     * 
     * @return array
     */
		function getConditionNameOptions() {
			$options = array(  );
			$names = Mage::getModel( 'adminhtml/system_config_source_shipping_tablerate' )->toOptionArray(  );
			foreach ($names as $name) {
				$options[$name['value']] = $name['label'];
			}

			return $options;
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
     * Prepare columns
     *
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid
     */
		function _prepareColumns() {
			$textHelper = $this->getTextHelper(  );
			$this->addColumn( 'dest_country_id', array( 'header' => $textHelper->__( 'Dest Country' ), 'align' => 'left', 'index' => 'dest_country_id', 'filter_index' => 'main_table.dest_country_id', 'type' => 'options', 'options' => $this->getCountryOptions(  ) ) );
			$this->addColumn( 'dest_region', array( 'header' => $textHelper->__( 'Dest Region/State' ), 'align' => 'left', 'index' => 'dest_region', 'filter_index' => 'region_table.code', 'filter' => $this->getAreaChildBlockTypePrefix(  ) . 'column_filter_region', 'default' => '*' ) );
			$this->addColumn( 'dest_zip', array( 'header' => $textHelper->__( 'Dest Zip/Postal Code' ), 'align' => 'left', 'index' => 'dest_zip', 'filter' => $this->getAreaChildBlockTypePrefix(  ) . 'column_filter_zip', 'renderer' => $this->getAreaChildBlockTypePrefix(  ) . 'column_renderer_zip', 'default' => '*' ) );
			$this->addColumn( 'condition_name', array( 'header' => $textHelper->__( 'Condition Name' ), 'align' => 'left', 'index' => 'condition_name', 'type' => 'options', 'options' => $this->getConditionNameOptions(  ) ) );
			$this->addColumn( 'condition_value', array( 'header' => $textHelper->__( 'Condition Value' ), 'align' => 'left', 'index' => 'condition_value', 'type' => 'number', 'default' => '0' ) );
			$store = $this->_getStore(  );
			$this->addColumn( 'price', array( 'header' => $textHelper->__( 'Price' ), 'align' => 'left', 'index' => 'price', 'type' => 'price', 'currency_code' => $store->getBaseCurrency(  )->getCode(  ), 'default' => '0.00' ) );
			$this->addColumn( 'cost', array( 'header' => $textHelper->__( 'Cost' ), 'align' => 'left', 'index' => 'cost', 'type' => 'price', 'currency_code' => $store->getBaseCurrency(  )->getCode(  ), 'default' => '0.00' ) );
			$this->addColumn( 'note', array( 'header' => $textHelper->__( 'Notes' ), 'index' => 'note', 'getter' => 'getShortNote' ) );
			$this->addExportType( '*/*/exportCsv', $textHelper->__( 'CSV' ) );
			$this->addExportType( '*/*/exportXml', $textHelper->__( 'Excel XML' ) );
			return $this;
		}

		/**
     * Get row URL
     * 
     * @param   Varien_Object $row
     * 
     * @return  string
     */
		function getRowUrl($row) {
			return $this->getUrl( '*/*/edit', array( $this->getObjectId(  ) => $row->getId(  ), 'website' => $this->getWebsiteId(  ) ) );
		}

		/**
     * Prepare mass action
     * 
     * @return Innoexts_ShippingTablerate_Block_Adminhtml_Tablerate_Grid
     */
		function _prepareMassaction() {
			$textHelper = $this->getTextHelper(  );
			$this->setMassactionIdField( 'pk' );
			$this->getMassactionBlock(  )->setFormFieldName( $this->getObjectId(  ) );
			$this->getMassactionBlock(  )->addItem( 'delete', array( 'label' => $textHelper->__( 'Delete' ), 'url' => $this->getUrl( '*/*/massDelete', array( 'website' => $this->getWebsiteId(  ) ) ), 'confirm' => $textHelper->__( 'Are you sure?' ) ) );
			return $this;
		}
	}

?>