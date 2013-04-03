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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Stock_Priority_Renderer extends Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Edit_Tab_Renderer_Abstract {
		/**
     * Constructor
     */
		function __construct() {
			$this->setTemplate( 'warehouse/catalog/product/edit/tab/stock/priority/renderer.phtml' );
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
			$values = array(  );
			$stocksIds = $helper->getCatalogInventoryHelper(  )->getStockIds(  );

			if (count( $stocksIds )) {
				$readonly = $this->getElement(  )->getReadonly(  );
				$data = $this->getElement(  )->getValue(  );
				foreach ($stocksIds as $stockId) {
					$value = array( 'stock_id' => $stockId );

					if (isset( $data[$stockId] )) {
						$value['priority'] = $data[$stockId];
						$value['use_default'] = 0;
					} 
else {
						$warehouse = $helper->getWarehouseByStockId( $stockId );

						if ($warehouse) {
							$value['priority'] = $warehouse->getPriority(  );
						} 
else {
							$value['priority'] = 0;
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