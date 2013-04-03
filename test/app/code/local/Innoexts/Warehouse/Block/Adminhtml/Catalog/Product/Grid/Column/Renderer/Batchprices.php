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

	class Innoexts_Warehouse_Block_Adminhtml_Catalog_Product_Grid_Column_Renderer_Batchprices extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text {
		/**
     * Get warehouse helper
     *
     * @return Innoexts_Warehouse_Helper_Data
     */
		function getWarehouseHelper() {
			return Mage::helper( 'warehouse' );
		}

		/**
     * Get currency code
     * 
     * @param array $row
     * 
     * @return string
     */
		function _getCurrencyCode($row) {
			$code = $this->getColumn(  )->getCurrencyCode(  );

			if ($code) {
				return $code;
			}

			$code = $row->getData( $this->getColumn(  )->getCurrency(  ) );

			if ($code) {
				return $code;
			}

			return false;
		}

		/**
     * Get rate
     * 
     * @param array $row
     * 
     * @return float
     */
		function _getRate($row) {
			$rate = $this->getColumn(  )->getRate(  );

			if ($rate) {
				return (double)$rate;
			}

			$rate = $row->getData( $this->getColumn(  )->getRateField(  ) );

			if ($rate) {
				return (double)$rate;
			}

			return 1;
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
				$currencyCode = $this->_getCurrencyCode( $row );
				$rate = $this->_getRate( $row );
				$output = '<table cellspacing="0" class="batch-prices-table"><col width="100"/><col width="40"/>';
				foreach ($value as $stockId => $price) {

					if ($currencyCode) {
						$price = sprintf( '%f', (double)$price * $rate );
						$price = Mage::app(  )->getLocale(  )->currency( $currencyCode )->toCurrency( $price );
					}

					$output .= '<tr><td>' . $helper->getWarehouseTitleByStockId( $stockId ) . '</td><td>' . $price . '</td></tr>';
				}

				$output .= '</table>';
				return $output;
			}

			return '';
		}
	}

?>