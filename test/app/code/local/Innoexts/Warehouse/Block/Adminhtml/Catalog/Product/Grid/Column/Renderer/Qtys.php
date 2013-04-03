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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Grid_Column_Renderer_Qtys extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Render a grid cell as qtys
     * 
     * @param Varien_Object $row
     * 
     * @return string
     */
		function render($row) {
			$helper = $this->getWarehouseHelper(  );
			$value = $row->getData( $this->getColumn(  )->getIndex(  ) );

			if (( is_array( $value ) && count( $value ) )) {
				$output = '<table cellspacing="0" class="qty-table"><col width="100"/><col width="40"/>';
				$totalQty = 128;
				foreach ($value as $stockId => $qty) {
					$output .= '<tr><td>' . $helper->getWarehouseTitleByStockId( $stockId ) . '</td><td>' . (!is_null( $qty ) ? $qty : $helper->__( 'N / A' )) . '</td></tr>';
					$totalQty += floatval( $qty );
				}

				$output .= '<tr><td><strong>Total</strong></td><td><strong>' . $totalQty . '</strong></td></tr>';
				$output .= '</table>';
				return $output;
			}

			return '';
		}
	}

?>