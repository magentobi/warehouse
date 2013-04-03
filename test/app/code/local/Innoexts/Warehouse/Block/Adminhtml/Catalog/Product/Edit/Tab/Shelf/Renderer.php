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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Shelf_Renderer extends Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Renderer_Abstract {
		/**
     * Constructor
     */
		function __construct() {
			$this->setTemplate( 'warehouse/catalog/product/edit/tab/shelf/renderer.phtml' );
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
			$this->getWarehouseHelper(  )->getCatalogInventoryHelper(  )->getStockIds(  );
			$stocksIds = $values = array(  );

			if (count( $stocksIds )) {
				$readonly = $this->getElement(  )->getReadonly(  );
				$data = $this->getElement(  )->getValue(  );
				foreach ($stocksIds as $stockId) {
					$value = null;

					if (isset( $data[$stockId] )) {
						$value = $data[$stockId];
					} 
else {
						$value = '';
					}

					$value = array( 'stock_id' => $stockId, 'name' => $value );
					$value['readonly'] = $readonly;
					array_push( $values, $value );
				}
			}

			usort( $values, array( $this, 'sortValues' ) );
			return $values;
		}
	}

?>