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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Batchprice_Renderer extends Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Renderer_Abstract {
		/**
     * Constructor
     */
		function __construct() {
			$this->setTemplate( 'warehouse/catalog/product/edit/tab/batchprice/renderer.phtml' );
		}

		/**
     * Check if global price scope is active
     * 
     * @return bool
     */
		function isGlobalPriceScope() {
			return $this->getWarehouseHelper(  )->getProductPriceHelper(  )->isGlobalScope(  );
		}

		/**
     * Check if website price scope is active
     * 
     * @return bool
     */
		function isWebsitePriceScope() {
			return $this->getWarehouseHelper(  )->getProductPriceHelper(  )->isWebsiteScope(  );
		}

		/**
     * Get price scope string
     * 
     * @return string
     */
		function getPriceScopeStr() {
			$scope = null;

			if ($this->isWebsitePriceScope(  )) {
				$scope = '[WEBSITE]';
			} 
else {
				$scope = '[GLOBAL]';
			}

			return $scope;
		}

		/**
     * Sort values function
     *
     * @param mixed $a
     * @param mixed $b
     * 
     * @return int
     */
		function sortValues($a, $b) {
			if ($a['stock_id'] != $b['stock_id']) {
				return ($a['stock_id'] < $b['stock_id'] ? -1 : 1);
			}

			return 0;
		}
	/**
     * Get values
     * 
     * @return array
     */
		function getValues() {
		    $helper = $this->getWarehouseHelper(  );
		    $helper->getProductPriceHelper();
			$productPriceHelper = $this->getWarehouseHelper(  );
			$values = array();
			$stockIds = $helper->getStockIds(  );

			if (count( $stockIds )) {
			    $element = $this->getElement();
				$product = $this->getProduct();
				$readonly = $element->getReadonly();
				
				$websiteId = $storeId = (int)$this->getStore(  )->getId(  );
				$product->getBatchPrices(  );
				$prices = $helper->getProductHelper(  )->getWebsiteIdByStoreId( $storeId );
				$data = (isset( $prices[$websiteId] ) ? $prices[$websiteId] : array(  ));
				foreach ($stockIds as $stockId) {
					$value = array( 'stock_id' => $stockId );
					$defaultPrice = $productPriceHelper->getEscapedPrice( $productPriceHelper->getDefaultPrice( $product, $websiteId, $stockId ) );

					if (isset( $data[$stockId] )) {
						$value['price'] = $productPriceHelper->getEscapedPrice( $data[$stockId] );
						$value['use_default'] = 0;
					} 
else {
						if (!is_null( $defaultPrice )) {
							$value['price'] = $defaultPrice;
						} 
else {
							$value['price'] = null;
						}

						$value['use_default'] = 1;
					}

					$value['readonly'] = $readonly;
					array_push( $values, $value );
				}
			}

			usort( $values, array( $this, 'sortValues' ) );
			return $values;
		}
	}

?>