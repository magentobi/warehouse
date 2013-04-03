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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Price_Tier_Renderer extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Price_Tier {
		/**
     * Constructor
     */
		function __construct() {
			parent::__construct(  );
			$this->setTemplate( 'warehouse/catalog/product/edit/tab/price/tier/renderer.phtml' );
		}

		/**
     * Get warehouse helper
     * 
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get stock identifiers
     * 
     * @return array
     */
		function getStockIds() {
			return $this->getWarehouseHelper(  )->getStockIds(  );
		}

		/**
     * Get warehouse title by stock identifier
     * 
     * @param int $stockId
     * 
     * @return string
     */
		function getWarehouseTitleByStockId($stockId) {
			return $this->getWarehouseHelper(  )->getWarehouseTitleByStockId( $stockId );
		}

		/**
     * Get default stock identifier
     * 
     * @return int
     */
		function getDefaultStockId() {
			return 0;
		}

		/**
     * Check if group price is fixed
     * 
     * @return bool
     */
		function isGroupPriceFixed() {
			return $this->getWarehouseHelper(  )->getProductHelper(  )->isGroupPriceFixed( $this->getProduct(  ) );
		}

		/**
     * Get values
     * 
     * @return array
     */
		function getValues() {
			$helper = $this->getWarehouseHelper(  );
			$priceHelper = $helper->getProductPriceHelper(  );
			$values = array(  );
			$data = $this->getElement(  )->getValue(  );

			if (is_array( $data )) {
				usort( $data, array( $this, '_sortTierPrices' ) );
				$values = $this;
			}

			$storeId = $this->getProduct(  )->getStoreId(  );
			$websiteId = $helper->getWebsiteIdByStoreId( $storeId );
			$_values = array(  );
			foreach ($values as $k => $v) {

				if (!$priceHelper->isInactiveData( $v, $websiteId )) {
					$_values[$k] = $v;
					continue;
				}
			}

			$values = $k;
			foreach ($values as $v) {
				$v = &;

				$v['readonly'] = ($priceHelper->isAncestorData( $v, $websiteId ) ? true : false);
			}

			return $values;
		}

		/**
     * Sort tier price values callback method
     *
     * @param array $a
     * @param array $b
     * 
     * @return int
     */
		function _sortTierPrices($a, $b) {
			if ($a['website_id'] != $b['website_id']) {
				return ($a['website_id'] < $b['website_id'] ? -1 : 1);
			}


			if ($a['stock_id'] != $b['stock_id']) {
				return ($a['stock_id'] < $b['stock_id'] ? -1 : 1);
			}


			if ($a['cust_group'] != $b['cust_group']) {
				return ($this->getCustomerGroups( $a['cust_group'] ) < $this->getCustomerGroups( $b['cust_group'] ) ? -1 : 1);
			}


			if ($a['price_qty'] != $b['price_qty']) {
				return ($a['price_qty'] < $b['price_qty'] ? -1 : 1);
			}

			return 0;
		}
	}

?>