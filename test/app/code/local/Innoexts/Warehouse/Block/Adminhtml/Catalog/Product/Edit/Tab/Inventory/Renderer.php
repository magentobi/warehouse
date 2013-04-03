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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Inventory_Renderer extends Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Renderer_Abstract {
		private $_stocksItems = null;

		/**
     * Constructor
     */
		function __construct() {
			$this->setTemplate( 'warehouse/catalog/product/edit/tab/inventory/renderer.phtml' );
		}

		/**
     * Is readonly stock
     *
     * @return boolean
     */
		function isReadonly() {
			return $this->getProduct(  )->getInventoryReadonly(  );
		}

		/**
     * Get stocks items
     * 
     * @return array
     */
		function getStocksItems() {
			if (is_null( $this->_stocksItems )) {
				if (!$this->isNew(  )) {
					$stocksItems = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockItemCollection( $this->getProductId(  ) );
					$this->_stocksItems = array(  );
					foreach ($stocksItems as $stockItem) {
						$this->_stocksItems[$stockItem->getStockId(  )] = $stockItem;
					}
				} 
else {
					$this->_stocksItems = array(  );
				}
			}

			return $this->_stocksItems;
		}

		/**
     * Get stock item
     * 
     * @param int $stockId
     * 
     * @return Innoexts_Warehouse_Model_Cataloginventory_Stock_Item | null
     */
		function getStockItem($stockId) {
			$stocksItems = $this->getStocksItems(  );
			return (isset( $stocksItems[$stockId] ) ? $stocksItems[$stockId] : null);
		}

		/**
     * Get default stock item field value
     * 
     * @param string $field
     * 
     * @return mixed
     */
		function getDefaultConfigValue($field) {
			return Mage::getStoreConfig( XML_PATH_ITEM . $field );
		}

		/**
     * Get stock item field value
     * 
     * @param Innoexts_Warehouse_Model_Cataloginventory_Stock_Item $stockItem
     * @param string $field
     * 
     * @return mixed
     */
		function getFieldValue($stockItem = null, $field) {
			if ($stockItem) {
				return $stockItem->getDataUsingMethod( $field );
			}

			return $this->getDefaultConfigValue( $field );
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
			$values = array(  );
			$data = array(  );
			$stockIds = $this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockIds(  );

			if (count( $stockIds )) {
				$keys = Mage::helper( 'cataloginventory/data' )->getConfigItemOptions(  );
				foreach ($stockIds as $stockId) {
					$stockItem = $this->getStockItem( $stockId );
					$item = array( 'stock_id' => $stockId, 'qty' => $this->getFieldValue( $stockItem, 'qty' ) * 1, 'original_inventory_qty' => $this->getFieldValue( $stockItem, 'qty' ) * 1, 'is_qty_decimal' => ($stockItem ? $this->getFieldValue( $stockItem, 'is_qty_decimal' ) * 1 : 0), 'is_in_stock' => ($stockItem ? $this->getFieldValue( $stockItem, 'is_in_stock' ) * 1 : 0) );
					foreach ($keys as $key) {
						$useConfigKey = 'use_config_' . $key;
						$item[$key] = $this->getFieldValue( $stockItem, $key ) * 1;
						$item[$useConfigKey] = (( !$stockItem || $this->getFieldValue( $stockItem, $useConfigKey ) ) ? 1 : 0);
					}

					array_push( $data, $item );
				}
			}


			if (is_array( $data )) {
				usort( $data, array( $this, 'sortValues' ) );
				$values = $stockId;
			}

			return $values;
		}

		/**
     * Get backorders values
     * 
     * @return array
     */
		function getBackordersValues() {
			return Mage::getSingleton( 'cataloginventory/source_backorders' )->toOptionArray(  );
		}

		/**
     * Get is in stock values
     * 
     * @return array
     */
		function getIsInStockValues() {
			return Mage::getSingleton( 'cataloginventory/source_stock' )->toOptionArray(  );
		}
	}

?>